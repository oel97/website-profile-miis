<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_the_contact_index_page(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.contacts.index'))
            ->assertOk()
            ->assertSee('Kontak Sekolah');
    }

    public function test_admin_can_open_create_and_edit_contact_pages(): void
    {
        $admin = $this->admin();
        $contact = $this->contact();

        $this->actingAs($admin)
            ->get(route('admin.contacts.create'))
            ->assertOk()
            ->assertSee('Tambah Kontak Sekolah');

        $this->actingAs($admin)
            ->get(route('admin.contacts.edit', $contact))
            ->assertOk()
            ->assertSee('Edit Kontak Sekolah')
            ->assertSee($contact->label);
    }

    public function test_admin_can_create_an_email_contact(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.contacts.store'), [
                'label' => 'Email Sekolah',
                'type' => 'email',
                'value' => 'info@miis.test',
                'sort_order' => 1,
                'is_active' => true,
            ])
            ->assertRedirect(route('admin.contacts.index'));

        $this->assertDatabaseHas('contacts', [
            'label' => 'Email Sekolah',
            'type' => 'email',
            'value' => 'info@miis.test',
            'sort_order' => 1,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_create_a_whatsapp_contact_without_an_explicit_url(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.contacts.store'), [
                'label' => 'WhatsApp PPDB',
                'type' => 'whatsapp',
                'value' => '+62 852-3655-1241',
                'url' => '+62 852-3655-1241',
                'sort_order' => 1,
                'is_active' => true,
            ])
            ->assertRedirect(route('admin.contacts.index'));

        $this->assertDatabaseHas('contacts', [
            'label' => 'WhatsApp PPDB',
            'value' => '+62 852-3655-1241',
            'url' => null,
        ]);
    }

    public function test_admin_can_create_a_google_maps_contact_with_a_url(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.contacts.store'), [
                'label' => 'Lokasi Madrasah',
                'type' => 'maps',
                'value' => 'Alamat MI Islamiyah Syafi\'iyah',
                'url' => 'https://maps.google.com/?q=-6.2,106.8',
                'sort_order' => 2,
                'is_active' => true,
            ])
            ->assertRedirect(route('admin.contacts.index'));

        $this->assertDatabaseHas('contacts', [
            'label' => 'Lokasi Madrasah',
            'type' => 'maps',
            'url' => 'https://maps.google.com/?q=-6.2,106.8',
        ]);
    }

    public function test_contact_requires_core_fields_and_validates_email_and_url(): void
    {
        $this->from(route('admin.contacts.create'))
            ->actingAs($this->admin())
            ->post(route('admin.contacts.store'), [])
            ->assertRedirect(route('admin.contacts.create'))
            ->assertSessionHasErrors(['label', 'type', 'value', 'is_active']);

        $this->actingAs($this->admin())
            ->post(route('admin.contacts.store'), [
                'label' => 'Kontak tidak valid',
                'type' => 'email',
                'value' => 'bukan-email',
                'url' => 'bukan-url',
                'is_active' => true,
            ])
            ->assertSessionHasErrors(['value', 'url']);
    }

    public function test_website_and_maps_contacts_require_a_url(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.contacts.store'), [
                'label' => 'Website Sekolah',
                'type' => 'website',
                'value' => 'Website resmi MIIS',
                'is_active' => true,
            ])
            ->assertSessionHasErrors('url');
    }

    public function test_admin_can_update_a_contact(): void
    {
        $contact = $this->contact();

        $this->actingAs($this->admin())
            ->put(route('admin.contacts.update', $contact), [
                'label' => 'WhatsApp PPDB',
                'type' => 'whatsapp',
                'value' => '081234567890',
                'url' => 'https://wa.me/6281234567890',
                'sort_order' => 3,
                'is_active' => false,
            ])
            ->assertRedirect(route('admin.contacts.index'));

        $contact->refresh();

        $this->assertSame('WhatsApp PPDB', $contact->label);
        $this->assertSame('whatsapp', $contact->type);
        $this->assertFalse($contact->is_active);
    }

    public function test_admin_can_delete_a_contact(): void
    {
        $contact = $this->contact();

        $this->actingAs($this->admin())
            ->delete(route('admin.contacts.destroy', $contact))
            ->assertRedirect(route('admin.contacts.index'));

        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
    }

    public function test_non_admin_cannot_access_contact_management(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->get(route('admin.contacts.index'))
            ->assertRedirect(route('admin.login'));
    }

    private function admin(): User
    {
        return User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    private function contact(): Contact
    {
        return Contact::create([
            'label' => 'Telepon Sekolah',
            'type' => 'phone',
            'value' => '021-1234567',
            'sort_order' => 0,
            'is_active' => true,
        ]);
    }
}
