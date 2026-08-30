<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\GalleryAlbum;
use App\Models\SchoolProfile;
use App\Models\SiteSetting;
use App\Models\SocialLink;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(): View
    {
        $schoolProfile = SchoolProfile::query()->latest('id')->first();
        $siteSettings = $this->publicSettings();

        $albums = GalleryAlbum::query()
            ->where('is_published', true)
            ->withCount('photos')
            ->orderBy('sort_order')
            ->orderByDesc('event_date')
            ->orderBy('title')
            ->get();

        return view('frontend.pages.gallery.index', array_merge(
            $this->layoutData($schoolProfile, $siteSettings),
            compact('albums'),
        ));
    }

    public function show(GalleryAlbum $gallery_album): View
    {
        $schoolProfile = SchoolProfile::query()->latest('id')->first();
        $siteSettings = $this->publicSettings();

        $album = GalleryAlbum::query()
            ->whereKey($gallery_album->getKey())
            ->where('is_published', true)
            ->with('photos')
            ->firstOrFail();

        return view('frontend.pages.gallery.show', array_merge(
            $this->layoutData($schoolProfile, $siteSettings),
            compact('album'),
        ));
    }

    /**
     * @return \Illuminate\Support\Collection<string, SiteSetting>
     */
    private function publicSettings(): \Illuminate\Support\Collection
    {
        return SiteSetting::query()
            ->where('is_public', true)
            ->orderBy('key')
            ->get()
            ->keyBy('key');
    }

    /**
     * @param  \Illuminate\Support\Collection<string, SiteSetting>  $siteSettings
     * @return array<string, mixed>
     */
    private function layoutData(?SchoolProfile $schoolProfile, \Illuminate\Support\Collection $siteSettings): array
    {
        return [
            'schoolProfile' => $schoolProfile,
            'siteSettings' => $siteSettings,
            'contacts' => Contact::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('label')
                ->get(),
            'socialLinks' => SocialLink::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('platform')
                ->get(),
        ];
    }
}
