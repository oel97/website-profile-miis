<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GalleryAlbumRequest;
use App\Models\GalleryAlbum;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class GalleryAlbumController extends Controller
{
    public function index(): View
    {
        $albums = GalleryAlbum::query()
            ->withCount('photos')
            ->orderBy('sort_order')
            ->orderByDesc('event_date')
            ->orderBy('title')
            ->paginate(10);

        return view('admin.gallery-albums.index', compact('albums'));
    }

    public function create(): View
    {
        return view('admin.gallery-albums.create');
    }

    public function store(GalleryAlbumRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->generateUniqueSlug($data['slug'] ?? $data['title']);

        if ($request->hasFile('image')) {
            $data['cover_image_path'] = $request->file('image')->store('gallery/albums', 'public');
        }

        unset($data['image']);

        GalleryAlbum::create($data);

        return redirect()
            ->route('admin.gallery-albums.index')
            ->with('success', 'Album galeri berhasil ditambahkan.');
    }

    public function edit(GalleryAlbum $gallery_album): View
    {
        return view('admin.gallery-albums.edit', compact('gallery_album'));
    }

    public function update(GalleryAlbumRequest $request, GalleryAlbum $gallery_album): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->generateUniqueSlug($data['slug'] ?? $data['title'], $gallery_album);

        if ($request->hasFile('image')) {
            if ($gallery_album->cover_image_path) {
                Storage::disk('public')->delete($gallery_album->cover_image_path);
            }

            $data['cover_image_path'] = $request->file('image')->store('gallery/albums', 'public');
        }

        unset($data['image']);

        $gallery_album->update($data);

        return redirect()
            ->route('admin.gallery-albums.index')
            ->with('success', 'Album galeri berhasil diperbarui.');
    }

    public function destroy(GalleryAlbum $gallery_album): RedirectResponse
    {
        $gallery_album->delete();

        return redirect()
            ->route('admin.gallery-albums.index')
            ->with('success', 'Album galeri berhasil dihapus.');
    }

    private function generateUniqueSlug(string $value, ?GalleryAlbum $ignoredAlbum = null): string
    {
        $baseSlug = Str::slug($value) ?: 'album-galeri';
        $slug = $baseSlug;
        $counter = 2;

        while (GalleryAlbum::withTrashed()
            ->where('slug', $slug)
            ->when($ignoredAlbum, fn ($query) => $query->whereKeyNot($ignoredAlbum->getKey()))
            ->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
