<?php

namespace App\Services;

use Carbon\CarbonInterface;
use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * InvoiceNumberService — PUSAT alokasi nomor invoice (M-2B).
 *
 * Masalah lama (audit M-2B / F-03) yang dihilangkan:
 *   1) Generator workspace memakai `whereDate(created_at)` + `orderBy('id','desc')`
 *      + `substr($invoice, -4)` → race condition, O(N) scan, dan terpotong saat
 *      sequence melewati 9999.
 *   2) Generator quota memakai `Payment::max('id') + 1` → nomor bergantung pada
 *      baris payment (bukan sequence), bisa bentrok, dan mundur bila baris dihapus.
 *   3) Kedua scope bercampur (invoice quota menentukan nomor workspace).
 *
 * Desain baru: sequence terpisah per (scope, tanggal) di tabel `invoice_sequences`.
 *   - next('workspace') → INV-YYYYMMDD-NNNN
 *   - next('quota')     → INV-QOT-YYYYMMDD-NNNN
 *
 * Alokasi WAJIB berada di dalam transaksi yang sama dengan Payment::create()
 * (kedua generator sudah memanggilnya di dalam transaksi). Bila service dipanggil
 * tanpa transaksi aktif, service membungkus alokasinya sendiri dengan transaksi
 * agar tetap atomik.
 *
 * Race safety (first-of-day, saat row sequence BELUM ada):
 *   1. INSERT ... IGNORE row (scope, seq_date) dengan `last_number` hasil seed
 *      dari invoice VALID yang sudah ada. Hanya satu request yang berhasil insert;
 *      request lain otomatis di-ignore karena UNIQUE(scope, seq_date).
 *   2. SELECT ... FOR UPDATE row tersebut — locking read (melihat data committed
 *      terbaru), sehingga request lain menunggu sampai transaksi pemegang lock selesai.
 *   3. increment `last_number` + update.
 *   4. hasilkan nomor invoice → caller membuat Payment di transaksi yang sama.
 * Dengan urutan ini dua request concurrent tidak pernah memperoleh nomor yang sama.
 *
 * Catatan driver: `SELECT ... FOR UPDATE` adalah row lock MySQL (InnoDB). Pada
 * SQLite (environment test) lock tersebut no-op, tetapi SQLite menserialisasi
 * penulisan; test SQLite TIDAK membuktikan row locking MySQL.
 *
 * Format: prefix existing + tanggal 8 digit + nomor minimal 4 digit, tanpa
 * underscore (kompatibel dengan MidtransService::buildOrderId/resolveInvoiceFromOrderId).
 */
class InvoiceNumberService
{
    public const SCOPE_WORKSPACE = 'workspace';

    public const SCOPE_QUOTA = 'quota';

    /** @var array<int, string> */
    public const SCOPES = [
        self::SCOPE_WORKSPACE,
        self::SCOPE_QUOTA,
    ];

    private const PREFIX_WORKSPACE = 'INV-';

    private const PREFIX_QUOTA = 'INV-QOT-';

    /**
     * Alokasikan nomor invoice berikutnya untuk scope + tanggal.
     *
     * Panggil DI DALAM transaksi yang sama dengan Payment::create() agar nomor
     * yang terpakai tidak pernah "bocor" bila transaksi dibatalkan.
     */
    public static function next(string $scope, ?CarbonInterface $date = null): string
    {
        static::assertScope($scope);

        $seqDate = $date ? Carbon::parse($date) : Carbon::now();

        $allocate = fn (): string => static::allocate($scope, $seqDate);

        return DB::transactionLevel() > 0 ? $allocate() : DB::transaction($allocate);
    }

    /**
     * Alokasikan nomor lalu jalankan $persist($invoiceNumber) — biasanya
     * `fn (string $n) => Payment::create([... 'invoice_number' => $n ...])`.
     *
     * Backstop UNIQUE `payments.invoice_number`: bila collision terjadi karena
     * kondisi tak terduga, retry MAKSIMAL 1 kali dengan nomor baru. Bila retry
     * juga gagal, exception dilempar dengan pesan jelas (tidak ada retry tak
     * terbatas, tidak ada penghapusan/rename invoice).
     *
     * @param  callable(string): mixed  $persist
     */
    public static function createWithRetry(string $scope, callable $persist, ?CarbonInterface $date = null): mixed
    {
        try {
            return $persist(static::next($scope, $date));
        } catch (QueryException $e) {
            if (!static::isDuplicateInvoiceNumber($e)) {
                throw $e;
            }

            Log::warning('InvoiceNumberService: invoice_number bentrok, retry 1x', [
                'scope' => $scope,
                'error' => $e->getMessage(),
            ]);

            try {
                return $persist(static::next($scope, $date));
            } catch (QueryException $retryError) {
                if (!static::isDuplicateInvoiceNumber($retryError)) {
                    throw $retryError;
                }

                throw new \RuntimeException(
                    'Gagal membuat invoice scope ' . $scope . ': nomor invoice bentrok dua kali berturut-turut '
                    . '(batas retry 1x). Tidak ada invoice existing yang diubah. Silakan coba lagi.',
                    0,
                    $retryError
                );
            }
        }
    }

