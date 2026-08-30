<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SiteSettingRequest;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SiteSettingController extends Controller
{
    public function index(): View
    {
        $siteSettings = SiteSetting::query()
            ->orderBy('group')
            ->orderBy('key')
            ->paginate(15);

        return view('admin.site-settings.index', compact('siteSettings'));
    }

    public function create(): View
    {
        return view('admin.site-settings.create');
    }

    public function store(SiteSettingRequest $request): RedirectResponse
    {
        SiteSetting::create($request->validated());

        return redirect()
            ->route('admin.site-settings.index')
            ->with('success', 'Pengaturan website berhasil ditambahkan.');
    }

    public function edit(SiteSetting $siteSetting): View
    {
        return view('admin.site-settings.edit', compact('siteSetting'));
    }

    public function update(SiteSettingRequest $request, SiteSetting $siteSetting): RedirectResponse
    {
        $siteSetting->update($request->validated());

        return redirect()
            ->route('admin.site-settings.index')
            ->with('success', 'Pengaturan website berhasil diperbarui.');
    }

    public function destroy(SiteSetting $siteSetting): RedirectResponse
    {
        $siteSetting->delete();

        return redirect()
            ->route('admin.site-settings.index')
            ->with('success', 'Pengaturan website berhasil dihapus.');
    }
}
