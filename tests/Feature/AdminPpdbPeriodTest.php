<?php

namespace Tests\Feature;

use App\Models\PpdbPeriod;
use App\Models\PpdbRequirement;
use App\Models\PpdbStep;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminPpdbPeriodTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_the_ppdb_period_index_page(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.ppdb-periods.index'))
            ->assertOk()
            ->assertSee('Periode PPDB');
    }

    public function test_admin_can_open_create_and_edit_ppdb_period_pages(): void
    {
        $admin = $this->admin();
        $period = $this->period();

        $this->actingAs($admin)
            ->get(route('admin.ppdb-periods.create'))
            ->assertOk()
            ->assertSee('Tambah Periode PPDB');

        $this->actingAs($admin)
            ->get(route('admin.ppdb-periods.edit', $period))
            ->assertOk()
            ->assertSee('Edit Periode PPDB')
            ->assertSee($period->title);
    }

    public function test_admin_can_create_a_ppdb_period_with_banner_and_generated_slug(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin())
            ->post(route('admin.ppdb-periods.store'), [
                'academic_year' => '2026/2027',
                'title' => 'Penerimaan Peserta Didik Baru 2026',
                'description' => 'Pendaftaran peserta didik baru MI Islamiyah Syafi\'iyah.',
                'registration_start_at' => '2026-01-10 08:00:00',
                'registration_end_at' => '2026-06-20 16:00:00',
                'announcement_at' => '2026-06-25 09:00:00',
                'registration_url' => 'https://example.test/daftar-ppdb',
                'contact_whatsapp' => '081234567890',
                'is_active' => true,
                'is_published' => true,
                'banner' => UploadedFile::fake()->image('banner-ppdb.jpg'),
            ])
            ->assertRedirect(route('admin.ppdb-periods.index'));

        $period = PpdbPeriod::firstOrFail();

        $this->assertSame('penerimaan-peserta-didik-baru-2026', $period->slug);
        $this->assertTrue($period->is_active);
        $this->assertTrue($period->is_published);
        Storage::disk('public')->assertExists($period->banner_path);
    }

    public function test_ppdb_period_requires_core_fields_and_statuses(): void
    {
        $this->from(route('admin.ppdb-periods.create'))
            ->actingAs($this->admin())
            ->post(route('admin.ppdb-periods.store'), [])
            ->assertRedirect(route('admin.ppdb-periods.create'))
            ->assertSessionHasErrors([
                'academic_year',
                'title',
                'registration_start_at',
                'registration_end_at',
                'is_active',
                'is_published',
            ]);
    }

    public function test_ppdb_period_end_date_cannot_precede_start_date(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.ppdb-periods.store'), [
                'academic_year' => '2026/2027',
                'title' => 'PPDB Uji Tanggal',
                'registration_start_at' => '2026-06-20 08:00:00',
                'registration_end_at' => '2026-06-19 08:00:00',
                'is_active' => true,
                'is_published' => true,
            ])
            ->assertSessionHasErrors('registration_end_at');
    }

    public function test_ppdb_period_slug_must_be_unique(): void
    {
        $this->period('PPDB Pertama', 'ppdb-sama');

        $this->actingAs($this->admin())
            ->post(route('admin.ppdb-periods.store'), [
                'academic_year' => '2027/2028',
                'title' => 'PPDB Kedua',
                'slug' => 'ppdb-sama',
                'registration_start_at' => '2027-01-10 08:00:00',
                'registration_end_at' => '2027-06-10 16:00:00',
                'is_active' => false,
                'is_published' => true,
            ])
            ->assertSessionHasErrors('slug');
    }

    public function test_admin_can_update_a_ppdb_period_and_replace_its_banner(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('ppdb/banner-lama.jpg', 'banner lama');

        $period = PpdbPeriod::create([
            'academic_year' => '2025/2026',
            'title' => 'PPDB Lama',
            'slug' => 'ppdb-lama',
            'banner_path' => 'ppdb/banner-lama.jpg',
            'registration_start_at' => '2025-01-10 08:00:00',
            'registration_end_at' => '2025-06-10 16:00:00',
            'is_active' => true,
            'is_published' => true,
        ]);

        $this->actingAs($this->admin())
            ->put(route('admin.ppdb-periods.update', $period), [
                'academic_year' => '2026/2027',
                'title' => 'PPDB Diperbarui',
                'slug' => 'ppdb-diperbarui',
                'description' => 'Informasi PPDB diperbarui.',
                'registration_start_at' => '2026-01-10 08:00:00',
                'registration_end_at' => '2026-06-20 16:00:00',
                'announcement_at' => '2026-06-25 09:00:00',
                'registration_url' => 'https://example.test/ppdb-2026',
                'contact_whatsapp' => '081234567890',
                'is_active' => false,
                'is_published' => false,
                'banner' => UploadedFile::fake()->image('banner-baru.png'),
            ])
            ->assertRedirect(route('admin.ppdb-periods.index'));

        $period->refresh();

        $this->assertSame('PPDB Diperbarui', $period->title);
        $this->assertSame('ppdb-diperbarui', $period->slug);
        $this->assertFalse($period->is_active);
        $this->assertFalse($period->is_published);
        Storage::disk('public')->assertMissing('ppdb/banner-lama.jpg');
        Storage::disk('public')->assertExists($period->banner_path);
    }

    public function test_deleting_a_ppdb_period_removes_its_banner_and_cascades_children(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('ppdb/banner-dihapus.jpg', 'banner dihapus');

        $period = PpdbPeriod::create([
            'academic_year' => '2026/2027',
            'title' => 'PPDB Untuk Dihapus',
            'slug' => 'ppdb-untuk-dihapus',
            'banner_path' => 'ppdb/banner-dihapus.jpg',
            'registration_start_at' => '2026-01-10 08:00:00',
            'registration_end_at' => '2026-06-10 16:00:00',
            'is_active' => true,
            'is_published' => true,
        ]);
        $requirement = PpdbRequirement::create([
            'ppdb_period_id' => $period->id,
            'title' => 'Fotokopi kartu keluarga',
        ]);
        $step = PpdbStep::create([
            'ppdb_period_id' => $period->id,
            'title' => 'Isi formulir pendaftaran',
        ]);

        $this->actingAs($this->admin())
            ->delete(route('admin.ppdb-periods.destroy', $period))
            ->assertRedirect(route('admin.ppdb-periods.index'));

        $this->assertDatabaseMissing('ppdb_periods', ['id' => $period->id]);
        $this->assertDatabaseMissing('ppdb_requirements', ['id' => $requirement->id]);
        $this->assertDatabaseMissing('ppdb_steps', ['id' => $step->id]);
        Storage::disk('public')->assertMissing('ppdb/banner-dihapus.jpg');
    }

    public function test_non_admin_cannot_access_ppdb_period_management(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->get(route('admin.ppdb-periods.index'))
            ->assertRedirect(route('admin.login'));
    }

    private function admin(): User
    {
        return User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    private function period(string $title = 'PPDB Tahun Ajaran 2026/2027', string $slug = 'ppdb-2026-2027'): PpdbPeriod
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
