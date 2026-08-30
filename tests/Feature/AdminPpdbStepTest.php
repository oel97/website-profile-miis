<?php

namespace Tests\Feature;

use App\Models\PpdbPeriod;
use App\Models\PpdbStep;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPpdbStepTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_and_filter_ppdb_steps_by_period(): void
    {
        $admin = $this->admin();
        $firstPeriod = $this->period('PPDB 2026', 'ppdb-2026');
        $secondPeriod = $this->period('PPDB 2027', 'ppdb-2027');

        PpdbStep::create([
            'ppdb_period_id' => $firstPeriod->id,
            'title' => 'Isi formulir pendaftaran',
            'sort_order' => 1,
        ]);
        PpdbStep::create([
            'ppdb_period_id' => $secondPeriod->id,
            'title' => 'Verifikasi berkas',
            'sort_order' => 1,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.ppdb-steps.index', ['ppdb_period_id' => $firstPeriod->id]))
            ->assertOk()
            ->assertSee('Isi formulir pendaftaran')
            ->assertDontSee('Verifikasi berkas');
    }

    public function test_admin_can_open_create_and_edit_ppdb_step_pages(): void
    {
        $admin = $this->admin();
        $period = $this->period();
        $step = PpdbStep::create([
            'ppdb_period_id' => $period->id,
            'title' => 'Lengkapi formulir',
            'sort_order' => 1,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.ppdb-steps.create'))
            ->assertOk()
            ->assertSee('Tambah Langkah Pendaftaran');

        $this->actingAs($admin)
            ->get(route('admin.ppdb-steps.edit', $step))
            ->assertOk()
            ->assertSee('Edit Langkah Pendaftaran')
            ->assertSee('Lengkapi formulir');
    }

    public function test_admin_can_create_a_step_for_the_selected_period_with_its_sort_order(): void
    {
        $period = $this->period();

        $this->actingAs($this->admin())
            ->post(route('admin.ppdb-steps.store'), [
                'ppdb_period_id' => $period->id,
                'title' => 'Unggah dokumen persyaratan',
                'description' => 'Unggah berkas sesuai daftar persyaratan.',
                'sort_order' => 2,
            ])
            ->assertRedirect(route('admin.ppdb-steps.index', ['ppdb_period_id' => $period->id]));

        $step = PpdbStep::firstOrFail();

        $this->assertSame($period->id, $step->ppdb_period_id);
        $this->assertSame('Unggah dokumen persyaratan', $step->title);
        $this->assertSame(2, $step->sort_order);
    }

    public function test_step_requires_a_period_and_title(): void
    {
        $this->from(route('admin.ppdb-steps.create'))
            ->actingAs($this->admin())
            ->post(route('admin.ppdb-steps.store'), [])
            ->assertRedirect(route('admin.ppdb-steps.create'))
            ->assertSessionHasErrors(['ppdb_period_id', 'title']);
    }

    public function test_step_requires_an_existing_period(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.ppdb-steps.store'), [
                'ppdb_period_id' => 999999,
                'title' => 'Langkah uji',
                'sort_order' => 1,
            ])
            ->assertSessionHasErrors('ppdb_period_id');
    }

    public function test_admin_can_update_a_ppdb_step_and_change_its_period_and_order(): void
    {
        $firstPeriod = $this->period('PPDB Pertama', 'ppdb-pertama');
        $secondPeriod = $this->period('PPDB Kedua', 'ppdb-kedua');
        $step = PpdbStep::create([
            'ppdb_period_id' => $firstPeriod->id,
            'title' => 'Langkah lama',
            'description' => 'Deskripsi lama.',
            'sort_order' => 1,
        ]);

        $this->actingAs($this->admin())
            ->put(route('admin.ppdb-steps.update', $step), [
                'ppdb_period_id' => $secondPeriod->id,
                'title' => 'Langkah diperbarui',
                'description' => 'Deskripsi diperbarui.',
                'sort_order' => 3,
            ])
            ->assertRedirect(route('admin.ppdb-steps.index', ['ppdb_period_id' => $secondPeriod->id]));

        $step->refresh();

        $this->assertSame($secondPeriod->id, $step->ppdb_period_id);
        $this->assertSame('Langkah diperbarui', $step->title);
        $this->assertSame(3, $step->sort_order);
    }

    public function test_admin_can_delete_a_ppdb_step_without_deleting_its_period(): void
    {
        $period = $this->period();
        $step = PpdbStep::create([
            'ppdb_period_id' => $period->id,
            'title' => 'Langkah untuk dihapus',
            'sort_order' => 1,
        ]);

        $this->actingAs($this->admin())
            ->delete(route('admin.ppdb-steps.destroy', $step))
            ->assertRedirect(route('admin.ppdb-steps.index', ['ppdb_period_id' => $period->id]));

        $this->assertDatabaseMissing('ppdb_steps', ['id' => $step->id]);
        $this->assertDatabaseHas('ppdb_periods', ['id' => $period->id]);
    }

    public function test_non_admin_cannot_access_ppdb_step_management(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->get(route('admin.ppdb-steps.index'))
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
