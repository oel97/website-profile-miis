<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostCategoryRequest;
use App\Models\PostCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostCategoryController extends Controller
{
    /**
     * Display the post categories.
     */
    public function index(): View
    {
        $categories = PostCategory::query()
            ->withCount([
                'posts as posts_count' => fn ($query) => $query->withTrashed(),
            ])
            ->orderBy('name')
            ->paginate(10);

        return view('admin.post-categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a post category.
     */
    public function create(): View
    {
        return view('admin.post-categories.create');
    }

    /**
     * Store a newly created post category.
     */
    public function store(PostCategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->generateUniqueSlug($data['slug'] ?? $data['name']);

        PostCategory::create($data);

        return redirect()
            ->route('admin.post-categories.index')
            ->with('success', 'Kategori berita berhasil ditambahkan.');
    }

    /**
     * Show the form for editing a post category.
     */
    public function edit(PostCategory $post_category): View
    {
        return view('admin.post-categories.edit', compact('post_category'));
    }

    /**
     * Update the specified post category.
     */
    public function update(PostCategoryRequest $request, PostCategory $post_category): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->generateUniqueSlug($data['slug'] ?? $data['name'], $post_category);

        $post_category->update($data);

        return redirect()
            ->route('admin.post-categories.index')
            ->with('success', 'Kategori berita berhasil diperbarui.');
    }

    /**
     * Remove the specified post category when it is not used by a post.
     */
    public function destroy(PostCategory $post_category): RedirectResponse
    {
        if ($post_category->posts()->withTrashed()->exists()) {
            return redirect()
                ->route('admin.post-categories.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh berita.');
        }

        $post_category->delete();

        return redirect()
            ->route('admin.post-categories.index')
            ->with('success', 'Kategori berita berhasil dihapus.');
    }

    /**
     * Generate a unique slug for the category.
     */
    protected function generateUniqueSlug(string $value, ?PostCategory $ignoredCategory = null): string
    {
        $baseSlug = Str::slug($value) ?: 'kategori-berita';
        $slug = $baseSlug;
        $suffix = 2;

        while (
            PostCategory::query()
                ->where('slug', $slug)
                ->when($ignoredCategory, fn ($query) => $query->whereKeyNot($ignoredCategory->getKey()))
                ->exists()
        ) {
            $slug = $baseSlug.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
}
