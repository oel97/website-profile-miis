<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Page;
use App\Models\PrincipalMessage;
use App\Models\SchoolProfile;
use App\Models\SiteSetting;
use App\Models\SocialLink;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(): View
    {
        $schoolProfile = SchoolProfile::query()->latest('id')->first();

        $siteSettings = SiteSetting::query()
            ->where('is_public', true)
            ->orderBy('key')
            ->get()
            ->keyBy('key');

        $pages = $this->publishedPages();

        $historyPage = $pages->firstWhere('slug', 'sejarah')
            ?? $pages->firstWhere('slug', 'sejarah-sekolah');

        $visionMissionPage = $pages->firstWhere('slug', 'visi-misi')
            ?? $pages->firstWhere('slug', 'visi-dan-misi');

        $principalMessage = PrincipalMessage::query()
            ->where('is_active', true)
            ->latest('id')
            ->first();

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

        return view('frontend.pages.profile', compact(
            'schoolProfile',
            'siteSettings',
            'historyPage',
            'visionMissionPage',
            'principalMessage',
            'contacts',
            'socialLinks',
        ));
    }

    /**
     * @return Collection<int, Page>
     */
    private function publishedPages(): Collection
    {
        return Page::query()
            ->where('is_published', true)
            ->whereIn('slug', ['sejarah', 'sejarah-sekolah', 'visi-misi', 'visi-dan-misi'])
            ->where(function ($query): void {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->get();
    }
}
