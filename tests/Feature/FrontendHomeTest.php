<?php

namespace Tests\Feature;

use App\Models\Achievement;
use App\Models\Agenda;
use App\Models\Contact;
use App\Models\FeaturedProgram;
use App\Models\HeroSlide;
use App\Models\HomepageSection;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\SchoolProfile;
use App\Models\SiteSetting;
use App\Models\SocialLink;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendHomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_is_accessible_without_cms_content(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee("MI Islamiyah Syafi'iyah")
            ->assertSee('Madrasah Unggul Berkarakter Islami');
    }

    public function test_homepage_displays_published_cms_content(): void
    {
        SchoolProfile::create([
            'name' => "MI Islamiyah Syafi'iyah",
            'tagline' => 'Madrasah Unggul Berkarakter Islami',
            'short_description' => 'Pendidikan dasar berlandaskan nilai Islami.',
            'address' => 'Jl. Madrasah Nomor 1',
        ]);

        SiteSetting::create([
            'group' => 'general',
            'key' => 'site_name',
            'value' => 'MIIS Hebat',
            'type' => 'text',
            'is_public' => true,
        ]);

        HeroSlide::create([
            'title' => 'Belajar dengan Gembira',
            'subtitle' => 'Tumbuh bersama nilai kebaikan.',
            'image_path' => 'heroes/utama.jpg',
            'is_active' => true,
        ]);

        FeaturedProgram::create([
            'title' => 'Tahfiz Al-Qur\'an',
            'slug' => 'tahfiz-al-quran',
            'short_description' => 'Program hafalan Al-Qur\'an.',
            'is_active' => true,
        ]);

        $category = PostCategory::create([
            'name' => 'Kegiatan',
            'slug' => 'kegiatan',
            'is_active' => true,
        ]);

        Post::create([
            'post_category_id' => $category->id,
            'title' => 'Kegiatan Belajar Bersama',
            'slug' => 'kegiatan-belajar-bersama',
            'content' => 'Isi berita kegiatan belajar bersama.',
            'status' => 'published',
            'published_at' => now(),
        ]);

        Achievement::create([
            'title' => 'Juara Olimpiade',
            'slug' => 'juara-olimpiade',
            'level' => 'Kabupaten',
            'is_published' => true,
        ]);

        Agenda::create([
            'title' => 'Pesantren Ramadan',
            'slug' => 'pesantren-ramadan',
            'start_at' => now(),
            'is_published' => true,
        ]);

        Contact::create([
            'label' => 'Alamat',
            'type' => 'address',
            'value' => 'Jl. Madrasah Nomor 1',
            'is_active' => true,
        ]);

        SocialLink::create([
            'platform' => 'Instagram',
            'label' => 'Instagram MIIS',
            'url' => 'https://instagram.com/miis',
            'is_active' => true,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('MIIS Hebat')
            ->assertSee('Belajar dengan Gembira')
            ->assertSee("Tahfiz Al-Qur'an")
            ->assertSee('Kegiatan Belajar Bersama')
            ->assertSee('Juara Olimpiade')
            ->assertSee('Pesantren Ramadan')
            ->assertSee('Instagram MIIS');
    }

    public function test_homepage_does_not_display_posts_from_inactive_categories(): void
    {
        $activeCategory = PostCategory::create([
            'name' => 'Kegiatan',
            'slug' => 'kegiatan',
            'is_active' => true,
        ]);
        $inactiveCategory = PostCategory::create([
            'name' => 'Arsip',
            'slug' => 'arsip',
            'is_active' => false,
        ]);

        Post::create([
            'post_category_id' => $activeCategory->id,
            'title' => 'Berita Kategori Aktif',
            'slug' => 'berita-kategori-aktif',
            'content' => 'Berita yang boleh tampil.',
            'status' => 'published',
            'published_at' => now(),
        ]);
        Post::create([
            'post_category_id' => $inactiveCategory->id,
            'title' => 'Berita Kategori Nonaktif',
            'slug' => 'berita-kategori-nonaktif',
            'content' => 'Berita yang tidak boleh tampil.',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Berita Kategori Aktif')
            ->assertDontSee('Berita Kategori Nonaktif');
    }

    public function test_homepage_section_configuration_controls_content_visibility_and_limits(): void
    {
        FeaturedProgram::create([
            'title' => 'Program Pertama',
            'slug' => 'program-pertama',
            'short_description' => 'Program yang tampil di beranda.',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        FeaturedProgram::create([
            'title' => 'Program Kedua',
            'slug' => 'program-kedua',
            'short_description' => 'Program yang dibatasi oleh pengaturan section.',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        HomepageSection::create([
            'key' => 'programs',
            'title_override' => 'Program Pilihan Madrasah',
            'subtitle_override' => 'Keunggulan Terarah',
            'item_limit' => 1,
            'is_active' => true,
        ]);

        HomepageSection::create([
            'key' => 'news',
            'is_active' => false,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Program Pilihan Madrasah')
            ->assertSee('Keunggulan Terarah')
            ->assertSee('Program Pertama')
            ->assertDontSee('Program Kedua')
            ->assertDontSee('Berita dan kegiatan terbaru.');
    }
}
