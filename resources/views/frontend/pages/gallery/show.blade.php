@extends('frontend.layouts.app')

@php
    $siteName = $siteSettings->get('site_name')?->value ?? $schoolProfile?->name ?? "MI Islamiyah Syafi'iyah";
    $metaDescription = $album->description ?: 'Dokumentasi ' . $album->title . ' dari ' . $siteName;
@endphp

@section('title', $album->title . ' | Galeri | ' . $siteName)
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($metaDescription), 160))
@if ($album->cover_image_path)
    @section('meta_image', asset('storage/' . $album->cover_image_path))
@endif

@section('content')
    <article class="bg-stone-50 pb-20 sm:pb-24">
        <header class="miis-page-hero relative isolate overflow-hidden bg-emerald-950 py-20 sm:py-24">
            @if ($album->cover_image_path)
                <img src="{{ asset('storage/' . $album->cover_image_path) }}" alt="{{ $album->title }}" class="absolute inset-0 -z-20 h-full w-full object-cover" fetchpriority="high">
                <div class="absolute inset-0 -z-10 bg-emerald-950/85"></div>
            @else
                <div class="miis-page-hero-overlay absolute inset-0 -z-10" aria-hidden="true"></div>
            @endif

            <div class="mx-auto max-w-4xl px-5 sm:px-6 lg:px-8">
                <a href="{{ route('gallery') }}" class="inline-flex items-center gap-2 text-sm font-bold text-emerald-100 transition hover:text-amber-200"><span aria-hidden="true">&larr;</span> Semua Album</a>
                <p class="mt-9 text-sm font-bold uppercase tracking-[0.16em] text-amber-300">Galeri MIIS</p>
                <h1 class="mt-5 text-4xl font-extrabold leading-tight tracking-tight text-white sm:text-5xl">{{ $album->title }}</h1>
                <div class="mt-6 flex flex-wrap gap-x-4 gap-y-2 text-sm font-semibold text-emerald-100/85">
                    @if ($album->event_date)
                        <span>{{ $album->event_date->translatedFormat('d F Y') }}</span>
                    @endif
                    <span>{{ $album->photos->count() }} foto</span>
                </div>
            </div>
        </header>

        <div class="mx-auto max-w-7xl px-5 pt-12 sm:px-6 sm:pt-16 lg:px-8">
            @if ($album->description)
                <p class="mx-auto max-w-4xl border-l-4 border-amber-300 pl-5 text-lg font-medium leading-8 text-emerald-900 sm:text-xl">{{ $album->description }}</p>
            @endif

            <div class="mt-12 grid grid-cols-2 gap-4 sm:grid-cols-3 sm:gap-6 lg:grid-cols-4">
                @forelse ($album->photos as $photo)
                    <figure class="group relative aspect-square overflow-hidden rounded-2xl bg-emerald-100 shadow-sm ring-1 ring-slate-200">
                        <img src="{{ asset('storage/' . $photo->image_path) }}" alt="{{ $photo->alt_text ?: $photo->caption ?: $album->title }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-110" loading="lazy">
                        @if ($photo->caption)
                            <figcaption class="absolute inset-x-0 bottom-0 translate-y-full bg-gradient-to-t from-emerald-950/95 via-emerald-950/70 to-transparent px-4 pb-4 pt-12 text-sm font-semibold leading-6 text-white transition duration-300 group-hover:translate-y-0">{{ $photo->caption }}</figcaption>
                        @endif
                    </figure>
                @empty
                    <div class="col-span-2 rounded-3xl border border-dashed border-emerald-200 bg-white p-8 text-sm leading-7 text-slate-500 sm:col-span-3 lg:col-span-4">Foto dokumentasi untuk album ini akan segera ditampilkan.</div>
                @endforelse
            </div>
        </div>
    </article>
@endsection
