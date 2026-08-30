<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Post;
use App\Models\SchoolProfile;
use App\Models\SiteSetting;
use App\Models\SocialLink;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(): View
    {
        $schoolProfile = SchoolProfile::query()->latest('id')->first();
        $siteSettings = $this->publicSettings();

        $posts = $this->visiblePosts()
            ->latest('published_at')
            ->paginate(9);

        return view('frontend.pages.news.index', array_merge(
            $this->layoutData($schoolProfile, $siteSettings),
            compact('posts'),
        ));
    }

    public function show(Post $post): View
    {
        $schoolProfile = SchoolProfile::query()->latest('id')->first();
        $siteSettings = $this->publicSettings();

        $post = $this->visiblePosts()
            ->whereKey($post->getKey())
            ->firstOrFail();

        $relatedPosts = $this->visiblePosts()
            ->where('post_category_id', $post->post_category_id)
            ->whereKeyNot($post->getKey())
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('frontend.pages.news.show', array_merge(
            $this->layoutData($schoolProfile, $siteSettings),
            compact('post', 'relatedPosts'),
        ));
    }

    private function visiblePosts(): Builder
    {
        return Post::query()
            ->with('category')
            ->where('status', 'published')
            ->where(function (Builder $query): void {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->whereHas('category', fn (Builder $query): Builder => $query->where('is_active', true));
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
