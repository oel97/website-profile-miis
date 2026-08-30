<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FacilityRequest;
use App\Models\Facility;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class FacilityController extends Controller
{
    public function index(): View
    {
        $facilities = Facility::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(10);

        return view('admin.facilities.index', compact('facilities'));
    }

    public function create(): View
    {
        return view('admin.facilities.create');
    }

    public function store(FacilityRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->generateUniqueSlug($data['slug'] ?? $data['name']);

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('facilities', 'public');
        }

        unset($data['image']);

        Facility::create($data);

        return redirect()
            ->route('admin.facilities.index')
            ->with('success', 'Data sarana dan prasarana berhasil ditambahkan.');
    }

    public function edit(Facility $facility): View
    {
        return view('admin.facilities.edit', compact('facility'));
    }

    public function update(FacilityRequest $request, Facility $facility): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->generateUniqueSlug($data['slug'] ?? $data['name'], $facility);

        if ($request->hasFile('image')) {
            if ($facility->image_path) {
                Storage::disk('public')->delete($facility->image_path);
            }

            $data['image_path'] = $request->file('image')->store('facilities', 'public');
        }

        unset($data['image']);

        $facility->update($data);

        return redirect()
            ->route('admin.facilities.index')
            ->with('success', 'Data sarana dan prasarana berhasil diperbarui.');
    }

    public function destroy(Facility $facility): RedirectResponse
    {
        $facility->delete();

        return redirect()
            ->route('admin.facilities.index')
            ->with('success', 'Data sarana dan prasarana berhasil dihapus.');
    }

    private function generateUniqueSlug(string $value, ?Facility $ignoredFacility = null): string
    {
        $baseSlug = Str::slug($value) ?: 'fasilitas-sekolah';
        $slug = $baseSlug;
        $counter = 2;

        while (Facility::withTrashed()
            ->where('slug', $slug)
            ->when($ignoredFacility, fn ($query) => $query->whereKeyNot($ignoredFacility->getKey()))
            ->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
