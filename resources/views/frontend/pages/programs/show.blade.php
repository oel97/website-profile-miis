@extends('frontend.layouts.app')

@php
    $siteName = $siteSettings->get('site_name')?->value ?? $schoolProfile?->name ?? "MI Islamiyah Syafi'iyah";
    $metaDescription = $program->short_description ?: $program->description ?: 'Informasi program ' . $program->title . ' dari ' . $siteName . '.';
@endphp

@section('title', $program->title . ' | Program Unggulan | ' . $siteName)
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($metaDescription), 160))
@if ($program->image_path)
    @section('meta_image', asset('storage/' . $program->image_path))
@endif

@section('content')
    <article class="bg-stone-50 pb-20 sm:pb-24">
        <header class="miis-page-hero relative isolate overflow-hidden bg-emerald-950 py-20 sm:py-24">
            @if ($program->image_path)
                <img src="{{ asset('storage/' . $program->image_path) }}" alt="{{ $program->title }}" class="absolute inset-0 -z-20 h-full w-full object-cover" fetchpriority="high">
                <div class="absolute inset-0 -z-10 bg-emerald-950/85"></div>
            @else
                <div class="miis-page-hero-overlay absolute inset-0 -z-10" aria-hidden="true"></div>
            @endif

            <div class="mx-auto max-w-4xl px-5 sm:px-6 lg:px-8">
                <a href="{{ route('program') }}" class="inline-flex items-center gap-2 text-sm font-bold text-emerald-100 transition hover:text-amber-200"><span aria-hidden="true">&larr;</span> Semua Program</a>
                <p class="mt-9 text-sm font-bold uppercase tracking-[0.16em] text-amber-300">Program Unggulan MIIS</p>
                <h1 class="mt-5 text-4xl font-extrabold leading-tight tracking-tight text-white sm:text-5xl">{{ $program->title }}</h1>
                @if ($program->short_description)
                    <p class="mt-6 max-w-3xl text-lg leading-8 text-emerald-50/90">{{ $program->short_description }}</p>
                @endif
            </div>
        </header>

        <div class="mx-auto max-w-4xl px-5 pt-12 sm:px-6 sm:pt-16 lg:px-8">
            @if ($program->image_path)
                <img src="{{ asset('storage/' . $program->image_path) }}" alt="{{ $program->title }}" class="aspect-[16/9] w-full rounded-3xl object-cover shadow-xl shadow-emerald-950/10" loading="lazy">
            @endif

            @if ($program->description)
                <div class="mt-10 whitespace-pre-line text-base leading-8 text-slate-700">{!! nl2br(e($program->description)) !!}</div>
            @elseif ($program->short_description)
                <p class="mt-10 text-base leading-8 text-slate-700">{{ $program->short_description }}</p>
            @endif

            @if ($program->icon)
                <div class="mt-10 rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm">
                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-emerald-700">Identitas Program</p>
                    <p class="mt-2 text-lg font-extrabold text-emerald-950">{{ $program->icon }}</p>
                </div>
            @endif
        </div>
    </article>
@endsection
