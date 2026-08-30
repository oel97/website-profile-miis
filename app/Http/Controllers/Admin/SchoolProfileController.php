<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SchoolProfileContentRequest;
use App\Http\Requests\Admin\SchoolProfileRequest;
use App\Models\Page;
use App\Models\SchoolProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SchoolProfileController extends Controller
{
    public function index(): View
    {
        $schoolProfiles = SchoolProfile::latest()->paginate(10);
        $contentProfile = SchoolProfile::query()->latest('id')->first();

        return view('admin.school-profile.index', compact('schoolProfiles', 'contentProfile'));
    }

    public function create(): View
    {
        return view('admin.school-profile.create');
    }

    public function store(SchoolProfileRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $contentPages = $this->extractContentPages($request, $data);

        $data['logo_path'] = $this->handleUpload($request, 'logo', 'school-profile/logo');
        $data['foto_sekolah_path'] = $this->handleUpload($request, 'foto_sekolah', 'school-profile/foto');

        if (isset($data['logo']) && $data['logo'] instanceof \Illuminate\Http\UploadedFile) {
            unset($data['logo']);
        }

        if (isset($data['foto_sekolah']) && $data['foto_sekolah'] instanceof \Illuminate\Http\UploadedFile) {
            unset($data['foto_sekolah']);
        }

        $schoolProfile = SchoolProfile::create($data);
        $this->syncContentPages($contentPages);

        return redirect()
            ->route('admin.school-profile.edit', $schoolProfile)
            ->with('success', 'Profil sekolah berhasil ditambahkan. Pilih bagian konten yang ingin diisi di bawah ini.');
    }

    public function edit(SchoolProfile $school_profile): View
    {
        return view('admin.school-profile.edit', array_merge(
            compact('school_profile'),
            $this->profileContentPages(),
        ));
    }

    public function update(SchoolProfileRequest $request, SchoolProfile $school_profile): RedirectResponse
    {
        $data = $request->validated();
        $contentPages = $this->extractContentPages($request, $data);

        if ($request->hasFile('logo')) {
            if ($school_profile->logo_path) {
                Storage::disk('public')->delete($school_profile->logo_path);
            }
            $data['logo_path'] = $this->handleUpload($request, 'logo', 'school-profile/logo');
        }

        if ($request->hasFile('foto_sekolah')) {
            if ($school_profile->foto_sekolah_path) {
                Storage::disk('public')->delete($school_profile->foto_sekolah_path);
            }
            $data['foto_sekolah_path'] = $this->handleUpload($request, 'foto_sekolah', 'school-profile/foto');
        }

        unset($data['logo'], $data['foto_sekolah']);

        $school_profile->update($data);
        $this->syncContentPages($contentPages);

        return redirect()->route('admin.school-profile.index')->with('success', 'Profil sekolah berhasil diperbarui.');
    }

    /**
     * Update one public-profile content section independently.
     */
    public function updateContent(
        SchoolProfileContentRequest $request,
        SchoolProfile $school_profile,
        string $section,
    ): RedirectResponse {
        $data = $request->validated();

        if ($section === 'about') {
            $school_profile->update(['about' => $data['about'] ?? null]);
            $message = 'Konten Tentang Madrasah berhasil diperbarui.';
        } elseif ($section === 'history') {
            $historyPage = $this->syncContentPage(
                [
                    'title' => $data['history_title'] ?? null,
                    'content' => $data['history_content'] ?? null,
                ],
                'sejarah',
                'sejarah-sekolah',
                'Sejarah Madrasah',
            );

            if ($request->hasFile('history_image') && $historyPage) {
                $imagePath = $request->file('history_image')->store('pages/history', 'public');

                if ($historyPage->cover_image_path) {
                    Storage::disk('public')->delete($historyPage->cover_image_path);
                }

                $historyPage->update(['cover_image_path' => $imagePath]);
            }

            $message = 'Konten Sejarah Sekolah berhasil diperbarui.';
        } else {
            $this->syncContentPage(
                [
                    'title' => $data['vision_mission_title'] ?? null,
                    'content' => $data['vision_mission_content'] ?? null,
                ],
                'visi-misi',
                'visi-dan-misi',
                'Visi dan Misi',
            );
            $message = 'Konten Visi & Misi berhasil diperbarui.';
        }

        return redirect()
            ->route('admin.school-profile.index')
            ->with('success', $message);
    }

    public function destroy(SchoolProfile $school_profile): RedirectResponse
    {
        if ($school_profile->logo_path) {
            Storage::disk('public')->delete($school_profile->logo_path);
        }

        if ($school_profile->foto_sekolah_path) {
            Storage::disk('public')->delete($school_profile->foto_sekolah_path);
        }

        $school_profile->delete();

        return redirect()->route('admin.school-profile.index')->with('success', 'Profil sekolah berhasil dihapus.');
    }

    protected function handleUpload(Request $request, string $field, string $directory): ?string
    {
        if (! $request->hasFile($field)) {
            return null;
        }

        return $request->file($field)->store($directory, 'public');
    }

    /**
     * Remove profile-page fields before the school profile is persisted.
     *
     * @param  array<string, mixed>  $data
     * @return array{history: array{title: mixed, content: mixed}|null, visionMission: array{title: mixed, content: mixed}|null}
     */
    protected function extractContentPages(Request $request, array &$data): array
    {
        $history = $request->hasAny(['history_title', 'history_content'])
            ? [
                'title' => $data['history_title'] ?? null,
                'content' => $data['history_content'] ?? null,
            ]
            : null;

        $visionMission = $request->hasAny(['vision_mission_title', 'vision_mission_content'])
            ? [
                'title' => $data['vision_mission_title'] ?? null,
                'content' => $data['vision_mission_content'] ?? null,
            ]
            : null;

        unset(
            $data['history_title'],
            $data['history_content'],
            $data['vision_mission_title'],
            $data['vision_mission_content'],
        );

        return compact('history', 'visionMission');
    }

    /**
     * @param  array{history: array{title: mixed, content: mixed}|null, visionMission: array{title: mixed, content: mixed}|null}  $contentPages
     */
    protected function syncContentPages(array $contentPages): void
    {
        $this->syncContentPage(
            $contentPages['history'],
            'sejarah',
            'sejarah-sekolah',
            'Sejarah Madrasah',
        );

        $this->syncContentPage(
            $contentPages['visionMission'],
            'visi-misi',
            'visi-dan-misi',
            'Visi dan Misi',
        );
    }

    /**
     * @param  array{title: mixed, content: mixed}|null  $content
     */
    protected function syncContentPage(?array $content, string $slug, string $legacySlug, string $defaultTitle): ?Page
    {
        if ($content === null) {
            return null;
        }

        $page = $this->contentPage($slug, $legacySlug) ?? new Page(['slug' => $slug]);
        $body = is_string($content['content']) && trim($content['content']) !== ''
            ? trim($content['content'])
            : null;
        $title = is_string($content['title']) && trim($content['title']) !== ''
            ? trim($content['title'])
            : $defaultTitle;

        $page->fill([
            'title' => $title,
            'slug' => $slug,
            'excerpt' => $body ? Str::limit(strip_tags($body), 250) : null,
            'content' => $body,
            'is_published' => true,
            'published_at' => $page->published_at ?? now(),
        ]);

        $page->save();

        return $page;
    }

    /**
     * @return array{historyPage: Page|null, visionMissionPage: Page|null}
     */
    protected function profileContentPages(): array
    {
        return [
            'historyPage' => $this->contentPage('sejarah', 'sejarah-sekolah'),
            'visionMissionPage' => $this->contentPage('visi-misi', 'visi-dan-misi'),
        ];
    }

    protected function contentPage(string $slug, string $legacySlug): ?Page
    {
        return Page::query()->where('slug', $slug)->first()
            ?? Page::query()->where('slug', $legacySlug)->first();
    }
}
