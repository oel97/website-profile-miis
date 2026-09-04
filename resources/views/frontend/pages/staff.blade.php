@extends('frontend.layouts.app')

@php
    $siteName = $siteSettings->get('site_name')?->value ?? $schoolProfile?->name ?? "MI Islamiyah Syafi'iyah";
    $tagline = $siteSettings->get('site_tagline')?->value ?? $schoolProfile?->tagline ?? 'Madrasah Unggul Berkarakter Islami';
    $staffGroups = [
        [
            'title' => 'Guru',
            'subtitle' => 'Para pendidik yang mendampingi tumbuhnya ilmu, adab, dan kepercayaan diri peserta didik.',
            'members' => $teachers,
            'accent' => 'emerald',
        ],
        [
            'title' => 'Tenaga Kependidikan',
            'subtitle' => 'Tim yang mendukung setiap layanan pendidikan agar berjalan hangat, tertata, dan bermakna.',
            'members' => $educationStaff,
            'accent' => 'amber',
        ],
    ];
@endphp

@section('title', 'Guru & Tenaga Kependidikan | ' . $siteName)
@section('meta_description', 'Guru dan tenaga kependidikan ' . $siteName . '. ' . $tagline)

@section('content')
    <section class="miis-page-hero relative isolate overflow-hidden bg-emerald-950 py-20 sm:py-24">
        <div class="miis-page-hero-overlay absolute inset-0 -z-10" aria-hidden="true"></div>
        <div class="mx-auto max-w-7xl px-5 sm:px-6 lg:px-8">
            <p class="text-sm font-bold uppercase tracking-[0.18em] text-amber-300">Pendidik & Tenaga Kependidikan</p>
            <h1 class="mt-5 max-w-4xl text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">Bertumbuh bersama para pendamping terbaik.</h1>
            <p class="mt-6 max-w-3xl text-base leading-8 text-emerald-50/85 sm:text-lg">{{ $tagline }} Setiap anggota madrasah hadir dengan dedikasi untuk menciptakan pengalaman belajar yang berilmu dan berakhlak.</p>
        </div>
    </section>

    <section class="bg-stone-50 py-20 sm:py-24">
        <div class="mx-auto max-w-7xl space-y-20 px-5 sm:px-6 lg:px-8">
            @foreach ($staffGroups as $group)
                <div>
                    <div class="max-w-3xl">
                        <p @class([
                            'text-sm font-bold uppercase tracking-[0.16em]',
                            'text-emerald-700' => $group['accent'] === 'emerald',
                            'text-amber-700' => $group['accent'] === 'amber',
                        ])>{{ $group['title'] }}</p>
                        <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-emerald-950 sm:text-4xl">{{ $group['title'] }} MI Islamiyah Syafi'iyah</h2>
                        <p class="mt-4 text-base leading-8 text-slate-600">{{ $group['subtitle'] }}</p>
                    </div>

                    <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                        @forelse ($group['members'] as $staff)
                            @php($hasPhoto = $staff->hasPhoto())
                            <article class="group overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200 transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-emerald-950/10">
                                <a href="{{ route('staff.show', $staff) }}" class="block focus:outline-none focus-visible:ring-4 focus-visible:ring-emerald-500/30" aria-label="Lihat profil {{ $staff->name }}">
                                    <div class="relative bg-emerald-100">
                                        @if ($hasPhoto)
                                            <img src="{{ asset('storage/' . $staff->photo_path) }}" alt="Foto {{ $staff->name }}" class="aspect-[4/5] w-full object-cover" loading="lazy">
                                        @else
                                            <div @class([
                                                'flex aspect-[4/5] items-end p-6',
                                                'bg-[linear-gradient(145deg,_#064e3b,_#059669_65%,_#fbbf24)]' => $group['accent'] === 'emerald',
                                                'bg-[linear-gradient(145deg,_#78350f,_#d97706_62%,_#fef3c7)]' => $group['accent'] === 'amber',
                                            ])>
                                                <span class="text-5xl font-extrabold text-white">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($staff->name, 0, 2)) }}</span>
                                            </div>
                                        @endif
                                        <span @class([
                                            'absolute left-4 top-4 rounded-full px-3 py-1.5 text-xs font-extrabold shadow-sm',
                                            'bg-emerald-700 text-white' => $group['accent'] === 'emerald',
                                            'bg-amber-300 text-amber-950' => $group['accent'] === 'amber',
                                        ])>{{ $group['title'] }}</span>
                                    </div>
                                    <div class="p-6">
                                        <h3 class="text-xl font-extrabold leading-snug text-emerald-950 transition group-hover:text-emerald-700">{{ $staff->name }}</h3>
                                        <p class="mt-2 text-sm font-semibold text-emerald-700">{{ $staff->position }}</p>
                                        @if ($staff->education)
                                            <p class="mt-4 text-sm text-slate-600"><span class="font-semibold text-slate-700">Pendidikan:</span> {{ $staff->education }}</p>
                                        @endif
                                        <p class="mt-2 text-xs font-bold uppercase tracking-wider text-slate-400">{{ $staff->employment_type }}</p>
                                        <span class="mt-5 inline-flex items-center gap-2 text-sm font-extrabold text-emerald-700">Lihat profil <span aria-hidden="true">&rarr;</span></span>
                                    </div>
                                </a>
                            </article>
                        @empty
                            <div class="rounded-3xl border border-dashed border-emerald-200 bg-white p-8 text-sm leading-7 text-slate-500 sm:col-span-2 lg:col-span-3 xl:col-span-4">Data {{ strtolower($group['title']) }} akan tampil di sini setelah dikelola dan diaktifkan melalui CMS.</div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection
