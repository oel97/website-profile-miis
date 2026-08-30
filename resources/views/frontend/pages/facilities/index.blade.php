@extends('frontend.layouts.app')

@php
    $siteName = $siteSettings->get('site_name')?->value ?? $schoolProfile?->name ?? "MI Islamiyah Syafi'iyah";
    $tagline = $siteSettings->get('site_tagline')?->value ?? $schoolProfile?->tagline ?? 'Madrasah Unggul Berkarakter Islami';
@endphp

@section('title', 'Sarana dan Prasarana | ' . $siteName)
@section('meta_description', 'Sarana dan prasarana ' . $siteName . ' untuk mendukung pembelajaran yang nyaman dan bermakna.')

@section('content')
    <section class="miis-page-hero relative isolate overflow-hidden bg-emerald-950 py-20 sm:py-24">
        <div class="miis-page-hero-overlay absolute inset-0 -z-10" aria-hidden="true"></div>
        <div class="mx-auto max-w-7xl px-5 sm:px-6 lg:px-8">
            <p class="text-sm font-bold uppercase tracking-[0.18em] text-amber-300">Sarana &amp; Prasarana</p>
            <h1 class="mt-5 max-w-4xl text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">Lingkungan belajar yang nyaman untuk bertumbuh.</h1>
            <p class="mt-6 max-w-3xl text-base leading-8 text-emerald-50/85 sm:text-lg">{{ $tagline }} Kami menghadirkan fasilitas yang mendukung pembelajaran aktif, pembiasaan baik, dan pengalaman belajar yang menyenangkan.</p>
        </div>
    </section>

    <section class="bg-stone-50 py-20 sm:py-24">
        <div class="mx-auto max-w-7xl px-5 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <p class="text-sm font-bold uppercase tracking-[0.16em] text-emerald-700">Fasilitas Madrasah</p>
                <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-emerald-950 sm:text-4xl">Ruang yang Mendukung Pembelajaran.</h2>
            </div>

            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($facilities as $facility)
                    <article class="group overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200 transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-emerald-950/10">
                        <a href="{{ route('facility.show', $facility) }}" class="block focus:outline-none focus-visible:ring-4 focus-visible:ring-emerald-500/30" aria-label="Baca detail fasilitas {{ $facility->name }}">
                            <div class="relative bg-emerald-100">
                                @if ($facility->image_path)
                                    <img src="{{ asset('storage/' . $facility->image_path) }}" alt="{{ $facility->name }}" class="aspect-[16/10] w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
                                @else
                                    <div class="flex aspect-[16/10] items-end bg-[linear-gradient(145deg,_#064e3b,_#059669_62%,_#fbbf24)] p-7">
                                        <p class="max-w-xs text-2xl font-extrabold leading-tight text-white">{{ $facility->name }}</p>
                                    </div>
                                @endif

                                @if (! is_null($facility->quantity))
                                    <span class="absolute bottom-4 left-4 rounded-full bg-amber-300 px-3 py-1.5 text-xs font-extrabold text-emerald-950 shadow-sm">{{ $facility->quantity }}{{ $facility->unit ? ' ' . $facility->unit : '' }}</span>
                                @endif
                            </div>

                            <div class="p-7">
                                <p class="text-xs font-bold uppercase tracking-[0.14em] text-emerald-700">Fasilitas MIIS</p>
                                <h2 class="mt-3 text-2xl font-extrabold tracking-tight text-emerald-950 transition group-hover:text-emerald-700">{{ $facility->name }}</h2>
                                @if ($facility->description)
                                    <p class="mt-4 text-sm leading-7 text-slate-600">{{ \Illuminate\Support\Str::limit(strip_tags($facility->description), 220) }}</p>
                                @endif
                                @if (! is_null($facility->quantity))
                                    <p class="mt-5 text-sm font-bold text-emerald-700">Jumlah: {{ $facility->quantity }}{{ $facility->unit ? ' ' . $facility->unit : '' }}</p>
                                @endif
                                <span class="mt-5 inline-flex items-center gap-2 text-sm font-extrabold text-emerald-700">Lihat detail fasilitas <span aria-hidden="true">&rarr;</span></span>
                            </div>
                        </a>
                    </article>
                @empty
                    <div class="rounded-3xl border border-dashed border-emerald-200 bg-white p-8 text-sm leading-7 text-slate-500 sm:col-span-2 lg:col-span-3">Informasi fasilitas yang telah diterbitkan akan tampil di sini.</div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
