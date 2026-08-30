<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FeaturedProgramRequest;
use App\Models\FeaturedProgram;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class FeaturedProgramController extends Controller
{
    /**
     * Display the featured program directory.
     */
    public function index(): View
    {
        $programs = FeaturedProgram::query()
            ->orderBy('sort_order')
            ->orderBy('title')
            ->paginate(10);

        return view('admin.programs.index', compact('programs'));
    }

    /**
     * Show the form for creating a featured program.
     */
    public function create(): View
    {
        return view('admin.programs.create');
    }

    /**
     * Store a newly created featured program.
     */
    public function store(FeaturedProgramRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->generateUniqueSlug($data['slug'] ?? $data['title']);

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('programs', 'public');
        }

        unset($data['image']);

        FeaturedProgram::create($data);

        return redirect()
            ->route('admin.programs.index')
            ->with('success', 'Program unggulan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing a featured program.
     */
    public function edit(FeaturedProgram $program): View
    {
        return view('admin.programs.edit', compact('program'));
    }

    /**
     * Update the specified featured program.
     */
    public function update(FeaturedProgramRequest $request, FeaturedProgram $program): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->generateUniqueSlug($data['slug'] ?? $data['title'], $program);

        if ($request->hasFile('image')) {
            if ($program->image_path) {
                Storage::disk('public')->delete($program->image_path);
            }

            $data['image_path'] = $request->file('image')->store('programs', 'public');
        }

        unset($data['image']);

        $program->update($data);

        return redirect()
            ->route('admin.programs.index')
            ->with('success', 'Program unggulan berhasil diperbarui.');
    }

    /**
     * Soft delete the specified featured program.
     */
    public function destroy(FeaturedProgram $program): RedirectResponse
    {
        $program->delete();

        return redirect()
            ->route('admin.programs.index')
            ->with('success', 'Program unggulan berhasil dihapus.');
    }

    /**
     * Generate a unique slug, including against soft-deleted records.
     */
    protected function generateUniqueSlug(string $value, ?FeaturedProgram $ignoredProgram = null): string
    {
        $baseSlug = Str::slug($value) ?: 'program-unggulan';
        $slug = $baseSlug;
        $suffix = 2;

        while (
            FeaturedProgram::withTrashed()
                ->where('slug', $slug)
                ->when($ignoredProgram, fn ($query) => $query->whereKeyNot($ignoredProgram->getKey()))
                ->exists()
        ) {
            $slug = $baseSlug.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
}
