<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StaffRequest;
use App\Models\Staff;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

class StaffController extends Controller
{
    /**
     * Display the staff directory.
     */
    public function index(): View
    {
        $staffMembers = Staff::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(10);

        return view('admin.staff.index', compact('staffMembers'));
    }

    /**
     * Show the form for creating a staff member.
     */
    public function create(): View
    {
        return view('admin.staff.create', $this->formOptions());
    }

    /**
     * Store a newly created staff member.
     */
    public function store(StaffRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $photoPath = null;

        try {
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('staff', 'public');

                if (! $photoPath) {
                    throw new RuntimeException('Foto staff gagal disimpan.');
                }

                $data['photo_path'] = $photoPath;
            }

            unset($data['photo']);

            Staff::create($data);
        } catch (Throwable $exception) {
            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }

            throw $exception;
        }

        return redirect()
            ->route('admin.staff.index')
            ->with('success', 'Data guru atau tenaga kependidikan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing a staff member.
     */
    public function edit(Staff $staff): View
    {
        return view('admin.staff.edit', [
            'staff' => $staff,
            ...$this->formOptions($staff),
        ]);
    }

    /**
     * Update the specified staff member.
     */
    public function update(StaffRequest $request, Staff $staff): RedirectResponse
    {
        $data = $request->validated();
        $oldPhotoPath = $staff->photo_path;
        $newPhotoPath = null;

        try {
            if ($request->hasFile('photo')) {
                $newPhotoPath = $request->file('photo')->store('staff', 'public');

                if (! $newPhotoPath) {
                    throw new RuntimeException('Foto staff gagal disimpan.');
                }

                $data['photo_path'] = $newPhotoPath;
            }

            unset($data['photo']);

            $staff->update($data);
        } catch (Throwable $exception) {
            if ($newPhotoPath) {
                Storage::disk('public')->delete($newPhotoPath);
            }

            throw $exception;
        }

        if ($newPhotoPath && $oldPhotoPath && $oldPhotoPath !== $newPhotoPath) {
            Storage::disk('public')->delete($oldPhotoPath);
        }

        return redirect()
            ->route('admin.staff.index')
            ->with('success', 'Data guru atau tenaga kependidikan berhasil diperbarui.');
    }

    /**
     * Soft delete the specified staff member.
     */
    public function destroy(Staff $staff): RedirectResponse
    {
        $photoPath = $staff->photo_path;

        $staff->delete();

        if ($photoPath) {
            Storage::disk('public')->delete($photoPath);
        }

        return redirect()
            ->route('admin.staff.index')
            ->with('success', 'Data guru atau tenaga kependidikan berhasil dihapus.');
    }

    /**
     * Get options for the create and edit forms without discarding legacy values.
     *
     * @return array<string, array<mixed>>
     */
    private function formOptions(?Staff $staff = null): array
    {
        $categoryOptions = Staff::CATEGORY_OPTIONS;
        $educationOptions = Staff::EDUCATION_OPTIONS;

        if ($staff && filled($staff->employment_type)) {
            $categoryOptions[$staff->type] ??= [];

            if (! in_array($staff->employment_type, $categoryOptions[$staff->type], true)) {
                $categoryOptions[$staff->type][] = $staff->employment_type;
            }
        }

        if ($staff && filled($staff->education) && ! in_array($staff->education, $educationOptions, true)) {
            $educationOptions[] = $staff->education;
        }

        return [
            'typeOptions' => Staff::TYPE_OPTIONS,
            'categoryOptions' => $categoryOptions,
            'educationOptions' => $educationOptions,
        ];
    }
}
