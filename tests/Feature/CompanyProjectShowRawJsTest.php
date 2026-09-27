<?php

namespace Tests\Feature;

use App\Models\CompanyAccountRequest;
use App\Models\Penawaran;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Regression test: JavaScript mentah tampil sebagai TEKS di halaman detail
 * proyek Company (bagian "Penawaran Freelancer").
 *
 * Root cause: blok `x-data` berisi literal kutip ganda di dalam
 * querySelector ('[data-penawaran-card="' + item.id + '"]'). Atribut HTML
 * x-data dibungkus kutip ganda, sehingga kutip ganda di dalam JS memutus
 * atribut lebih awal dan sisa baris JavaScript bocor ke halaman sebagai teks.
 *
 * Test ini mengunci syaratnya: atribut x-data harus utuh (tidak terpotong)
 * dan tidak boleh ada baris JS yang bocor sebagai teks di luar <script>.
 */
class CompanyProjectShowRawJsTest extends TestCase
{
    use RefreshDatabase;

    private User $company;
    private Project $project;

    protected function setUp(): void
    {
        parent::setUp();

        $this->company = User::factory()->create(['role' => 'company']);

        CompanyAccountRequest::create([
            'company_name'    => $this->company->name,
            'contact_person' => $this->company->name,
            'company_email'  => $this->company->email,
            'company_phone'  => '081234567890',
            'company_address' => 'Alamat Perusahaan',
            'request_status'  => 'disetujui',
        ]);

        $this->project = Project::factory()->create([
            'user_id' => $this->company->id,
            'status'  => Project::STATUS_OPEN,
        ]);

        $freelancer = User::factory()->create(['role' => 'freelancer']);

        Penawaran::create([
            'project_id'      => $this->project->id,
            'freelancer_id'   => $freelancer->id,
            'harga_penawaran' => 1500000,
            'pesan'           => 'Saya bisa mengerjakan proyek ini.',
            'status'          => 'Menunggu',
            'estimasi_hari'   => 14,
        ]);
    }

    public function test_halaman_detail_proyek_tidak_membocorkan_javascript_mentah(): void
    {
        $html = $this->renderShowPage();

        // 1. Source JS tidak boleh muncul sebagai TEKS VISIBLE di halaman.
        //    (Script-nya memang BOLEH ada di dalam nilai atribut x-data —
        //    yang dilarang adalah bocornya isi ke badan halaman.)
        $teksTampil = $this->teksTerlihat($html);

        foreach ([
            'const q = this.search.toLowerCase()',
            'const matchSearch',
            'const matchStatus',
            'return matchSearch && matchStatus;',
            'this.items.filter(item =>',
            'this.sortedItems().forEach',
            'get filteredItems()',
            'container.querySelectorAll',
        ] as $snippet) {
            $this->assertStringNotContainsString(
                $snippet,
                $teksTampil,
                "Snippet JavaScript \"$snippet\" bocor sebagai TEKS yang terlihat user."
            );
        }

        // 2. Atribut x-data Penawaran Freelancer harus UTUH: seluruh logic
        //    filter + sort berada di dalam SATU nilai atribut (tidak terpotong).
        $this->assertSame(
            1,
            preg_match("/x-data=\"\{\s*search: ''.*?\}\"/s", $html),
            'x-data Penawaran Freelancer tidak utuh / terpotong.'
        );

        // 3. Logika search, filter status, dan sorting tetap utuh.
        $this->assertStringContainsString("search: '',", $html);
        $this->assertStringContainsString("statusFilter: 'all',", $html);
        $this->assertStringContainsString('sortedItems()', $html);
        $this->assertStringContainsString('applySort()', $html);
        $this->assertStringContainsString('get filteredItems()', $html);
        $this->assertStringContainsString("this.sortOption === 'harga_tertinggi'", $html);
        $this->assertStringContainsString("this.sortOption === 'harga_terendah'", $html);
        $this->assertStringContainsString("this.statusFilter === 'all'", $html);
        $this->assertStringContainsString("getAttribute('data-penawaran-card')", $html);
    }

    /**
     * Atribut x-data harus tetap bisa dibaca Alpine sebagai satu objek JS utuh.
     * Kalau ada kutip ganda di dalamnya, atribut terpotong dan Alpine gagal
     * mengevaluasi x-data (search/filter/sorting mati).
     */
    public function test_x_data_penawaran_freelancer_tetap_valid(): void
    {
        $html = $this->renderShowPage();

        $this->assertSame(1, preg_match('/x-data="(\{.*?\})"/s', $html, $m));

        // Nilai atribut harus bisa di-parse sebagai objek JS yang utuh.
        $decoded = html_entity_decode($m[1], ENT_QUOTES, 'UTF-8');
        $decoded = preg_replace('/\bitems:\s*JSON\.parse\(.*?\),/s', 'items: [],', $decoded);

        $this->assertNotNull(
            json_decode(preg_replace('/(\{|,)\s*([A-Za-z_$][\w$]*)\s*:/', '$1"$2":', $decoded), true),
            'x-data tidak bisa di-parse sebagai objek JavaScript yang utuh: ' . $decoded
        );
    }

    /** HTML halaman detail proyek. */
    private function renderShowPage(): string
    {
        return $this->actingAs($this->company)
            ->get(route('company.projects.show', $this->project))
            ->assertOk()
            ->getContent();
    }

    /**
     * Semua teks yang benar-benar DI-RENDER user, yaitu seluruh text node
     * di luar <script>/<style>. Isi atribut TIDAK ikut karena atribut tidak
     * pernah ditampilkan di halaman.
     */
    private function teksTerlihat(string $html): string
    {
        $doc = new \DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        libxml_clear_errors();

        foreach (iterator_to_array($doc->getElementsByTagName('script')) as $node) {
            $node->parentNode->removeChild($node);
        }
        foreach (iterator_to_array($doc->getElementsByTagName('style')) as $node) {
            $node->parentNode->removeChild($node);
        }

        return $doc->textContent;
    }
}
