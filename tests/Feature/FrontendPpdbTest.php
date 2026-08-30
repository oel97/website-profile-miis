<?php

namespace Tests\Feature;

use App\Models\PpdbPeriod;
use App\Models\PpdbRequirement;
use App\Models\PpdbStep;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendPpdbTest extends TestCase
{
    use RefreshDatabase;

    public function test_ppdb_page_is_accessible_without_an_active_published_period(): void
    {
        $this->get(route('ppdb'))
            ->assertOk()
            ->assertSee('PPDB MIIS')
            ->assertSee('Periode PPDB belum tersedia.');
    }

    public function test_ppdb_page_displays_the_active_published_period_with_active_requirements_and_sorted_steps(): void
    {
        $period = $this->period('PPDB 2026/2027', 'ppdb-2026-2027', true, true);
        $this->period('PPDB Draft', 'ppdb-draft', true, false);
        $this->period('PPDB Tidak Aktif', 'ppdb-tidak-aktif', false, true);

        PpdbRequirement::create([
            'ppdb_period_id' => $period->id,
            'title' => 'Fotokopi Kartu Keluarga',
            'description' => 'Satu lembar salinan kartu keluarga.',
            'sort_order' => 2,
            'is_active' => true,
        ]);
        PpdbRequirement::create([
            'ppdb_period_id' => $period->id,
            'title' => 'Akta Kelahiran',
            'sort_order' => 1,
            'is_active' => true,
        ]);
        PpdbRequirement::create([
            'ppdb_period_id' => $period->id,
            'title' => 'Persyaratan Nonaktif',
            'is_active' => false,
        ]);

        PpdbStep::create([
            'ppdb_period_id' => $period->id,
            'title' => 'Isi Formulir',
            'sort_order' => 2,
        ]);
        PpdbStep::create([
            'ppdb_period_id' => $period->id,
            'title' => 'Buka Tautan Pendaftaran',
            'sort_order' => 1,
        ]);

        $response = $this->get(route('ppdb'))
            ->assertOk()
            ->assertSee('PPDB 2026/2027')
            ->assertSee('Tahun Ajaran 2026/2027')
            ->assertSee('Akta Kelahiran')
            ->assertSee('Fotokopi Kartu Keluarga')
            ->assertSee('Buka Tautan Pendaftaran')
            ->assertSee('Isi Formulir')
            ->assertSee('wa.me/628123456789')
            ->assertDontSee('PPDB Draft')
            ->assertDontSee('PPDB Tidak Aktif')
            ->assertDontSee('Persyaratan Nonaktif');

        $this->assertLessThan(
            strpos($response->getContent(), 'Fotokopi Kartu Keluarga'),
            strpos($response->getContent(), 'Akta Kelahiran'),
        );
        $this->assertLessThan(
            strpos($response->getContent(), 'Isi Formulir'),
            strpos($response->getContent(), 'Buka Tautan Pendaftaran'),
        );
    }

    private function period(string $title, string $slug, bool $isActive, bool $isPublished): PpdbPeriod
    {
        return PpdbPeriod::create([
            'academic_year' => '2026/2027',
            'title' => $title,
            'slug' => $slug,
            'registration_url' => 'https://pendaftaran.test/miis',
            'contact_whatsapp' => '08123456789',
            'is_active' => $isActive,
            'is_published' => $isPublished,
        ]);
    }
}
