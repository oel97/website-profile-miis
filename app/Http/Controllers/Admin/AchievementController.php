<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AchievementRequest;
use App\Models\Achievement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AchievementController extends Controller
{
    /**
     * Display the achievements.
     */
    public function index(): View
    {
        $achievements = Achievement::query()
            ->orderBy('sort_order')
            ->orderByDesc('achievement_date')
            ->orderBy('title')
            ->paginate(10);

        return view('admin.achievements.index', compact('achievements'));
    }

    /**
     * Show the form for creating an achievement.
     */
    public function create(): View
    {
        return view('admin.achievements.create');
    }

    /**
     * Store a newly created achievement.
     */
    public function store(AchievementRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->generateUniqueSlug($data['slug'] ?? $data['title']);

        $this->storeFiles($request, $data);

        Achievement::create($data);

        return redirect()
            ->route('admin.achievements.index')
            ->with('success', 'Data prestasi berhasil ditambahkan.');
    }

    /**
     * Show the form for editing an achievement.
     */
    public function edit(Achievement $achievement): View
    {
        return view('admin.achievements.edit', compact('achievement'));
    }

    /**
     * Update the specified achievement.
     */
    public function update(AchievementRequest $request, Achievement $achievement): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->generateUniqueSlug($data['slug'] ?? $data['title'], $achievement);

        $this->replaceFiles($request, $achievement, $data);

        $achievement->update($data);

        return redirect()
            ->route('admin.achievements.index')
            ->with('success', 'Data prestasi berhasil diperbarui.');
    }

    /**
     * Soft delete the specified achievement.
     */
    public function destroy(Achievement $achievement): RedirectResponse
    {
        $achievement->delete();

        return redirect()
            ->route('admin.achievements.index')
            ->with('success', 'Data prestasi berhasil dihapus.');
    }

    /**
     * Store files submitted with a new achievement.
     *
     * @param  array<string, mixed>  $data
     */
    protected function storeFiles(AchievementRequest $request, array &$data): void
    {
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('achievements', 'public');
        }

        if ($request->hasFile('certificate')) {
            $data['certificate_path'] = $request->file('certificate')->store('achievements', 'public');
        }

        unset($data['image'], $data['certificate']);
    }

    /**
     * Replace uploaded files for an existing achievement.
     *
     * @param  array<string, mixed>  $data
     */
    protected function replaceFiles(AchievementRequest $request, Achievement $achievement, array &$data): void
    {
        if ($request->hasFile('image')) {
            if ($achievement->image_path) {
                Storage::disk('public')->delete($achievement->image_path);
            }

            $data['image_path'] = $request->file('image')->store('achievements', 'public');
        }

        if ($request->hasFile('certificate')) {
            if ($achievement->certificate_path) {
                Storage::disk('public')->delete($achievement->certificate_path);
            }

            $data['certificate_path'] = $request->file('certificate')->store('achievements', 'public');
        }

        unset($data['image'], $data['certificate']);
    }

    /**
     * Generate a unique slug, including against soft-deleted achievements.
     */
    protected function generateUniqueSlug(string $value, ?Achievement $ignoredAchievement = null): string
    {
        $baseSlug = Str::slug($value) ?: 'prestasi-sekolah';
        $slug = $baseSlug;
        $suffix = 2;

        while (
            Achievement::withTrashed()
                ->where('slug', $slug)
                ->when($ignoredAchievement, fn ($query) => $query->whereKeyNot($ignoredAchievement->getKey()))
                ->exists()
        ) {
            $slug = $baseSlug.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
}
