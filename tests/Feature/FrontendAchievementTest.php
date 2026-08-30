<?php

namespace Tests\Feature;

use App\Models\Achievement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendAchievementTest extends TestCase
{
    use RefreshDatabase;

    public function test_achievement_page_is_accessible_without_cms_content(): void
    {
        $this->get(route('achievement'))
            ->assertOk()
            ->assertSee('Prestasi MIIS')
            ->assertSee('Pencapaian Sekolah');
    }

    public function test_achievement_page_displays_only_published_achievements_in_newest_order(): void
    {
        Achievement::create([
            'title' => 'Prestasi Terdahulu',
            'slug' => 'prestasi-terdahulu',
            'achievement_date' => now()->subMonth(),
            'is_published' => true,
        ]);

        Achievement::create([
            'title' => 'Prestasi Terbaru',
            'slug' => 'prestasi-terbaru',
            'achievement_date' => now(),
            'is_published' => true,
        ]);

        Achievement::create([
            'title' => 'Prestasi Belum Terbit',
            'slug' => 'prestasi-belum-terbit',
            'is_published' => false,
        ]);

        $response = $this->get(route('achievement'))
            ->assertOk()
            ->assertSee('Prestasi Terbaru')
            ->assertSee('Prestasi Terdahulu')
            ->assertDontSee('Prestasi Belum Terbit');

        $this->assertLessThan(
            strpos($response->getContent(), 'Prestasi Terdahulu'),
            strpos($response->getContent(), 'Prestasi Terbaru'),
        );
    }

    public function test_published_achievement_can_be_opened_on_its_detail_page(): void
    {
        $achievement = Achievement::create([
            'title' => 'Juara 1 Lomba Sains Madrasah',
            'slug' => 'juara-1-lomba-sains-madrasah',
            'recipient_name' => 'Tim Sains MIIS',
            'level' => 'Tingkat Kabupaten',
            'organizer' => 'Kementerian Agama Kabupaten Probolinggo',
            'achievement_date' => '2026-08-22',
            'description' => 'Meraih juara pertama dalam Lomba Sains Madrasah.',
            'is_published' => true,
        ]);

        $this->get(route('achievement'))
            ->assertOk()
            ->assertSee(route('achievement.show', $achievement));

        $this->get(route('achievement.show', $achievement))
            ->assertOk()
            ->assertSee('Juara 1 Lomba Sains Madrasah')
            ->assertSee('Tim Sains MIIS')
            ->assertSee('Tingkat Kabupaten')
            ->assertSee('Kementerian Agama Kabupaten Probolinggo')
            ->assertSee('Meraih juara pertama dalam Lomba Sains Madrasah.');
    }

    public function test_unpublished_achievement_cannot_be_opened_on_the_public_detail_page(): void
    {
        $achievement = Achievement::create([
            'title' => 'Prestasi Belum Diterbitkan',
            'slug' => 'prestasi-belum-diterbitkan',
            'is_published' => false,
        ]);

        $this->get(route('achievement.show', $achievement))
            ->assertNotFound();
    }
}
