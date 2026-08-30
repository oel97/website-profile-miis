<?php

namespace Tests\Feature;

use App\Models\HomepageSection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminHomepageSectionTest extends TestCase
{
    use RefreshDatabase;

    protected function actingAsAdmin(): User
    {
        $user = User::factory()->create([
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        $this->actingAs($user);

        return $user;
    }

    public function test_admin_can_open_the_homepage_section_index_page(): void
    {
        $this->actingAsAdmin();

        $this->get(route('admin.homepage-sections.index'))
            ->assertOk()
            ->assertSee('Homepage Section');
    }

    public function test_admin_can_create_a_homepage_section_configuration(): void
    {
        $this->actingAsAdmin();

        $this->post(route('admin.homepage-sections.store'), [
            'key' => 'Programs',
            'title_override' => 'Program Pilihan Kami',
            'subtitle_override' => 'Keunggulan Madrasah',
            'sort_order' => 2,
            'item_limit' => 2,
            'settings' => '{"highlight":true}',
            'is_active' => true,
        ])->assertRedirect(route('admin.homepage-sections.index'));

        $section = HomepageSection::firstOrFail();

        $this->assertSame('programs', $section->key);
        $this->assertSame('Program Pilihan Kami', $section->title_override);
        $this->assertSame(['highlight' => true], $section->settings);
    }

    public function test_homepage_section_requires_a_unique_supported_key_and_valid_json(): void
    {
        $this->actingAsAdmin();
        HomepageSection::create(['key' => 'news', 'is_active' => true]);

        $this->from(route('admin.homepage-sections.create'))
            ->post(route('admin.homepage-sections.store'), [
                'key' => 'section-baru',
                'settings' => '{invalid json}',
                'is_active' => true,
            ])
            ->assertRedirect(route('admin.homepage-sections.create'))
            ->assertSessionHasErrors(['key', 'settings']);

        $this->from(route('admin.homepage-sections.create'))
            ->post(route('admin.homepage-sections.store'), [
                'key' => 'news',
                'is_active' => true,
            ])
            ->assertRedirect(route('admin.homepage-sections.create'))
            ->assertSessionHasErrors('key');
    }

    public function test_admin_can_update_and_delete_a_homepage_section_configuration(): void
    {
        $this->actingAsAdmin();
        $section = HomepageSection::create([
            'key' => 'achievements',
            'title_override' => 'Prestasi Awal',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->put(route('admin.homepage-sections.update', $section), [
            'key' => 'achievements',
            'title_override' => 'Prestasi Terbaru',
            'subtitle_override' => 'Pencapaian siswa dan madrasah',
            'sort_order' => 4,
            'item_limit' => 1,
            'settings' => '{"featured":true}',
            'is_active' => false,
        ])->assertRedirect(route('admin.homepage-sections.index'));

        $section->refresh();
        $this->assertSame('Prestasi Terbaru', $section->title_override);
        $this->assertFalse($section->is_active);
        $this->assertSame(1, $section->item_limit);

        $this->delete(route('admin.homepage-sections.destroy', $section))
            ->assertRedirect(route('admin.homepage-sections.index'));

        $this->assertDatabaseMissing('homepage_sections', ['id' => $section->id]);
    }

    public function test_non_admin_cannot_access_homepage_section_management(): void
    {
        $user = User::factory()->create(['role' => 'editor', 'is_active' => true]);

        $this->actingAs($user)
            ->get(route('admin.homepage-sections.index'))
            ->assertRedirect(route('admin.login'));
    }
}
