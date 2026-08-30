<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\GalleryAlbum;
use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $urls = [
            ['loc' => route('home'), 'changefreq' => 'weekly', 'priority' => '1.0'],
            ['loc' => route('profile'), 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => route('staff'), 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => route('program'), 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => route('news'), 'changefreq' => 'daily', 'priority' => '0.8'],
            ['loc' => route('achievement'), 'changefreq' => 'weekly', 'priority' => '0.6'],
            ['loc' => route('agenda'), 'changefreq' => 'weekly', 'priority' => '0.6'],
            ['loc' => route('gallery'), 'changefreq' => 'weekly', 'priority' => '0.6'],
            ['loc' => route('facility'), 'changefreq' => 'monthly', 'priority' => '0.5'],
            ['loc' => route('ppdb'), 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['loc' => route('contact'), 'changefreq' => 'monthly', 'priority' => '0.5'],
        ];

        $posts = Post::query()
            ->select(['slug', 'published_at', 'updated_at'])
            ->where('status', 'published')
            ->where(function (Builder $query): void {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->whereHas('category', fn (Builder $query): Builder => $query->where('is_active', true))
            ->orderByDesc('published_at')
            ->get();

        foreach ($posts as $post) {
            $urls[] = [
                'loc' => route('news.show', $post),
                'lastmod' => $post->updated_at,
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ];
        }

        $albums = GalleryAlbum::query()
            ->select(['slug', 'updated_at'])
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->get();

        foreach ($albums as $album) {
            $urls[] = [
                'loc' => route('gallery.show', $album),
                'lastmod' => $album->updated_at,
                'changefreq' => 'monthly',
                'priority' => '0.5',
            ];
        }

        return response()
            ->view('frontend.sitemap', compact('urls'))
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
