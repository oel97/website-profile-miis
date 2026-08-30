<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\SchoolProfile;
use App\Models\SocialLink;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_page_is_accessible_without_cms_content(): void
    {
        $this->get(route('contact'))
            ->assertOk()
            ->assertSee('Kontak MIIS')
            ->assertSee('Informasi Kontak');
    }

    public function test_contact_page_displays_active_contacts_social_links_and_school_map_in_sort_order(): void
    {
        SchoolProfile::create([
            'name' => "MI Islamiyah Syafi'iyah",
            'address' => 'Jl. Madrasah Nomor 1',
            'map_embed_url' => 'https://www.google.com/maps/embed?pb=miis',
        ]);

        Contact::create([
            'label' => 'Telepon',
            'type' => 'phone',
            'value' => '0812 1111 2222',
            'sort_order' => 2,
            'is_active' => true,
        ]);
        Contact::create([
            'label' => 'WhatsApp',
            'type' => 'whatsapp',
            'value' => '0812 3333 4444',
            'sort_order' => 1,
            'is_active' => true,
        ]);
        Contact::create([
            'label' => 'Kontak Nonaktif',
            'type' => 'email',
            'value' => 'hidden@example.test',
            'is_active' => false,
        ]);

        SocialLink::create([
            'platform' => 'instagram',
            'label' => 'Instagram MIIS',
            'url' => 'https://instagram.com/miis',
            'sort_order' => 1,
            'is_active' => true,
        ]);
        SocialLink::create([
            'platform' => 'facebook',
            'label' => 'Facebook Nonaktif',
            'url' => 'https://facebook.com/miis',
            'is_active' => false,
        ]);

        $response = $this->get(route('contact'))
            ->assertOk()
            ->assertSee('WhatsApp')
            ->assertSee('0812 3333 4444')
            ->assertSee('https://wa.me/6281233334444', false)
            ->assertSee('Telepon')
            ->assertSee('0812 1111 2222')
            ->assertSee('tel:081211112222', false)
            ->assertSee('www.google.com/maps/embed?pb=miis')
            ->assertSee('Instagram MIIS')
            ->assertDontSee('Kontak Nonaktif')
            ->assertDontSee('Facebook Nonaktif');

        $this->assertLessThan(
            strpos($response->getContent(), 'Telepon'),
            strpos($response->getContent(), 'WhatsApp'),
        );
    }
}
