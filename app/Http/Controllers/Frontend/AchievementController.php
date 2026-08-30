<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\Contact;
use App\Models\SchoolProfile;
use App\Models\SiteSetting;
use App\Models\SocialLink;
use Illuminate\View\View;

class AchievementController extends Controller
{
    public function index(): View
    {
        $schoolProfile = SchoolProfile::query()->latest('id')->first();

        $siteSettings = SiteSetting::query()
            ->where('is_public', true)
            ->orderBy('key')
            ->get()
            ->keyBy('key');

        $achievements = Achievement::query()
            ->where('is_published', true)
            ->orderByDesc('achievement_date')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

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

        return view('frontend.pages.achievements.index', compact(
            'schoolProfile',
            'siteSettings',
            'achievements',
            'contacts',
            'socialLinks',
        ));
    }

    /**
     * Display one published achievement.
     */
    public function show(Achievement $achievement): View
    {
        $schoolProfile = SchoolProfile::query()->latest('id')->first();

        $siteSettings = SiteSetting::query()
            ->where('is_public', true)
            ->orderBy('key')
            ->get()
            ->keyBy('key');

        $achievement = Achievement::query()
            ->whereKey($achievement->getKey())
            ->where('is_published', true)
            ->firstOrFail();

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

        return view('frontend.pages.achievements.show', compact(
            'schoolProfile',
            'siteSettings',
            'achievement',
            'contacts',
            'socialLinks',
        ));
    }
}
