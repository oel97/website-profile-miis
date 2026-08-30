<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\PpdbPeriod;
use App\Models\SchoolProfile;
use App\Models\SiteSetting;
use App\Models\SocialLink;
use Illuminate\View\View;

class PpdbController extends Controller
{
    public function index(): View
    {
        $schoolProfile = SchoolProfile::query()->latest('id')->first();

        $siteSettings = SiteSetting::query()
            ->where('is_public', true)
            ->orderBy('key')
            ->get()
            ->keyBy('key');

        $period = PpdbPeriod::query()
            ->where('is_active', true)
            ->where('is_published', true)
            ->with([
                'requirements' => fn ($query) => $query
                    ->where('is_active', true)
                    ->orderBy('sort_order'),
                'steps' => fn ($query) => $query->orderBy('sort_order'),
            ])
            ->orderByDesc('registration_start_at')
            ->first();

        $whatsappUrl = $this->whatsappUrl($period?->contact_whatsapp);

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

        return view('frontend.pages.ppdb.index', compact(
            'schoolProfile',
            'siteSettings',
            'period',
            'whatsappUrl',
            'contacts',
            'socialLinks',
        ));
    }

    private function whatsappUrl(?string $whatsappNumber): ?string
    {
        if (blank($whatsappNumber)) {
            return null;
        }

        $number = preg_replace('/\D+/', '', $whatsappNumber);

        if (blank($number)) {
            return null;
        }

        if (str_starts_with($number, '0')) {
            $number = '62'.substr($number, 1);
        }

        return "https://wa.me/{$number}";
    }
}
