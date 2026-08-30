<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\Agenda;
use App\Models\Contact;
use App\Models\FeaturedProgram;
use App\Models\HeroSlide;
use App\Models\HomepageSection;
use App\Models\Post;
use App\Models\SchoolProfile;
use App\Models\SiteSetting;
use App\Models\SocialLink;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $schoolProfile = SchoolProfile::query()->latest('id')->first();

        $siteSettings = SiteSetting::query()
            ->where('is_public', true)
            ->orderBy('key')
            ->get()
            ->keyBy('key');

        $heroSlides = HeroSlide::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $homepageSections = HomepageSection::query()
            ->orderBy('sort_order')
            ->orderBy('key')
            ->get()
            ->keyBy('key');

        $itemLimit = static function (string $key, int $default) use ($homepageSections): int {
            return $homepageSections->get($key)?->item_limit ?? $default;
        };

        $featuredPrograms = FeaturedProgram::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('title')
            ->take($itemLimit('programs', 3))
            ->get();

        $latestPosts = Post::query()
            ->with('category')
            ->where('status', 'published')
            ->where(function ($query): void {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->whereHas('category', fn ($query) => $query->where('is_active', true))
            ->latest('published_at')
            ->take($itemLimit('news', 3))
            ->get();

        $latestAchievements = Achievement::query()
            ->where('is_published', true)
            ->orderByDesc('achievement_date')
            ->orderBy('sort_order')
            ->take($itemLimit('achievements', 3))
            ->get();

        $latestAgendas = Agenda::query()
            ->where('is_published', true)
            ->orderByDesc('start_at')
            ->take($itemLimit('agenda_ppdb', 3))
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

        return view('frontend.pages.home', compact(
            'schoolProfile',
            'siteSettings',
            'heroSlides',
            'homepageSections',
            'featuredPrograms',
            'latestPosts',
            'latestAchievements',
            'latestAgendas',
            'contacts',
            'socialLinks',
        ));
    }
}
