@extends('frontend.layouts.app')

@php
    $siteName = $siteSettings->get('site_name')?->value ?? $schoolProfile?->name ?? "MI Islamiyah Syafi'iyah";
    $tagline = $siteSettings->get('site_tagline')?->value ?? $schoolProfile?->tagline ?? 'Madrasah Unggul Berkarakter Islami';
@endphp

@section('title', 'Prestasi | ' . $siteName)
@section('meta_description', 'Prestasi siswa dan sekolah ' . $siteName . ' dalam perjalanan belajar yang berkarakter Islami.')

@section('content')
    <section class="miis-page-hero relative isolate overflow-hidden bg-emerald-950 py-20 sm:py-24">
        <div class="miis-page-hero-overlay absolute inset-0 -z-10" aria-hidden="true"></div>
        <div class="mx-auto max-w-7xl px-5 sm:px-6 lg:px-8">
            <p class="text-sm font-bold uppercase tracking-[0.18em] text-amber-300">Prestasi MIIS</p>
            <h1 class="mt-5 max-w-4xl text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">Tumbuh dengan ikhtiar, bersinar melalui prestasi.</h1>
            <p class="mt-6 max-w-3xl text-base leading-8 text-emerald-50/85 sm:text-lg">{{ $tagline }} Kami mengapresiasi setiap pencapaian yang lahir dari ketekunan, kolaborasi, dan karakter mulia warga madrasah.</p>
        </div>
    </section>

    <section class="bg-stone-50 py-20 sm:py-24">
        <div class="mx-auto max-w-7xl px-5 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <p class="text-sm font-bold uppercase tracking-[0.16em] text-emerald-700">Pencapaian Sekolah</p>
                <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-emerald-950 sm:text-4xl">Kabar baik dari perjalanan belajar kami.</h2>
            </div>

            <div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($achievements as $achievement)
                    <article class="group overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200 transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-emerald-950/10">
                        <a href="{{ route('achievement.show', $achievement) }}" class="block focus:outline-none focus-visible:ring-4 focus-visible:ring-emerald-500/30" aria-label="Baca detail prestasi {{ $achievement->title }}">
                            <div class="relative bg-emerald-100">
                                @if ($achievement->image_path)
                                    <img src="{{ asset('storage/' . $achievement->image_path) }}" alt="{{ $achievement->title }}" class="aspect-[16/10] w-full object-cover" loading="lazy">
                                @else
                                    <div class="flex aspect-[16/10] items-end bg-[linear-gradient(145deg,_#064e3b,_#059669_62%,_#fbbf24)] p-7">
                                        <p class="max-w-xs text-2xl font-extrabold leading-tight text-white">{{ $achievement->title }}</p>
                                    </div>
                                @endif

                                @if ($achievement->level)
                                    <span class="absolute left-4 top-4 rounded-full bg-amber-300 px-3 py-1.5 text-xs font-extrabold text-emerald-950 shadow-sm">{{ $achievement->level }}</span>
                                @endif
                            </div>

                            <div class="p-7">
                                <p class="text-xs font-bold uppercase tracking-[0.14em] text-emerald-700">Prestasi</p>
                                <h3 class="mt-3 text-2xl font-extrabold tracking-tight text-emerald-950 transition group-hover:text-emerald-700">{{ $achievement->title }}</h3>

                                @if ($achievement->recipient_name)
                                    <p class="mt-3 text-sm font-semibold text-slate-700">{{ $achievement->recipient_name }}</p>
                                @endif

                                @if ($achievement->achievement_date)
                                    <p class="mt-2 text-sm text-slate-500">{{ $achievement->achievement_date->translatedFormat('d F Y') }}</p>
                                @endif

                                @if ($achievement->description)
                                    <p class="mt-4 text-sm leading-7 text-slate-600">{{ \Illuminate\Support\Str::limit(strip_tags($achievement->description), 220) }}</p>
                                @endif

                                <span class="mt-5 inline-flex items-center gap-2 text-sm font-extrabold text-emerald-700">Lihat detail prestasi <span aria-hidden="true">&rarr;</span></span>
                            </div>
                        </a>
                    </article>
                @empty
                    <div class="rounded-3xl border border-dashed border-emerald-200 bg-white p-8 text-sm leading-7 text-slate-500 md:col-span-2 lg:col-span-3">Prestasi yang telah diterbitkan akan tampil di sini.</div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
