<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HomepageSectionRequest;
use App\Models\HomepageSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HomepageSectionController extends Controller
{
    /**
     * Display the homepage section directory.
     */
    public function index(): View
    {
        $homepageSections = HomepageSection::query()
            ->orderBy('sort_order')
            ->orderBy('key')
            ->paginate(10);

        return view('admin.homepage-sections.index', compact('homepageSections'));
    }

    /**
     * Show the form for creating a homepage section configuration.
     */
    public function create(): View
    {
        return view('admin.homepage-sections.create', [
            'sectionOptions' => HomepageSection::MANAGEABLE_SECTIONS,
        ]);
    }

    /**
     * Store a new homepage section configuration.
     */
    public function store(HomepageSectionRequest $request): RedirectResponse
    {
        HomepageSection::create($this->validatedData($request));

        return redirect()
            ->route('admin.homepage-sections.index')
            ->with('success', 'Pengaturan section beranda berhasil ditambahkan.');
    }

    /**
     * Show the form for editing a homepage section configuration.
     */
    public function edit(HomepageSection $homepageSection): View
    {
        return view('admin.homepage-sections.edit', [
            'homepageSection' => $homepageSection,
            'sectionOptions' => HomepageSection::MANAGEABLE_SECTIONS,
        ]);
    }

    /**
     * Update the specified homepage section configuration.
     */
    public function update(HomepageSectionRequest $request, HomepageSection $homepageSection): RedirectResponse
    {
        $homepageSection->update($this->validatedData($request));

        return redirect()
            ->route('admin.homepage-sections.index')
            ->with('success', 'Pengaturan section beranda berhasil diperbarui.');
    }

    /**
     * Delete the specified homepage section configuration.
     */
    public function destroy(HomepageSection $homepageSection): RedirectResponse
    {
        $homepageSection->delete();

        return redirect()
            ->route('admin.homepage-sections.index')
            ->with('success', 'Pengaturan section beranda berhasil dihapus.');
    }

    /**
     * Decode the optional JSON settings before model casting.
     *
     * @return array<string, mixed>
     */
    private function validatedData(HomepageSectionRequest $request): array
    {
        $data = $request->validated();
        $data['settings'] = filled($data['settings'] ?? null)
            ? json_decode($data['settings'], true)
            : null;

        return $data;
    }
}
