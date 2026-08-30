<?php

namespace Tests\Feature;

use App\Models\FeaturedProgram;
use App\Models\SchoolProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendProgramTest extends TestCase
{
    use RefreshDatabase;

    public function test_program_page_is_accessible_without_cms_content(): void
    {
        $this->get(route('program'))
            ->assertOk()
            ->assertSee('Program Unggulan')
            ->assertSee('Pilihan Program');
    }

    public function test_program_page_displays_only_active_programs_in_sort_order(): void
    {
        SchoolProfile::create([
            'name' => "MI Islamiyah Syafi'iyah",
            'address' => 'Jl. Madrasah Nomor 1',
        ]);

        FeaturedProgram::create([
            'title' => 'Program Kedua',
            'slug' => 'program-kedua',
            'short_description' => 'Deskripsi program kedua.',
            'icon' => 'B2',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        FeaturedProgram::create([
            'title' => 'Program Pertama',
            'slug' => 'program-pertama',
            'short_description' => 'Deskripsi program pertama.',
            'icon' => 'P1',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        FeaturedProgram::create([
            'title' => 'Program Tidak Aktif',
            'slug' => 'program-tidak-aktif',
            'description' => 'Tidak untuk publik.',
            'is_active' => false,
        ]);

        $response = $this->get(route('program'))
            ->assertOk()
            ->assertSee('Program Pertama')
            ->assertSee('Deskripsi program pertama.')
            ->assertSee('Program Kedua')
            ->assertDontSee('Program Tidak Aktif');

        $this->assertLessThan(
            $response->getContent() ? strpos($response->getContent(), 'Program Kedua') : 0,
            $response->getContent() ? strpos($response->getContent(), 'Program Pertama') : 0,
        );
    }

    public function test_active_program_can_be_opened_on_its_detail_page(): void
    {
        $program = FeaturedProgram::create([
            'title' => 'Program Tahfiz',
            'slug' => 'program-tahfiz',
            'short_description' => 'Pembiasaan mencintai Al-Qur’an.',
            'description' => 'Program pendampingan hafalan Al-Qur’an untuk peserta didik.',
            'icon' => 'Tahfiz',
            'is_active' => true,
        ]);

        $inactiveProgram = FeaturedProgram::create([
            'title' => 'Program Internal',
            'slug' => 'program-internal',
            'is_active' => false,
        ]);

        $this->get(route('program'))
            ->assertOk()
            ->assertSee(route('program.show', $program));

        $this->get(route('program.show', $program))
            ->assertOk()
            ->assertSee('Program Tahfiz')
            ->assertSee('Program pendampingan hafalan Al-Qur’an untuk peserta didik.');

        $this->get(route('program.show', $inactiveProgram))
            ->assertNotFound();
    }
}
