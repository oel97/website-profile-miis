<?php

namespace Tests\Feature;

use App\Models\PpdbPeriod;
use App\Models\PpdbRequirement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPpdbRequirementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_and_filter_requirements_by_period(): void
    {
        $admin = $this->admin();
        $firstPeriod = $this->period('PPDB 2026', 'ppdb-2026');
        $secondPeriod = $this->period('PPDB 2027', 'ppdb-2027');

        PpdbRequirement::create([
            'ppdb_period_id' => $firstPeriod->id,
            'title' => 'Fotokopi kartu keluarga',
            'is_active' => true,
        ]);
        PpdbRequirement::create([
            'ppdb_period_id' => $secondPeriod->id,
            'title' => 'Fotokopi akta kelahiran',
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.ppdb-requirements.index', ['ppdb_period_id' => $firstPeriod->id]))
            ->assertOk()
            ->assertSee('Fotokopi kartu keluarga')
            ->assertDontSee('Fotokopi akta kelahiran');
    }

    public function test_admin_can_open_create_and_edit_ppdb_requirement_pages(): void
    {
        $admin = $this->admin();
        $period = $this->period();
        $requirement = PpdbRequirement::create([
            'ppdb_period_id' => $period->id,
            'title' => 'Kartu keluarga',
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.ppdb-requirements.create'))
            ->assertOk()
            ->assertSee('Tambah Persyaratan PPDB');

        $this->actingAs($admin)
            ->get(route('admin.ppdb-requirements.edit', $requirement))
            ->assertOk()
            ->assertSee('Edit Persyaratan PPDB')
            ->assertSee('Kartu keluarga');
    }

    public function test_admin_can_create_a_requirement_for_the_selected_period(): void
    {
        $period = $this->period();

        $this->actingAs($this->admin())
            ->post(route('admin.ppdb-requirements.store'), [
                'ppdb_period_id' => $period->id,
                'title' => 'Fotokopi kartu keluarga',
                'description' => 'Satu lembar fotokopi yang masih terbaca jelas.',
                'sort_order' => 1,
                'is_active' => true,
            ])
            ->assertRedirect(route('admin.ppdb-requirements.index', ['ppdb_period_id' => $period->id]));

        $requirement = PpdbRequirement::firstOrFail();

        $this->assertSame($period->id, $requirement->ppdb_period_id);
        $this->assertSame('Fotokopi kartu keluarga', $requirement->title);
        $this->assertTrue($requirement->is_active);
    }

    public function test_requirement_requires_a_period_title_and_active_status(): void
    {
        $this->from(route('admin.ppdb-requirements.create'))
            ->actingAs($this->admin())
            ->post(route('admin.ppdb-requirements.store'), [])
            ->assertRedirect(route('admin.ppdb-requirements.create'))
            ->assertSessionHasErrors(['ppdb_period_id', 'title', 'is_active']);
    }

    public function test_requirement_requires_an_existing_period(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.ppdb-requirements.store'), [
                'ppdb_period_id' => 999999,
                'title' => 'Dokumen uji',
                'is_active' => true,
            ])
            ->assertSessionHasErrors('ppdb_period_id');
    }

    public function test_admin_can_update_a_ppdb_requirement(): void
    {
        $firstPeriod = $this->period('PPDB Pertama', 'ppdb-pertama');
        $secondPeriod = $this->period('PPDB Kedua', 'ppdb-kedua');
        $requirement = PpdbRequirement::create([
            'ppdb_period_id' => $firstPeriod->id,
            'title' => 'Dokumen lama',
            'description' => 'Deskripsi lama.',
            'sort_order' => 0,
            'is_active' => true,
        ]);

        $this->actingAs($this->admin())
            ->put(route('admin.ppdb-requirements.update', $requirement), [
                'ppdb_period_id' => $secondPeriod->id,
                'title' => 'Dokumen diperbarui',
                'description' => 'Deskripsi diperbarui.',
                'sort_order' => 2,
                'is_active' => false,
            ])
            ->assertRedirect(route('admin.ppdb-requirements.index', ['ppdb_period_id' => $secondPeriod->id]));

        $requirement->refresh();

        $this->assertSame($secondPeriod->id, $requirement->ppdb_period_id);
        $this->assertSame('Dokumen diperbarui', $requirement->title);
        $this->assertFalse($requirement->is_active);
    }

    public function test_admin_can_delete_a_ppdb_requirement_without_deleting_its_period(): void
    {
        $period = $this->period();
        $requirement = PpdbRequirement::create([
            'ppdb_period_id' => $period->id,
            'title' => 'Dokumen untuk dihapus',
            'is_active' => true,
        ]);

        $this->actingAs($this->admin())
            ->delete(route('admin.ppdb-requirements.destroy', $requirement))
            ->assertRedirect(route('admin.ppdb-requirements.index', ['ppdb_period_id' => $period->id]));

        $this->assertDatabaseMissing('ppdb_requirements', ['id' => $requirement->id]);
        $this->assertDatabaseHas('ppdb_periods', ['id' => $period->id]);
    }

    public function test_non_admin_cannot_access_ppdb_requirement_management(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->get(route('admin.ppdb-requirements.index'))
            ->assertRedirect(route('admin.login'));
    }

    private function admin(): User
    {
        return User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    private function period(string $title = 'PPDB 2026/2027', string $slug = 'ppdb-2026-2027'): PpdbPeriod
    {
        return PpdbPeriod::create([
            'academic_year' => '2026/2027',
            'title' => $title,
            'slug' => $slug,
            'registration_start_at' => '2026-01-10 08:00:00',
            'registration_end_at' => '2026-06-10 16:00:00',
            'is_active' => true,
            'is_published' => true,
        ]);
    }
}
