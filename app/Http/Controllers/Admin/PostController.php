<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostRequest;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostController extends Controller
{
    /**
     * Display the posts.
     */
    public function index(): View
    {
        $posts = Post::query()
            ->with(['category', 'author'])
            ->orderByDesc('published_at')
            ->latest()
            ->paginate(10);

        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a post.
     */
    public function create(): View
    {
        $categories = $this->categories();

        return view('admin.posts.create', compact('categories'));
    }

    /**
     * Store a newly created post.
     */
    public function store(PostRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['author_id'] = $request->user()->getKey();
        $data['slug'] = $this->generateUniqueSlug($data['slug'] ?? $data['title']);
        $this->setPublishedAt($data);

        if ($request->hasFile('thumbnail')) {
            $data['featured_image_path'] = $request->file('thumbnail')->store('posts', 'public');
        }

        unset($data['thumbnail']);

        Post::create($data);

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Berita atau kegiatan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing a post.
     */
    public function edit(Post $post): View
    {
        $categories = $this->categories();

        return view('admin.posts.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified post.
     */
    public function update(PostRequest $request, Post $post): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->generateUniqueSlug($data['slug'] ?? $data['title'], $post);
        $this->setPublishedAt($data);

        if ($request->hasFile('thumbnail')) {
            if ($post->featured_image_path) {
                Storage::disk('public')->delete($post->featured_image_path);
            }

            $data['featured_image_path'] = $request->file('thumbnail')->store('posts', 'public');
        }

        unset($data['thumbnail']);

        $post->update($data);

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Berita atau kegiatan berhasil diperbarui.');
    }

    /**
     * Soft delete the specified post.
     */
    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Berita atau kegiatan berhasil dihapus.');
    }

    /**
     * Get categories for the post form.
     *
     * @return Collection<int, PostCategory>
     */
    protected function categories(): Collection
    {
        return PostCategory::query()
            ->orderByDesc('is_active')
            ->orderBy('name')
            ->get();
    }

    /**
     * Set the publication timestamp based on the selected status.
     *
     * @param  array<string, mixed>  $data
     */
    protected function setPublishedAt(array &$data): void
    {
        if ($data['status'] === 'draft') {
            $data['published_at'] = null;

            return;
        }

        $data['published_at'] ??= now();
    }

    /**
     * Generate a unique slug, including against soft-deleted posts.
     */
    protected function generateUniqueSlug(string $value, ?Post $ignoredPost = null): string
    {
        $baseSlug = Str::slug($value) ?: 'berita-kegiatan';
        $slug = $baseSlug;
        $suffix = 2;

        while (
            Post::withTrashed()
                ->where('slug', $slug)
                ->when($ignoredPost, fn ($query) => $query->whereKeyNot($ignoredPost->getKey()))
                ->exists()
        ) {
            $slug = $baseSlug.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
}
