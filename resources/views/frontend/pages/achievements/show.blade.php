@extends('frontend.layouts.app')

@php
    $siteName = $siteSettings->get('site_name')?->value ?? $schoolProfile?->name ?? "MI Islamiyah Syafi'iyah";
    $metaDescription = $achievement->description ?: 'Informasi prestasi ' . $achievement->title . ' dari ' . $siteName . '.';
@endphp

@section('title', $achievement->title . ' | Prestasi | ' . $siteName)
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($metaDescription), 160))
@section('og_type', 'article')
@if ($achievement->image_path)
    @section('meta_image', asset('storage/' . $achievement->image_path))
@endif

@section('content')
    <article class="bg-stone-50 pb-20 sm:pb-24">
        <header class="miis-page-hero relative isolate overflow-hidden bg-emerald-950 py-20 sm:py-24">
            @if ($achievement->image_path)
                <img src="{{ asset('storage/' . $achievement->image_path) }}" alt="{{ $achievement->title }}" class="absolute inset-0 -z-20 h-full w-full object-cover" fetchpriority="high">
                <div class="absolute inset-0 -z-10 bg-emerald-950/85"></div>
            @else
                <div class="miis-page-hero-overlay absolute inset-0 -z-10" aria-hidden="true"></div>
            @endif

            <div class="mx-auto max-w-4xl px-5 sm:px-6 lg:px-8">
                <a href="{{ route('achievement') }}" class="inline-flex items-center gap-2 text-sm font-bold text-emerald-100 transition hover:text-amber-200"><span aria-hidden="true">&larr;</span> Semua Prestasi</a>
                <p class="mt-9 text-sm font-bold uppercase tracking-[0.16em] text-amber-300">{{ $achievement->level ?: 'Prestasi MIIS' }}</p>
                <h1 class="mt-5 text-4xl font-extrabold leading-tight tracking-tight text-white sm:text-5xl">{{ $achievement->title }}</h1>

                <div class="mt-6 flex flex-wrap gap-x-5 gap-y-2 text-sm font-semibold text-emerald-100/85">
                    @if ($achievement->recipient_name)
                        <span>{{ $achievement->recipient_name }}</span>
                    @endif
                    @if ($achievement->achievement_date)
                        <span>{{ $achievement->achievement_date->translatedFormat('d F Y') }}</span>
                    @endif
                </div>
            </div>
        </header>

        <div class="mx-auto max-w-4xl px-5 pt-12 sm:px-6 sm:pt-16 lg:px-8">
            @if ($achievement->description)
                <div class="whitespace-pre-line text-base leading-8 text-slate-700">{!! nl2br(e($achievement->description)) !!}</div>
            @endif

            <dl class="mt-10 grid gap-4 sm:grid-cols-2">
                @if ($achievement->level)
                    <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm">
                        <dt class="text-xs font-bold uppercase tracking-[0.14em] text-emerald-700">Tingkat Prestasi</dt>
                        <dd class="mt-2 text-lg font-extrabold text-emerald-950">{{ $achievement->level }}</dd>
                    </div>
                @endif
                @if ($achievement->recipient_name)
                    <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm">
                        <dt class="text-xs font-bold uppercase tracking-[0.14em] text-emerald-700">Penerima Prestasi</dt>
                        <dd class="mt-2 text-lg font-extrabold text-emerald-950">{{ $achievement->recipient_name }}</dd>
                    </div>
                @endif
                @if ($achievement->organizer)
                    <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm">
                        <dt class="text-xs font-bold uppercase tracking-[0.14em] text-emerald-700">Penyelenggara</dt>
                        <dd class="mt-2 text-lg font-extrabold text-emerald-950">{{ $achievement->organizer }}</dd>
                    </div>
                @endif
                @if ($achievement->achievement_date)
                    <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm">
                        <dt class="text-xs font-bold uppercase tracking-[0.14em] text-emerald-700">Tanggal Perolehan</dt>
                        <dd class="mt-2 text-lg font-extrabold text-emerald-950">{{ $achievement->achievement_date->translatedFormat('d F Y') }}</dd>
                    </div>
                @endif
            </dl>

            @if ($achievement->certificate_path)
                <a href="{{ asset('storage/' . $achievement->certificate_path) }}" target="_blank" rel="noopener noreferrer" class="mt-10 inline-flex items-center gap-2 rounded-full bg-emerald-700 px-6 py-3.5 text-sm font-extrabold text-white shadow-lg shadow-emerald-900/15 transition hover:bg-emerald-800">Lihat Sertifikat <span aria-hidden="true">&rarr;</span></a>
            @endif
        </div>
    </article>
@endsection
