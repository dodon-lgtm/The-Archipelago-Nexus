<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * GUARD: tidak boleh ada sisa Git conflict marker yang bocor ke UI.
 *
 * Saat merge/stash gagal, Git menyisakan:
 *     <<<<<<< Updated upstream
 *     =======
 *     Stashed changes
 *     >>>>>>>
 *
 * Kalau file Blade itu ikut ter-render, marker tersebut muncul sebagai TEKS
 * di halaman user (terlihat persis seperti bug yang pernah terjadi di
 * /freelancer/pendapatan) dan merusak tampilan.
 *
 * Test ini memindai SELURUH file project (kecuali vendor/ & .git/) supaya
 * halaman yang belum dicek manual ikut terlindungi.
 */
class NoGitConflictMarkerTest extends TestCase
{
    /** Folder yang tidak boleh ikut dipindai. */
    private const SKIP = ['vendor', '.git', 'node_modules', 'storage'];

    public function test_tidak_ada_conflict_marker_di_seluruh_project(): void
    {
        $findings = [];
        $scanned  = 0;

        foreach ($this->projectFiles() as $file) {
            $scanned++;
            $lines = file($file, FILE_IGNORE_NEW_LINES);
            if ($lines === false) {
                continue;
            }

            foreach ($lines as $i => $line) {
                $t = ltrim($line);

                // Git menulis 7 tanda '<' / '>' / '=' PERSIS di awal baris,
                // diikuti spasi/tab/akhir baris. Banner dekoratif seperti
                // "/* ============== */" atau "=====" di README BUKAN konflik.
                if (preg_match('/^[ \t]*(<<<<<<<|=======|>>>>>>>)([ \t].*)?$/', $t)) {
                    $findings[] = $this->label($file) . ':' . ($i + 1) . '  ' . trim($line);
                }
            }
        }

        $this->assertGreaterThan(
            100,
            $scanned,
            'File yang dipindai terlalu sedikit — guard ini tidak bermakna.'
        );

        $this->assertSame(
            [],
            $findings,
            "Ada Git conflict marker yang masih tertinggal:\n  " . implode("\n  ", $findings)
        );
    }

    /**
     * '=======' kadang muncul sebagai garis bawah heading di Markdown README,
     * jadi formatnya tidak bisa dipakai sendiri sebagai penanda konflik.
     * Test ini memastikan file yang bermarker itu benar-benar terdeteksi.
     */
    public function test_scanner_mendeteksi_konflik_yang_known(): void
    {
        $file = tempnam(sys_get_temp_dir(), 'conflict') . '.txt';
        file_put_contents($file, implode("\n", [
            'sebelum',
            '<<<<<<< Updated upstream',
            'versi A',
            '=======',
            'versi B',
            '>>>>>>> Stashed changes',
            'sesudah',
        ]));

        $lines = file($file, FILE_IGNORE_NEW_LINES);
        $found = [];
        foreach ($lines as $i => $line) {
            $t = ltrim($line);
            if (preg_match('/^[ \t]*(<<<<<<<|=======|>>>>>>>)([ \t].*)?$/', $t)) {
                $found[] = $i + 1;
            }
        }
        @unlink($file);

        $this->assertSame([2, 4, 6], $found, 'Guard gagal mendeteksi pola konflik yang known-bad.');
    }

    /** @return string[] seluruh file teks project */
    private function projectFiles(): array
    {
        $out = [];

        $rii = new \RecursiveIteratorIterator(
            new \RecursiveCallbackFilterIterator(
                new \RecursiveDirectoryIterator(base_path(), \FilesystemIterator::SKIP_DOTS),
                function ($cur) {
                    if ($cur->isDir()) {
                        return !in_array($cur->getFilename(), self::SKIP, true);
                    }
                    return true;
                }
            )
        );

        foreach ($rii as $f) {
            if (!$f->isFile()) {
                continue;
            }
            if (in_array(strtolower($f->getExtension()), ['php', 'js', 'css', 'md', 'json', 'txt', 'html'], true)) {
                $out[] = $f->getPathname();
            }
        }

        return $out;
    }

    private function label(string $path): string
    {
        return str_replace(base_path() . '\\', '', $path);
    }
}
