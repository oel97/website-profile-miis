<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\PrincipalMessage;
use App\Models\SchoolProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_accessible_without_cms_content(): void
    {
        $this->get(route('profile'))
            ->assertOk()
            ->assertSee('Profil Sekolah')
            ->assertSee("MI Islamiyah Syafi'iyah");
    }

    public function test_profile_page_displays_published_cms_content(): void
    {
        SchoolProfile::create([
            'name' => "MI Islamiyah Syafi'iyah",
            'short_description' => 'Madrasah untuk generasi berakhlak.',
            'about' => 'Tentang MI Islamiyah Syafi\'iyah.',
            'address' => 'Jl. Madrasah Nomor 1',
            'npsn' => '12345678',
            'accreditation' => 'A',
        ]);

        Page::create([
            'title' => 'Sejarah MIIS',
            'slug' => 'sejarah',
            'content' => 'Sejarah berdirinya madrasah.',
            'is_published' => true,
            'published_at' => now(),
        ]);

        Page::create([
            'title' => 'Visi dan Misi MIIS',
            'slug' => 'visi-misi',
            'content' => 'Menjadi madrasah unggul dan Islami.',
            'is_published' => true,
            'published_at' => now(),
        ]);

        PrincipalMessage::create([
            'name' => 'Ahmad Fauzi, S.Pd.I.',
            'position' => 'Kepala Madrasah',
            'title' => 'Sambutan Kepala Madrasah',
            'message' => 'Mari tumbuh bersama dalam kebaikan.',
            'is_active' => true,
        ]);

        $this->get(route('profile'))
            ->assertOk()
            ->assertSee('Tentang MI Islamiyah Syafi\'iyah.')
            ->assertSee('Sejarah MIIS')
            ->assertSee('Sejarah berdirinya madrasah.')
            ->assertSee('Visi dan Misi MIIS')
            ->assertSee('Menjadi madrasah unggul dan Islami.')
            ->assertSee('Sambutan Kepala Madrasah')
            ->assertSee('Ahmad Fauzi, S.Pd.I.');
    }
}
