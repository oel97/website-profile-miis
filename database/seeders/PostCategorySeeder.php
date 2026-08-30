<?php

namespace Database\Seeders;

use App\Models\PostCategory;
use Illuminate\Database\Seeder;

class PostCategorySeeder extends Seeder
{
    /**
     * Seed the default categories used by school news and activities.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Berita Madrasah',
                'slug' => 'berita-madrasah',
                'description' => 'Kabar dan informasi terbaru dari madrasah.',
            ],
            [
                'name' => 'Kegiatan Sekolah',
                'slug' => 'kegiatan-sekolah',
                'description' => 'Dokumentasi kegiatan pembelajaran dan sekolah.',
            ],
            [
                'name' => 'Pengumuman',
                'slug' => 'pengumuman',
                'description' => 'Pengumuman resmi untuk siswa, orang tua, dan masyarakat.',
            ],
            [
                'name' => 'Prestasi',
                'slug' => 'prestasi',
                'description' => 'Informasi capaian dan prestasi madrasah.',
            ],
        ];

        foreach ($categories as $category) {
            PostCategory::updateOrCreate(
                ['slug' => $category['slug']],
                [...$category, 'is_active' => true],
            );
        }
    }
}
