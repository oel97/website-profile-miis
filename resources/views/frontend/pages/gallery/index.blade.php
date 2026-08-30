@extends('frontend.layouts.app')

@php
    $siteName = $siteSettings->get('site_name')?->value ?? $schoolProfile?->name ?? "MI Islamiyah Syafi'iyah";
    $tagline = $siteSettings->get('site_tagline')?->value ?? $schoolProfile?->tagline ?? 'Madrasah Unggul Berkarakter Islami';
@endphp

@section('title', 'Galeri | ' . $siteName)
@section('meta_description', 'Galeri dokumentasi kegiatan dan kebersamaan ' . $siteName . '.')

@section('content')
    <section class="miis-page-hero relative isolate overflow-hidden bg-emerald-950 py-20 sm:py-24">
        <div class="miis-page-hero-overlay absolute inset-0 -z-10" aria-hidden="true"></div>
        <div class="mx-auto max-w-7xl px-5 sm:px-6 lg:px-8">
            <p class="text-sm font-bold uppercase tracking-[0.18em] text-amber-300">Galeri MIIS</p>
            <h1 class="mt-5 max-w-4xl text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">Merekam momen, menguatkan kenangan.</h1>
            <p class="mt-6 max-w-3xl text-base leading-8 text-emerald-50/85 sm:text-lg">{{ $tagline }} Lihat kembali kegiatan dan kebersamaan yang menjadi bagian dari perjalanan madrasah kami.</p>
        </div>
    </section>

    <section class="bg-stone-50 py-20 sm:py-24">
        <div class="mx-auto max-w-7xl px-5 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <p class="text-sm font-bold uppercase tracking-[0.16em] text-emerald-700">Album Dokumentasi</p>
                <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-emerald-950 sm:text-4xl">Cerita madrasah dalam setiap bingkai.</h2>
            </div>

            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($albums as $album)
                    <a href="{{ route('gallery.show', $album) }}" class="group overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200 transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-emerald-950/10">
                        <div class="relative overflow-hidden bg-emerald-100">
                            @if ($album->cover_image_path)
                                <img src="{{ asset('storage/' . $album->cover_image_path) }}" alt="{{ $album->title }}" class="aspect-[16/10] w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
                            @else
                                <div class="flex aspect-[16/10] items-end bg-[linear-gradient(145deg,_#064e3b,_#059669_62%,_#fbbf24)] p-7">
                                    <p class="max-w-xs text-2xl font-extrabold leading-tight text-white">{{ $album->title }}</p>
                                </div>
                            @endif
                            <span class="absolute bottom-4 left-4 rounded-full bg-emerald-950/90 px-3 py-1.5 text-xs font-bold text-white backdrop-blur">{{ $album->photos_count }} foto</span>
                        </div>

                        <div class="p-7">
                            @if ($album->event_date)
                                <p class="text-xs font-bold uppercase tracking-[0.14em] text-emerald-700">{{ $album->event_date->translatedFormat('d F Y') }}</p>
                            @else
                                <p class="text-xs font-bold uppercase tracking-[0.14em] text-emerald-700">Dokumentasi MIIS</p>
                            @endif
                            <h2 class="mt-3 text-2xl font-extrabold tracking-tight text-emerald-950 transition group-hover:text-emerald-700">{{ $album->title }}</h2>
                            @if ($album->description)
                                <p class="mt-4 text-sm leading-7 text-slate-600">{{ \Illuminate\Support\Str::limit(strip_tags($album->description), 180) }}</p>
                            @endif
                            <span class="mt-5 inline-flex text-sm font-extrabold text-emerald-700">Lihat album &rarr;</span>
                        </div>
                    </a>
                @empty
                    <div class="rounded-3xl border border-dashed border-emerald-200 bg-white p-8 text-sm leading-7 text-slate-500 sm:col-span-2 lg:col-span-3">Album galeri yang telah diterbitkan akan tampil di sini.</div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
