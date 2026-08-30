<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StaffRequest;
use App\Models\Staff;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

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
        return view('admin.staff.create');
    }

    /**
     * Store a newly created staff member.
     */
    public function store(StaffRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('staff', 'public');
        }

        unset($data['photo']);

        Staff::create($data);

        return redirect()
            ->route('admin.staff.index')
            ->with('success', 'Data guru atau tenaga kependidikan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing a staff member.
     */
    public function edit(Staff $staff): View
    {
        return view('admin.staff.edit', compact('staff'));
    }

    /**
     * Update the specified staff member.
     */
    public function update(StaffRequest $request, Staff $staff): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            if ($staff->photo_path) {
                Storage::disk('public')->delete($staff->photo_path);
            }

            $data['photo_path'] = $request->file('photo')->store('staff', 'public');
        }

        unset($data['photo']);

        $staff->update($data);

        return redirect()
            ->route('admin.staff.index')
            ->with('success', 'Data guru atau tenaga kependidikan berhasil diperbarui.');
    }

    /**
     * Soft delete the specified staff member.
     */
    public function destroy(Staff $staff): RedirectResponse
    {
        $staff->delete();

        return redirect()
            ->route('admin.staff.index')
            ->with('success', 'Data guru atau tenaga kependidikan berhasil dihapus.');
    }
}
