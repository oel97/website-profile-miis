<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PpdbPeriodRequest;
use App\Models\PpdbPeriod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PpdbPeriodController extends Controller
{
    public function index(): View
    {
        $periods = PpdbPeriod::query()
            ->withCount(['requirements', 'steps'])
            ->orderByDesc('is_active')
            ->orderByDesc('registration_start_at')
            ->paginate(10);

        return view('admin.ppdb-periods.index', compact('periods'));
    }

    public function create(): View
    {
        return view('admin.ppdb-periods.create');
    }

    public function store(PpdbPeriodRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->generateUniqueSlug($data['slug'] ?? $data['title']);

        if ($request->hasFile('banner')) {
            $data['banner_path'] = $request->file('banner')->store('ppdb', 'public');
        }

        unset($data['banner']);

        PpdbPeriod::create($data);

        return redirect()
            ->route('admin.ppdb-periods.index')
            ->with('success', 'Periode PPDB berhasil ditambahkan.');
    }

    public function edit(PpdbPeriod $ppdb_period): View
    {
        return view('admin.ppdb-periods.edit', compact('ppdb_period'));
    }

    public function update(PpdbPeriodRequest $request, PpdbPeriod $ppdb_period): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->generateUniqueSlug($data['slug'] ?? $data['title'], $ppdb_period);

        if ($request->hasFile('banner')) {
            if ($ppdb_period->banner_path) {
                Storage::disk('public')->delete($ppdb_period->banner_path);
            }

            $data['banner_path'] = $request->file('banner')->store('ppdb', 'public');
        }

        unset($data['banner']);

        $ppdb_period->update($data);

        return redirect()
            ->route('admin.ppdb-periods.index')
            ->with('success', 'Periode PPDB berhasil diperbarui.');
    }

    public function destroy(PpdbPeriod $ppdb_period): RedirectResponse
    {
        if ($ppdb_period->banner_path) {
            Storage::disk('public')->delete($ppdb_period->banner_path);
        }

        $ppdb_period->delete();

        return redirect()
            ->route('admin.ppdb-periods.index')
            ->with('success', 'Periode PPDB berhasil dihapus.');
    }

    private function generateUniqueSlug(string $value, ?PpdbPeriod $ignoredPeriod = null): string
    {
        $baseSlug = Str::slug($value) ?: 'ppdb';
        $slug = $baseSlug;
        $counter = 2;

        while (PpdbPeriod::query()
            ->where('slug', $slug)
            ->when($ignoredPeriod, fn ($query) => $query->whereKeyNot($ignoredPeriod->getKey()))
            ->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
