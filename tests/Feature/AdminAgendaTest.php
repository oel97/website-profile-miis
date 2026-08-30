<?php

namespace Tests\Feature;

use App\Models\Agenda;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminAgendaTest extends TestCase
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

    public function test_admin_can_open_the_agenda_index_page(): void
    {
        $this->actingAsAdmin();

        $this->get(route('admin.agendas.index'))
            ->assertOk()
            ->assertSee('Agenda Sekolah');
    }

    public function test_admin_can_open_the_create_and_edit_agenda_pages(): void
    {
        $this->actingAsAdmin();
        $agenda = Agenda::create([
            'title' => 'Rapat Persiapan Semester',
            'slug' => 'rapat-persiapan-semester',
            'description' => 'Agenda awal.',
            'start_at' => '2026-08-20 08:00:00',
            'is_published' => true,
        ]);

        $this->get(route('admin.agendas.create'))
            ->assertOk()
            ->assertSee('Tambah Agenda');

        $this->get(route('admin.agendas.edit', $agenda))
            ->assertOk()
            ->assertSee('Edit Agenda')
            ->assertSee($agenda->title);
    }

    public function test_admin_can_create_an_agenda_with_a_cover_image_and_generated_slug(): void
    {
        $this->actingAsAdmin();
        Storage::fake('public');

        $this->post(route('admin.agendas.store'), [
            'title' => 'Peringatan Maulid Nabi',
            'description' => 'Agenda peringatan Maulid Nabi Muhammad SAW di lingkungan sekolah.',
            'location' => 'Aula MI Islamiyah Syafi\'iyah',
            'start_at' => '2026-09-05 08:00:00',
            'end_at' => '2026-09-05 11:00:00',
            'registration_url' => 'https://profilemiis.test/pendaftaran-maulid',
            'is_published' => true,
            'image' => UploadedFile::fake()->image('maulid.png', 1200, 675),
        ])->assertRedirect(route('admin.agendas.index'));

        $this->assertDatabaseHas('agendas', [
            'title' => 'Peringatan Maulid Nabi',
            'slug' => 'peringatan-maulid-nabi',
            'location' => 'Aula MI Islamiyah Syafi\'iyah',
            'is_published' => true,
        ]);

        $agenda = Agenda::firstOrFail();
        Storage::disk('public')->assertExists($agenda->cover_image_path);
    }

    public function test_agenda_validation_requires_title_description_start_date_and_status(): void
    {
        $this->actingAsAdmin();

        $this->from(route('admin.agendas.create'))
            ->post(route('admin.agendas.store'))
            ->assertRedirect(route('admin.agendas.create'))
            ->assertSessionHasErrors(['title', 'description', 'start_at', 'is_published']);
    }

    public function test_agenda_end_date_must_not_be_before_its_start_date(): void
    {
        $this->actingAsAdmin();

        $this->from(route('admin.agendas.create'))
            ->post(route('admin.agendas.store'), [
                'title' => 'Agenda Uji Tanggal',
                'description' => 'Deskripsi agenda uji tanggal.',
                'start_at' => '2026-09-05 10:00:00',
                'end_at' => '2026-09-05 08:00:00',
                'is_published' => true,
            ])
            ->assertRedirect(route('admin.agendas.create'))
            ->assertSessionHasErrors('end_at');
    }

    public function test_admin_cannot_use_a_duplicate_agenda_slug(): void
    {
        $this->actingAsAdmin();

        Agenda::create([
            'title' => 'Agenda Pertama',
            'slug' => 'agenda-sama',
            'description' => 'Deskripsi agenda pertama.',
            'start_at' => '2026-09-01 08:00:00',
            'is_published' => true,
        ]);

        $this->from(route('admin.agendas.create'))
            ->post(route('admin.agendas.store'), [
                'title' => 'Agenda Kedua',
                'slug' => 'Agenda Sama',
                'description' => 'Deskripsi agenda kedua.',
                'start_at' => '2026-09-02 08:00:00',
                'is_published' => true,
            ])
            ->assertRedirect(route('admin.agendas.create'))
            ->assertSessionHasErrors('slug');
    }

    public function test_admin_can_update_an_agenda_and_replace_its_cover_image(): void
    {
        $this->actingAsAdmin();
        Storage::fake('public');

        $oldImage = UploadedFile::fake()->image('cover-lama.jpg')->store('agendas', 'public');
        $agenda = Agenda::create([
            'title' => 'Agenda Awal',
            'slug' => 'agenda-awal',
            'description' => 'Deskripsi agenda awal.',
            'cover_image_path' => $oldImage,
            'start_at' => '2026-09-10 08:00:00',
            'is_published' => true,
        ]);

        $this->put(route('admin.agendas.update', $agenda), [
            'title' => 'Agenda yang Diperbarui',
            'slug' => 'agenda-yang-diperbarui',
            'description' => 'Deskripsi agenda yang diperbarui.',
            'location' => 'Aula Utama',
            'start_at' => '2026-09-12 08:00:00',
            'end_at' => '2026-09-12 11:00:00',
            'registration_url' => 'https://profilemiis.test/agenda',
            'is_published' => false,
            'image' => UploadedFile::fake()->image('cover-baru.webp', 1200, 675),
        ])->assertRedirect(route('admin.agendas.index'));

        $agenda->refresh();

        $this->assertSame('Agenda yang Diperbarui', $agenda->title);
        $this->assertSame('agenda-yang-diperbarui', $agenda->slug);
        $this->assertFalse($agenda->is_published);
        $this->assertNotSame($oldImage, $agenda->cover_image_path);
        Storage::disk('public')->assertMissing($oldImage);
        Storage::disk('public')->assertExists($agenda->cover_image_path);
    }

    public function test_admin_can_delete_an_agenda_and_its_cover_image(): void
    {
        $this->actingAsAdmin();
        Storage::fake('public');

        $image = UploadedFile::fake()->image('cover-agenda.jpg')->store('agendas', 'public');
        $agenda = Agenda::create([
            'title' => 'Agenda yang Dihapus',
            'slug' => 'agenda-yang-dihapus',
            'description' => 'Deskripsi agenda yang dihapus.',
            'cover_image_path' => $image,
            'start_at' => '2026-09-15 08:00:00',
            'is_published' => true,
        ]);

        $this->delete(route('admin.agendas.destroy', $agenda))
            ->assertRedirect(route('admin.agendas.index'));

        $this->assertDatabaseMissing('agendas', ['id' => $agenda->id]);
        Storage::disk('public')->assertMissing($image);
    }
}
