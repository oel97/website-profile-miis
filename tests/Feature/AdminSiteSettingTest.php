<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSiteSettingTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_the_site_setting_index_page(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.site-settings.index'))
            ->assertOk()
            ->assertSee('Pengaturan Website');
    }

    public function test_admin_can_open_create_and_edit_site_setting_pages(): void
    {
        $admin = $this->admin();
        $siteSetting = $this->siteSetting();

        $this->actingAs($admin)
            ->get(route('admin.site-settings.create'))
            ->assertOk()
            ->assertSee('Tambah Website Setting');

        $this->actingAs($admin)
            ->get(route('admin.site-settings.edit', $siteSetting))
            ->assertOk()
            ->assertSee('Edit Website Setting')
            ->assertSee($siteSetting->key);
    }

    public function test_admin_can_create_a_site_setting(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.site-settings.store'), [
                'group' => 'general',
                'key' => 'site_name',
                'value' => "MI Islamiyah Syafi'iyah",
                'type' => 'text',
                'is_public' => true,
            ])
            ->assertRedirect(route('admin.site-settings.index'));

        $this->assertDatabaseHas('site_settings', [
            'group' => 'general',
            'key' => 'site_name',
            'value' => "MI Islamiyah Syafi'iyah",
            'type' => 'text',
            'is_public' => true,
        ]);
    }

    public function test_site_setting_requires_core_fields(): void
    {
        $this->from(route('admin.site-settings.create'))
            ->actingAs($this->admin())
            ->post(route('admin.site-settings.store'), [])
            ->assertRedirect(route('admin.site-settings.create'))
            ->assertSessionHasErrors(['group', 'key', 'value', 'type', 'is_public']);
    }

    public function test_site_setting_validates_unique_keys_and_typed_values(): void
    {
        $this->siteSetting();

        $this->actingAs($this->admin())
            ->post(route('admin.site-settings.store'), [
                'group' => 'general',
                'key' => 'school_website',
                'value' => 'bukan-url',
                'type' => 'url',
                'is_public' => true,
            ])
            ->assertSessionHasErrors('value');

        $this->actingAs($this->admin())
            ->post(route('admin.site-settings.store'), [
                'group' => 'general',
                'key' => 'site_name',
                'value' => 'Nama lain',
                'type' => 'text',
                'is_public' => true,
            ])
            ->assertSessionHasErrors('key');
    }

    public function test_site_setting_validates_json_values(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.site-settings.store'), [
                'group' => 'homepage',
                'key' => 'featured_items',
                'value' => 'bukan-json',
                'type' => 'json',
                'is_public' => true,
            ])
            ->assertSessionHasErrors('value');
    }

    public function test_admin_can_update_a_site_setting(): void
    {
        $siteSetting = $this->siteSetting();

        $this->actingAs($this->admin())
            ->put(route('admin.site-settings.update', $siteSetting), [
                'group' => 'seo',
                'key' => 'meta_description',
                'value' => "Website resmi MI Islamiyah Syafi'iyah",
                'type' => 'textarea',
                'is_public' => true,
            ])
            ->assertRedirect(route('admin.site-settings.index'));

        $siteSetting->refresh();

        $this->assertSame('seo', $siteSetting->group);
        $this->assertSame('meta_description', $siteSetting->key);
        $this->assertTrue($siteSetting->is_public);
    }

    public function test_admin_can_delete_a_site_setting(): void
    {
        $siteSetting = $this->siteSetting();

        $this->actingAs($this->admin())
            ->delete(route('admin.site-settings.destroy', $siteSetting))
            ->assertRedirect(route('admin.site-settings.index'));

        $this->assertDatabaseMissing('site_settings', ['id' => $siteSetting->id]);
    }

    public function test_non_admin_cannot_access_site_setting_management(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->get(route('admin.site-settings.index'))
            ->assertRedirect(route('admin.login'));
    }

    private function admin(): User
    {
        return User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    private function siteSetting(): SiteSetting
    {
        return SiteSetting::create([
            'group' => 'general',
            'key' => 'site_name',
            'value' => "MI Islamiyah Syafi'iyah",
            'type' => 'text',
            'is_public' => true,
        ]);
    }
}
