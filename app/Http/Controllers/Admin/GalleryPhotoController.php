<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GalleryPhotoRequest;
use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class GalleryPhotoController extends Controller
{
    public function index(Request $request): View
    {
        $albums = $this->albums();
        $selectedAlbumId = $request->integer('album_id') ?: null;

        $photos = GalleryPhoto::query()
            ->with('album')
            ->whereHas('album')
            ->when($selectedAlbumId, fn ($query) => $query->where('gallery_album_id', $selectedAlbumId))
            ->orderBy('gallery_album_id')
            ->orderBy('sort_order')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.gallery-photos.index', compact('albums', 'photos', 'selectedAlbumId'));
    }

    public function create(): View
    {
        $albums = $this->albums();

        return view('admin.gallery-photos.create', compact('albums'));
    }

    public function store(GalleryPhotoRequest $request): RedirectResponse
    {
        $data = $request->safe()->except(['image', 'images']);
        $images = $this->uploadedImages($request);
        $sortOrder = (int) ($data['sort_order'] ?? 0);

        foreach ($images as $index => $image) {
            GalleryPhoto::create([
                ...$data,
                'image_path' => $image->store('gallery/photos', 'public'),
                'sort_order' => $sortOrder + $index,
            ]);
        }

        $message = count($images) === 1
            ? 'Foto galeri berhasil ditambahkan.'
            : count($images).' foto galeri berhasil ditambahkan.';

        return redirect()
            ->route('admin.gallery-photos.index', ['album_id' => $data['gallery_album_id']])
            ->with('success', $message);
    }

    public function edit(GalleryPhoto $gallery_photo): View
    {
        $albums = $this->albums();

        return view('admin.gallery-photos.edit', compact('gallery_photo', 'albums'));
    }

    public function update(GalleryPhotoRequest $request, GalleryPhoto $gallery_photo): RedirectResponse
    {
        $data = $request->safe()->except(['image', 'images']);

        if ($request->hasFile('image')) {
            if ($gallery_photo->image_path) {
                Storage::disk('public')->delete($gallery_photo->image_path);
            }

            $data['image_path'] = $request->file('image')->store('gallery/photos', 'public');
        }

        $gallery_photo->update($data);

        return redirect()
            ->route('admin.gallery-photos.index', ['album_id' => $gallery_photo->gallery_album_id])
            ->with('success', 'Foto galeri berhasil diperbarui.');
    }

    public function destroy(GalleryPhoto $gallery_photo): RedirectResponse
    {
        $albumId = $gallery_photo->gallery_album_id;

        if ($gallery_photo->image_path) {
            Storage::disk('public')->delete($gallery_photo->image_path);
        }

        $gallery_photo->delete();

        return redirect()
            ->route('admin.gallery-photos.index', ['album_id' => $albumId])
            ->with('success', 'Foto galeri berhasil dihapus.');
    }

    /**
     * Get active albums for the album selector.
     *
     * @return Collection<int, GalleryAlbum>
     */
    protected function albums(): Collection
    {
        return GalleryAlbum::query()
            ->orderBy('sort_order')
            ->orderByDesc('event_date')
            ->orderBy('title')
            ->get();
    }

    /**
     * Get all uploaded images from either the single or multi-upload field.
     *
     * @return array<int, UploadedFile>
     */
    protected function uploadedImages(GalleryPhotoRequest $request): array
    {
        $images = [];

        if ($request->hasFile('image')) {
            $images[] = $request->file('image');
        }

        foreach ($request->file('images', []) as $image) {
            $images[] = $image;
        }

        return $images;
    }
}