    /**
     * Apakah QueryException berasal dari UNIQUE `payments.invoice_number`?
     * Dicek dari SQLSTATE + nama index/kolom (bukan pesan bahasa tertentu).
     */
    public static function isDuplicateInvoiceNumber(QueryException $e): bool
    {
        $sqlState = (string) ($e->errorInfo[0] ?? '');

        if ($sqlState !== '23000' && !str_contains($e->getMessage(), '23000')) {
            return false;
        }

        $message = $e->getMessage();

        return str_contains($message, 'payments_invoice_number_unique')
            || str_contains($message, 'payments.invoice_number');
    }

    private static function allocate(string $scope, CarbonInterface $seqDate): string
    {
        $dateKey = $seqDate->format('Y-m-d');

        // 1) Pastikan row sequence ada — race-safe (INSERT IGNORE + UNIQUE(scope,seq_date)).
        //    last_number di-seed dari invoice VALID (scope+tanggal) yang sudah ada agar
        //    tidak collision dengan invoice existing.
        DB::table('invoice_sequences')->insertOrIgnore([
            'scope' => $scope,
            'seq_date' => $dateKey,
            'last_number' => static::seedFromExistingInvoices($scope, $seqDate),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2) Kunci row — request concurrent lain menunggu di sini.
        $row = DB::table('invoice_sequences')
            ->where('scope', $scope)
            ->where('seq_date', $dateKey)
            ->lockForUpdate()
            ->first();

        if ($row === null) {
            throw new \RuntimeException(
                'Gagal mengalokasikan nomor invoice: row sequence ' . $scope . '/' . $dateKey . ' tidak ditemukan.'
            );
        }

        $next = (int) $row->last_number + 1;

        DB::table('invoice_sequences')
            ->where('id', $row->id)
            ->update([
                'last_number' => $next,
                'updated_at' => now(),
            ]);

        return static::format($scope, $seqDate, $next);
    }

    /**
     * Nomor terakhir yang sudah dipakai invoice VALID pada scope+tanggal tersebut.
     *
     * Hanya invoice yang cocok format resmi scope-nya yang dihitung
     * (workspace: ^INV-YYYYMMDD-NNNN$ / quota: ^INV-QOT-YYYYMMDD-NNNN$, NNNN >= 4 digit).
     * Invoice legacy/non-conforming (mis. INV-QOT-RENDER-0001) dan invoice scope lain
     * TIDAK pernah dipakai sebagai sumber counter.
     */
    private static function seedFromExistingInvoices(string $scope, CarbonInterface $date): int
    {
        $ymd = $date->format('Ymd');
        $prefix = static::prefix($scope) . $ymd . '-';

        // Prefix LIKE sudah memisahkan scope secara alami:
        // 'INV-YYYYMMDD-%' tidak pernah cocok dengan 'INV-QOT-YYYYMMDD-%'.
        $pattern = '/^' . preg_quote(static::prefix($scope) . $ymd, '/') . '\-(\d{4,})$/D';

        $max = 0;

        $invoices = DB::table('payments')
            ->where('invoice_number', 'like', $prefix . '%')
            ->pluck('invoice_number');

        foreach ($invoices as $invoice) {
            if (preg_match($pattern, (string) $invoice, $matches) === 1) {
                $max = max($max, (int) $matches[1]);
            }
        }

        return $max;
    }

    private static function format(string $scope, CarbonInterface $date, int $number): string
    {
        // str_pad TIDAK memotong: 10000 tetap "10000" (>= 4 digit, tidak pernah kembali ke 0000).
        return static::prefix($scope) . $date->format('Ymd') . '-' . str_pad((string) $number, 4, '0', STR_PAD_LEFT);
    }

    private static function prefix(string $scope): string
    {
        return $scope === self::SCOPE_QUOTA ? self::PREFIX_QUOTA : self::PREFIX_WORKSPACE;
    }

    private static function assertScope(string $scope): void
    {
        if (!in_array($scope, self::SCOPES, true)) {
            throw new \InvalidArgumentException('Scope invoice tidak dikenal: ' . $scope);
        }
    }
}
