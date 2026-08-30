<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\SchoolProfile;
use App\Models\SiteSetting;
use App\Models\SocialLink;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        $schoolProfile = SchoolProfile::query()->latest('id')->first();

        $siteSettings = SiteSetting::query()
            ->where('is_public', true)
            ->orderBy('key')
            ->get()
            ->keyBy('key');

        $contacts = Contact::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('label')
            ->get();

        $socialLinks = SocialLink::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('platform')
            ->get();

        $mapEmbedUrl = $this->mapEmbedUrl($schoolProfile);

        return view('frontend.pages.contact.index', compact(
            'schoolProfile',
            'siteSettings',
            'contacts',
            'socialLinks',
            'mapEmbedUrl',
        ));
    }

    private function mapEmbedUrl(?SchoolProfile $schoolProfile): ?string
    {
        if ($schoolProfile?->map_embed_url) {
            return $schoolProfile->map_embed_url;
        }

        if ($schoolProfile?->latitude && $schoolProfile->longitude) {
            return 'https://www.google.com/maps?q='.$schoolProfile->latitude.','.$schoolProfile->longitude.'&output=embed';
        }

        if ($schoolProfile?->address) {
            return 'https://www.google.com/maps?q='.rawurlencode($schoolProfile->address).'&output=embed';
        }

        return null;
    }
}
