<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\Contact;
use App\Models\SchoolProfile;
use App\Models\SiteSetting;
use App\Models\SocialLink;
use Illuminate\View\View;

class AgendaController extends Controller
{
    public function index(): View
    {
        $schoolProfile = SchoolProfile::query()->latest('id')->first();

        $siteSettings = SiteSetting::query()
            ->where('is_public', true)
            ->orderBy('key')
            ->get()
            ->keyBy('key');

        $agendas = Agenda::query()
            ->where('is_published', true)
            ->orderBy('start_at')
            ->orderBy('title')
            ->get();

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

        return view('frontend.pages.agendas.index', compact(
            'schoolProfile',
            'siteSettings',
            'agendas',
            'contacts',
            'socialLinks',
        ));
    }

    /**
     * Display one published school agenda.
     */
    public function show(Agenda $agenda): View
    {
        $schoolProfile = SchoolProfile::query()->latest('id')->first();

        $siteSettings = SiteSetting::query()
            ->where('is_public', true)
            ->orderBy('key')
            ->get()
            ->keyBy('key');

        $agenda = Agenda::query()
            ->whereKey($agenda->getKey())
            ->where('is_published', true)
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

        return view('frontend.pages.agendas.show', compact(
            'schoolProfile',
            'siteSettings',
            'agenda',
            'contacts',
            'socialLinks',
        ));
    }
}
