<?php

namespace Tests\Feature;

use App\Models\Agenda;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendAgendaTest extends TestCase
{
    use RefreshDatabase;

    public function test_agenda_page_is_accessible_without_cms_content(): void
    {
        $this->get(route('agenda'))
            ->assertOk()
            ->assertSee('Agenda Sekolah')
            ->assertSee('Jadwal Kegiatan');
    }

    public function test_agenda_page_displays_only_published_agendas_in_date_order(): void
    {
        Agenda::create([
            'title' => 'Agenda Mendatang',
            'slug' => 'agenda-mendatang',
            'start_at' => now()->addMonth(),
            'is_published' => true,
        ]);

        Agenda::create([
            'title' => 'Agenda Terdekat',
            'slug' => 'agenda-terdekat',
            'start_at' => now()->addWeek(),
            'is_published' => true,
        ]);

        Agenda::create([
            'title' => 'Agenda Belum Terbit',
            'slug' => 'agenda-belum-terbit',
            'start_at' => now()->addDays(2),
            'is_published' => false,
        ]);

        $response = $this->get(route('agenda'))
            ->assertOk()
            ->assertSee('Agenda Terdekat')
            ->assertSee('Agenda Mendatang')
            ->assertDontSee('Agenda Belum Terbit');

        $this->assertLessThan(
            strpos($response->getContent(), 'Agenda Mendatang'),
            strpos($response->getContent(), 'Agenda Terdekat'),
        );
    }

    public function test_published_agenda_can_be_opened_on_its_detail_page(): void
    {
        $agenda = Agenda::create([
            'title' => 'Pesantren Ramadan',
            'slug' => 'pesantren-ramadan',
            'description' => 'Kegiatan penguatan ibadah dan karakter selama Ramadan.',
            'location' => 'Aula MIIS',
            'start_at' => '2026-03-05 08:00:00',
            'end_at' => '2026-03-05 12:00:00',
            'is_published' => true,
        ]);

        $unpublishedAgenda = Agenda::create([
            'title' => 'Agenda Internal',
            'slug' => 'agenda-internal',
            'start_at' => now(),
            'is_published' => false,
        ]);

        $this->get(route('agenda'))
            ->assertOk()
            ->assertSee(route('agenda.show', $agenda));

        $this->get(route('agenda.show', $agenda))
            ->assertOk()
            ->assertSee('Pesantren Ramadan')
            ->assertSee('Aula MIIS')
            ->assertSee('Kegiatan penguatan ibadah dan karakter selama Ramadan.');

        $this->get(route('agenda.show', $unpublishedAgenda))
            ->assertNotFound();
    }
}
