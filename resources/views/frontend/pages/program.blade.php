@extends('frontend.layouts.app')

@php
    $siteName = $siteSettings->get('site_name')?->value ?? $schoolProfile?->name ?? "MI Islamiyah Syafi'iyah";
    $tagline = $siteSettings->get('site_tagline')?->value ?? $schoolProfile?->tagline ?? 'Madrasah Unggul Berkarakter Islami';
@endphp

@section('title', 'Program Unggulan | ' . $siteName)
@section('meta_description', 'Program unggulan ' . $siteName . ' untuk mengembangkan ilmu, karakter Islami, dan potensi peserta didik.')

@section('content')
    <section class="miis-page-hero relative isolate overflow-hidden bg-emerald-950 py-20 sm:py-24">
        <div class="miis-page-hero-overlay absolute inset-0 -z-10" aria-hidden="true"></div>
        <div class="mx-auto max-w-7xl px-5 sm:px-6 lg:px-8">
            <p class="text-sm font-bold uppercase tracking-[0.18em] text-amber-300">Program Unggulan</p>
            <h1 class="mt-5 max-w-4xl text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">Membuka ruang bagi setiap potensi untuk bertumbuh.</h1>
            <p class="mt-6 max-w-3xl text-base leading-8 text-emerald-50/85 sm:text-lg">{{ $tagline }} Program-program kami dirancang untuk memperkuat ilmu, karakter Islami, keterampilan, dan kegembiraan belajar anak.</p>
        </div>
    </section>

    <section class="bg-stone-50 py-20 sm:py-24">
        <div class="mx-auto max-w-7xl px-5 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <p class="text-sm font-bold uppercase tracking-[0.16em] text-emerald-700">Pilihan Program</p>
                <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-emerald-950 sm:text-4xl">Keunggulan yang hadir dalam setiap proses belajar.</h2>
            </div>

            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($programs as $program)
                    @php($iconLabel = \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($program->icon ?? $program->title, 0, 2)))
                    <article class="group overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200 transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-emerald-950/10">
                        <a href="{{ route('program.show', $program) }}" class="block focus:outline-none focus-visible:ring-4 focus-visible:ring-emerald-500/30" aria-label="Baca detail program {{ $program->title }}">
                            <div class="relative bg-emerald-100">
                                @if ($program->image_path)
                                    <img src="{{ asset('storage/' . $program->image_path) }}" alt="{{ $program->title }}" class="aspect-[16/10] w-full object-cover" loading="lazy">
                                @else
                                    <div class="flex aspect-[16/10] items-end bg-[linear-gradient(145deg,_#064e3b,_#059669_62%,_#fbbf24)] p-7">
                                        <p class="max-w-xs text-2xl font-extrabold leading-tight text-white">{{ $program->title }}</p>
                                    </div>
                                @endif
                                <span class="absolute bottom-4 left-4 flex h-12 min-w-12 items-center justify-center rounded-2xl bg-amber-300 px-3 text-sm font-extrabold text-emerald-950 shadow-lg" title="{{ $program->icon ?? $program->title }}">{{ $iconLabel }}</span>
                            </div>
                            <div class="p-7">
                                <p class="text-xs font-bold uppercase tracking-[0.14em] text-emerald-700">Program MIIS</p>
                                <h3 class="mt-3 text-2xl font-extrabold tracking-tight text-emerald-950 transition group-hover:text-emerald-700">{{ $program->title }}</h3>
                                <p class="mt-4 text-sm leading-7 text-slate-600">{{ \Illuminate\Support\Str::limit(strip_tags($program->short_description ?? $program->description), 180) }}</p>
                                @if ($program->icon)
                                    <p class="mt-5 text-xs font-semibold text-slate-400">Ikon: {{ $program->icon }}</p>
                                @endif
                                <span class="mt-5 inline-flex items-center gap-2 text-sm font-extrabold text-emerald-700">Lihat detail program <span aria-hidden="true">&rarr;</span></span>
                            </div>
                        </a>
                    </article>
                @empty
                    <div class="rounded-3xl border border-dashed border-emerald-200 bg-white p-8 text-sm leading-7 text-slate-500 sm:col-span-2 lg:col-span-3">Program unggulan akan tampil di sini setelah dikelola dan diaktifkan melalui CMS.</div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
