<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * SCAN GLOBAL: tidak boleh ada JavaScript/Alpine yang bocor sebagai TEKS di
 * halaman mana pun, untuk semua role (public, freelancer, company, admin).
 *
 * PENDEKATAN — statis di seluruh file Blade, bukan render per-route, supaya
 * cepat dan benar-benar menutup SELURUH website (bukan hanya halaman yang
 * kebetulan punya data di database).
 *
 * Aturan HTML:  name="value"  -> nilai atribut berakhir di kutip PERTAMA.
 * Kalau nilai itu punya kurung kurawal/siku yang tidak seimbang DAN teks
 * setelah kutip penutup bukan awal atribut yang sah, berarti ada kutip " di
 * dalam JavaScript yang memotong atribut lebih awal. Sisa JS itu lalu bocor
 * ke badan halaman dan TERLIHAT sebagai teks oleh user.
 *
 * Contoh bug nyata (company/projects/show.blade.php, applySort):
 *   const card = c.querySelector('[data-penawaran-card="' + item.id + '"]');
 *                 ^ kutip " itu menutup x-data lebih awal
 *
 * Atribut yang SEHAT (x-data utuh) tetap lolos: kurungnya seimbang dan
 * setelah kutip penutup langsung mengikuti atribut lain atau '>'.
 */
class GlobalNoRawJsTextTest extends TestCase
{
    /** Atribut yang isinya dieksekusi / di-query oleh Alpine-JS. */
    private const JS_ATTR = '/^(x-|:|@|v-|wire:|on[a-z]+$|data-)/';

    /** Jejak JavaScript di dalam nilai atribut. */
    private const JS_CODE = '/=>|\bthis\.|\bconst\b|\breturn\b|\$nextTick|\$watch|\.filter\(|\.map\(/';

    public function test_tidak_ada_atribut_javascript_terpotong_di_seluruh_blade(): void
    {
        $findings = [];
        $files    = $this->bladeFiles();
        $attrs    = 0;

        foreach ($files as $file) {
            foreach ($this->scanFile($file, $attrs) as $f) {
                $findings[] = $f;
            }
        }

        // Pastikan scan-nya benar-benar bekerja (bukan diam karena bug di scanner).
        $this->assertGreaterThan(
            200,
            $attrs,
            'Atribut JS yang dipindai terlalu sedikit — scanner kemungkinan salah.'
        );

        $this->assertSame(
            [],
            $findings,
            "Ada atribut JS yang terpotong sehingga bocor sebagai teks:\n  "
            . implode("\n  ", $findings)
        );
    }

    /**
     * Scanner harus mendeteksi pola bug yang nyata.
     * Ini mengunci agar scanner tidak diam-diam tidak berguna.
     */
    public function test_scanner_mendeteksi_pola_bocor_yang_known(): void
    {
        $html = <<<'HTML'
        <div
            x-data="{
                items: [1, 2],
                apply() {
                    const card = c.querySelector('[data-x="' + item.id + '"]');
                    if (card) c.appendChild(card);
                }
            }"
            class="p-4"
        ></div>
        HTML;

        $tmp = tempnam(sys_get_temp_dir(), 'jsscan') . '.blade.php';
        file_put_contents($tmp, $html);

        $attrs = 0;
        $found = $this->scanFile($tmp, $attrs);
        @unlink($tmp);

        $this->assertNotEmpty($found, 'Scanner gagal mendeteksi pola bocor yang known-bad.');
    }

    /** @return string[] daftar file blade */
    private function bladeFiles(): array
    {
        $root = resource_path('views');
        $out  = [];

        $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($root));
        foreach ($rii as $f) {
            if ($f->isFile() && substr($f->getFilename(), -10) === '.blade.php') {
                $out[] = $f->getPathname();
            }
        }
        sort($out);

        return $out;
    }

    /**
     * @return string[] temuan pada satu file
     */
    private function scanFile(string $file, int &$attrs): array
    {
        $src = file_get_contents($file);
        $out = [];

        preg_match_all(
            '/([a-zA-Z_:@][a-zA-Z0-9_:.\-]*)\s*=\s*(["\'])/',
            $src,
            $m,
            PREG_OFFSET_CAPTURE | PREG_SET_ORDER
        );

        foreach ($m as $hit) {
            $name  = $hit[1][0];
            $quote = $hit[2][0];
            $start = $hit[0][1] + strlen($hit[0][0]) - 1; // posisi kutip pembuka

            if (!preg_match(self::JS_ATTR, $name)) {
                continue;
            }

            $close = strpos($src, $quote, $start + 1);
            if ($close === false) {
                continue;
            }
            $attrs++;

            $value = substr($src, $start + 1, $close - $start - 1);

            // 1. Kurung harus seimbang pada nilai yang terambil.
            $unbalanced = [];
            foreach (['{' => '}', '(' => ')', '[' => ']'] as $o => $c) {
                $co = substr_count($value, $o);
                $cc = substr_count($value, $c);
                if ($co !== $cc) {
                    $unbalanced[] = "{$o}{$co}/{$c}{$cc}";
                }
            }
            if (!$unbalanced || !preg_match(self::JS_CODE, $value)) {
                continue;
            }

            // 2. Setelah kutip penutup harusnya awal atribut sah / '>'.
            $rest = ltrim(substr($src, $close + 1, 400));
            $ok   = preg_match('/^(>|\/>|[a-zA-Z_:@][a-zA-Z0-9_:.\-]*\s*(=|[\s>\/]|$))/', $rest);
            if ($ok) {
                continue;
            }

            $line = substr_count(substr($src, 0, $start), "\n") + 1;
            $out[] = sprintf(
                '%s:%d attr=%s tidak seimbang(%s) nilai="%s" bocor="%s"',
                str_replace(base_path() . '\\', '', $file),
                $line,
                $name,
                implode(',', $unbalanced),
                substr(preg_replace('/\s+/', ' ', $value), -60),
                substr(preg_replace('/\s+/', ' ', $rest), 0, 90)
            );
        }

        return $out;
    }
}
