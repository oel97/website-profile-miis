<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AgendaRequest;
use App\Models\Agenda;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AgendaController extends Controller
{
    /**
     * Display the agendas.
     */
    public function index(): View
    {
        $agendas = Agenda::query()
            ->orderBy('start_at')
            ->paginate(10);

        return view('admin.agendas.index', compact('agendas'));
    }

    /**
     * Show the form for creating an agenda.
     */
    public function create(): View
    {
        return view('admin.agendas.create');
    }

    /**
     * Store a newly created agenda.
     */
    public function store(AgendaRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->generateUniqueSlug($data['slug'] ?? $data['title']);

        if ($request->hasFile('image')) {
            $data['cover_image_path'] = $request->file('image')->store('agendas', 'public');
        }

        unset($data['image']);

        Agenda::create($data);

        return redirect()
            ->route('admin.agendas.index')
            ->with('success', 'Agenda sekolah berhasil ditambahkan.');
    }

    /**
     * Show the form for editing an agenda.
     */
    public function edit(Agenda $agenda): View
    {
        return view('admin.agendas.edit', compact('agenda'));
    }

    /**
     * Update the specified agenda.
     */
    public function update(AgendaRequest $request, Agenda $agenda): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->generateUniqueSlug($data['slug'] ?? $data['title'], $agenda);

        if ($request->hasFile('image')) {
            if ($agenda->cover_image_path) {
                Storage::disk('public')->delete($agenda->cover_image_path);
            }

            $data['cover_image_path'] = $request->file('image')->store('agendas', 'public');
        }

        unset($data['image']);

        $agenda->update($data);

        return redirect()
            ->route('admin.agendas.index')
            ->with('success', 'Agenda sekolah berhasil diperbarui.');
    }

    /**
     * Remove the specified agenda and its cover image.
     */
    public function destroy(Agenda $agenda): RedirectResponse
    {
        if ($agenda->cover_image_path) {
            Storage::disk('public')->delete($agenda->cover_image_path);
        }

        $agenda->delete();

        return redirect()
            ->route('admin.agendas.index')
            ->with('success', 'Agenda sekolah berhasil dihapus.');
    }

    /**
     * Generate a unique slug for an agenda.
     */
    protected function generateUniqueSlug(string $value, ?Agenda $ignoredAgenda = null): string
    {
        $baseSlug = Str::slug($value) ?: 'agenda-sekolah';
        $slug = $baseSlug;
        $suffix = 2;

        while (
            Agenda::query()
                ->where('slug', $slug)
                ->when($ignoredAgenda, fn ($query) => $query->whereKeyNot($ignoredAgenda->getKey()))
                ->exists()
        ) {
            $slug = $baseSlug.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
}
