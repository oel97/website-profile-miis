<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SocialLinkRequest;
use App\Models\SocialLink;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SocialLinkController extends Controller
{
    public function index(): View
    {
        $socialLinks = SocialLink::query()
            ->orderByDesc('is_active')
            ->orderBy('sort_order')
            ->orderBy('platform')
            ->paginate(15);

        return view('admin.social-links.index', compact('socialLinks'));
    }

    public function create(): View
    {
        return view('admin.social-links.create');
    }

    public function store(SocialLinkRequest $request): RedirectResponse
    {
        SocialLink::create($request->validated());

        return redirect()
            ->route('admin.social-links.index')
            ->with('success', 'Sosial media berhasil ditambahkan.');
    }

    public function edit(SocialLink $socialLink): View
    {
        return view('admin.social-links.edit', compact('socialLink'));
    }

    public function update(SocialLinkRequest $request, SocialLink $socialLink): RedirectResponse
    {
        $socialLink->update($request->validated());

        return redirect()
            ->route('admin.social-links.index')
            ->with('success', 'Sosial media berhasil diperbarui.');
    }

    public function destroy(SocialLink $socialLink): RedirectResponse
    {
        $socialLink->delete();

        return redirect()
            ->route('admin.social-links.index')
            ->with('success', 'Sosial media berhasil dihapus.');
    }
}
