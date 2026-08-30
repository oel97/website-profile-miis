<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\SchoolProfile;
use App\Models\SiteSetting;
use App\Models\SocialLink;
use App\Models\Staff;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StaffController extends Controller
{
    public function index(): View
    {
        $schoolProfile = SchoolProfile::query()->latest('id')->first();

        $siteSettings = SiteSetting::query()
            ->where('is_public', true)
            ->orderBy('key')
            ->get()
            ->keyBy('key');

        $staffMembers = Staff::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $teachers = $staffMembers
            ->filter(fn (Staff $staff): bool => $this->isTeacher($staff))
            ->values();

        $educationStaff = $staffMembers
            ->reject(fn (Staff $staff): bool => $this->isTeacher($staff))
            ->values();

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

        return view('frontend.pages.staff', compact(
            'schoolProfile',
            'siteSettings',
            'teachers',
            'educationStaff',
            'contacts',
            'socialLinks',
        ));
    }

    /**
     * Display one active staff member.
     */
    public function show(Staff $staff): View
    {
        $schoolProfile = SchoolProfile::query()->latest('id')->first();

        $siteSettings = SiteSetting::query()
            ->where('is_public', true)
            ->orderBy('key')
            ->get()
            ->keyBy('key');

        $staff = Staff::query()
            ->whereKey($staff->getKey())
            ->where('is_active', true)
            ->firstOrFail();

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

        return view('frontend.pages.staff.show', compact(
            'schoolProfile',
            'siteSettings',
            'staff',
            'contacts',
            'socialLinks',
        ));
    }

    private function isTeacher(Staff $staff): bool
    {
        return Str::contains(
            Str::lower($staff->employment_type . ' ' . $staff->position),
            'guru',
        );
    }
}
