@extends('frontend.layouts.app')

@php
    $siteName = $siteSettings->get('site_name')?->value ?? $schoolProfile?->name ?? "MI Islamiyah Syafi'iyah";
    $metaDescription = $agenda->description ?: 'Informasi agenda ' . $agenda->title . ' dari ' . $siteName . '.';
@endphp

@section('title', $agenda->title . ' | Agenda | ' . $siteName)
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($metaDescription), 160))
@section('og_type', 'article')
@if ($agenda->cover_image_path)
    @section('meta_image', asset('storage/' . $agenda->cover_image_path))
@endif

@section('content')
    <article class="bg-stone-50 pb-20 sm:pb-24">
        <header class="miis-page-hero relative isolate overflow-hidden bg-emerald-950 py-20 sm:py-24">
            @if ($agenda->cover_image_path)
                <img src="{{ asset('storage/' . $agenda->cover_image_path) }}" alt="{{ $agenda->title }}" class="absolute inset-0 -z-20 h-full w-full object-cover" fetchpriority="high">
                <div class="absolute inset-0 -z-10 bg-emerald-950/85"></div>
            @else
                <div class="miis-page-hero-overlay absolute inset-0 -z-10" aria-hidden="true"></div>
            @endif

            <div class="mx-auto max-w-4xl px-5 sm:px-6 lg:px-8">
                <a href="{{ route('agenda') }}" class="inline-flex items-center gap-2 text-sm font-bold text-emerald-100 transition hover:text-amber-200"><span aria-hidden="true">&larr;</span> Semua Agenda</a>
                <p class="mt-9 text-sm font-bold uppercase tracking-[0.16em] text-amber-300">Agenda Sekolah</p>
                <h1 class="mt-5 text-4xl font-extrabold leading-tight tracking-tight text-white sm:text-5xl">{{ $agenda->title }}</h1>
                <p class="mt-6 text-sm font-semibold text-emerald-100/85">{{ $agenda->start_at->translatedFormat('d F Y, H:i') }}</p>
            </div>
        </header>

        <div class="mx-auto max-w-4xl px-5 pt-12 sm:px-6 sm:pt-16 lg:px-8">
            @if ($agenda->cover_image_path)
                <img src="{{ asset('storage/' . $agenda->cover_image_path) }}" alt="{{ $agenda->title }}" class="aspect-[16/9] w-full rounded-3xl object-cover shadow-xl shadow-emerald-950/10" loading="lazy">
            @endif

            <dl class="mt-10 grid gap-4 sm:grid-cols-2">
                <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm">
                    <dt class="text-xs font-bold uppercase tracking-[0.14em] text-emerald-700">Waktu Mulai</dt>
                    <dd class="mt-2 text-lg font-extrabold text-emerald-950">{{ $agenda->start_at->translatedFormat('d F Y, H:i') }}</dd>
                </div>
                @if ($agenda->end_at)
                    <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm">
                        <dt class="text-xs font-bold uppercase tracking-[0.14em] text-emerald-700">Waktu Selesai</dt>
                        <dd class="mt-2 text-lg font-extrabold text-emerald-950">{{ $agenda->end_at->translatedFormat('d F Y, H:i') }}</dd>
                    </div>
                @endif
                @if ($agenda->location)
                    <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm sm:col-span-2">
                        <dt class="text-xs font-bold uppercase tracking-[0.14em] text-emerald-700">Lokasi</dt>
                        <dd class="mt-2 text-lg font-extrabold text-emerald-950">{{ $agenda->location }}</dd>
                    </div>
                @endif
            </dl>

            @if ($agenda->description)
                <div class="mt-10 whitespace-pre-line text-base leading-8 text-slate-700">{!! nl2br(e($agenda->description)) !!}</div>
            @endif

            @if ($agenda->registration_url)
                <a href="{{ $agenda->registration_url }}" target="_blank" rel="noopener noreferrer" class="mt-10 inline-flex items-center gap-2 rounded-full bg-emerald-700 px-6 py-3.5 text-sm font-extrabold text-white shadow-lg shadow-emerald-900/15 transition hover:bg-emerald-800">Info Pendaftaran <span aria-hidden="true">&rarr;</span></a>
            @endif
        </div>
    </article>
@endsection
