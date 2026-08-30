<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HeroSlideRequest;
use App\Models\HeroSlide;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HeroSlideController extends Controller
{
    /**
     * Display the hero slide directory.
     */
    public function index(): View
    {
        $heroSlides = HeroSlide::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(10);

        return view('admin.hero-slides.index', compact('heroSlides'));
    }

    /**
     * Show the form for creating a hero slide.
     */
    public function create(): View
    {
        return view('admin.hero-slides.create');
    }

    /**
     * Store a newly created hero slide.
     */
    public function store(HeroSlideRequest $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        HeroSlide::create($data);

        return redirect()
            ->route('admin.hero-slides.index')
            ->with('success', 'Hero slide berhasil ditambahkan.');
    }

    /**
     * Show the form for editing a hero slide.
     */
    public function edit(HeroSlide $heroSlide): View
    {
        return view('admin.hero-slides.edit', compact('heroSlide'));
    }

    /**
     * Update the specified hero slide.
     */
    public function update(HeroSlideRequest $request, HeroSlide $heroSlide): RedirectResponse
    {
        $data = $this->validatedData($request, $heroSlide);

        $heroSlide->update($data);

        return redirect()
            ->route('admin.hero-slides.index')
            ->with('success', 'Hero slide berhasil diperbarui.');
    }

    /**
     * Delete the specified hero slide and its uploaded images.
     */
    public function destroy(HeroSlide $heroSlide): RedirectResponse
    {
        Storage::disk('public')->delete(array_filter(array_unique([
            $heroSlide->image_path,
            $heroSlide->mobile_image_path,
        ])));

        $heroSlide->delete();

        return redirect()
            ->route('admin.hero-slides.index')
            ->with('success', 'Hero slide berhasil dihapus.');
    }

    /**
     * Prepare request data and replace images safely when needed.
     *
     * @return array<string, mixed>
     */
    private function validatedData(HeroSlideRequest $request, ?HeroSlide $heroSlide = null): array
    {
        $data = $request->validated();

        foreach (['image', 'mobile_image'] as $field) {
            if (! $request->hasFile($field)) {
                continue;
            }

            $pathColumn = $field === 'image' ? 'image_path' : 'mobile_image_path';

            if ($heroSlide?->{$pathColumn}) {
                Storage::disk('public')->delete($heroSlide->{$pathColumn});
            }

            $data[$pathColumn] = $request->file($field)->store('hero', 'public');
        }

        unset($data['image'], $data['mobile_image']);

        return $data;
    }
}
