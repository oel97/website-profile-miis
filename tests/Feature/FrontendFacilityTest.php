<?php

namespace Tests\Feature;

use App\Models\Facility;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendFacilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_facility_page_is_accessible_without_cms_content(): void
    {
        $this->get(route('facility'))
            ->assertOk()
            ->assertSee('Sarana &amp; Prasarana', false)
            ->assertSee('Ruang yang Mendukung Pembelajaran');
    }

    public function test_facility_page_displays_only_published_facilities_in_sort_order(): void
    {
        Facility::create([
            'name' => 'Fasilitas Kedua',
            'slug' => 'fasilitas-kedua',
            'sort_order' => 2,
            'is_published' => true,
        ]);

        Facility::create([
            'name' => 'Fasilitas Pertama',
            'slug' => 'fasilitas-pertama',
            'description' => 'Ruang belajar yang nyaman.',
            'quantity' => 12,
            'unit' => 'ruang',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        Facility::create([
            'name' => 'Fasilitas Belum Terbit',
            'slug' => 'fasilitas-belum-terbit',
            'is_published' => false,
        ]);

        $response = $this->get(route('facility'))
            ->assertOk()
            ->assertSee('Fasilitas Pertama')
            ->assertSee('Ruang belajar yang nyaman.')
            ->assertSee('12 ruang')
            ->assertSee('Fasilitas Kedua')
            ->assertDontSee('Fasilitas Belum Terbit');

        $this->assertLessThan(
            strpos($response->getContent(), 'Fasilitas Kedua'),
            strpos($response->getContent(), 'Fasilitas Pertama'),
        );
    }

    public function test_published_facility_can_be_opened_on_its_detail_page(): void
    {
        $facility = Facility::create([
            'name' => 'Perpustakaan Madrasah',
            'slug' => 'perpustakaan-madrasah',
            'description' => 'Ruang literasi untuk membaca dan belajar bersama.',
            'quantity' => 1,
            'unit' => 'ruang',
            'is_published' => true,
        ]);

        $unpublishedFacility = Facility::create([
            'name' => 'Fasilitas Internal',
            'slug' => 'fasilitas-internal',
            'is_published' => false,
        ]);

        $this->get(route('facility'))
            ->assertOk()
            ->assertSee(route('facility.show', $facility));

        $this->get(route('facility.show', $facility))
            ->assertOk()
            ->assertSee('Perpustakaan Madrasah')
            ->assertSee('Ruang literasi untuk membaca dan belajar bersama.')
            ->assertSee('1 ruang');

        $this->get(route('facility.show', $unpublishedFacility))
            ->assertNotFound();
    }
}
