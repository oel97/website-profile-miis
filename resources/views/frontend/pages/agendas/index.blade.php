@extends('frontend.layouts.app')

@php
    $siteName = $siteSettings->get('site_name')?->value ?? $schoolProfile?->name ?? "MI Islamiyah Syafi'iyah";
    $tagline = $siteSettings->get('site_tagline')?->value ?? $schoolProfile?->tagline ?? 'Madrasah Unggul Berkarakter Islami';
@endphp

@section('title', 'Agenda Sekolah | ' . $siteName)
@section('meta_description', 'Agenda kegiatan dan jadwal terbaru ' . $siteName . '.')

@section('content')
    <section class="miis-page-hero relative isolate overflow-hidden bg-emerald-950 py-20 sm:py-24">
        <div class="miis-page-hero-overlay absolute inset-0 -z-10" aria-hidden="true"></div>
        <div class="mx-auto max-w-7xl px-5 sm:px-6 lg:px-8">
            <p class="text-sm font-bold uppercase tracking-[0.18em] text-amber-300">Agenda Sekolah</p>
            <h1 class="mt-5 max-w-4xl text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">Temukan setiap momen bermakna di madrasah.</h1>
            <p class="mt-6 max-w-3xl text-base leading-8 text-emerald-50/85 sm:text-lg">{{ $tagline }} Ikuti jadwal kegiatan, pertemuan, dan program yang akan berlangsung di lingkungan MI Islamiyah Syafi'iyah.</p>
        </div>
    </section>

    <section class="bg-stone-50 py-20 sm:py-24">
        <div class="mx-auto max-w-7xl px-5 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <p class="text-sm font-bold uppercase tracking-[0.16em] text-emerald-700">Jadwal Kegiatan</p>
                <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-emerald-950 sm:text-4xl">Mari hadir dan bertumbuh bersama.</h2>
            </div>

            <div class="mt-10 space-y-6">
                @forelse ($agendas as $agenda)
                    <article class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200 transition duration-300 hover:shadow-xl hover:shadow-emerald-950/10 md:grid md:grid-cols-[minmax(13rem,0.8fr)_minmax(0,1.7fr)]">
                        <div class="relative bg-emerald-100">
                            @if ($agenda->cover_image_path)
                                <a href="{{ route('agenda.show', $agenda) }}" class="block focus:outline-none focus-visible:ring-4 focus-visible:ring-emerald-500/30" aria-label="Lihat detail agenda {{ $agenda->title }}">
                                    <img src="{{ asset('storage/' . $agenda->cover_image_path) }}" alt="{{ $agenda->title }}" class="h-full min-h-56 w-full object-cover" loading="lazy">
                                </a>
                            @else
                                <a href="{{ route('agenda.show', $agenda) }}" class="flex min-h-56 h-full flex-col justify-end bg-[linear-gradient(145deg,_#064e3b,_#059669_62%,_#fbbf24)] p-7 text-white focus:outline-none focus-visible:ring-4 focus-visible:ring-emerald-500/30" aria-label="Lihat detail agenda {{ $agenda->title }}">
                                    <span class="text-sm font-bold uppercase tracking-[0.16em] text-amber-200">{{ $agenda->start_at->translatedFormat('M Y') }}</span>
                                    <span class="mt-2 text-5xl font-extrabold">{{ $agenda->start_at->translatedFormat('d') }}</span>
                                </a>
                            @endif
                        </div>

                        <div class="p-7 sm:p-8">
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-sm font-semibold text-emerald-700">
                                <span>{{ $agenda->start_at->translatedFormat('d F Y, H:i') }}</span>
                                @if ($agenda->end_at)
                                    <span class="text-slate-400" aria-hidden="true">&bull;</span>
                                    <span>{{ $agenda->end_at->translatedFormat('d F Y, H:i') }}</span>
                                @endif
                            </div>

                            <h3 class="mt-4 text-2xl font-extrabold tracking-tight text-emerald-950 sm:text-3xl"><a href="{{ route('agenda.show', $agenda) }}" class="transition hover:text-emerald-700 focus:outline-none focus-visible:ring-4 focus-visible:ring-emerald-500/30">{{ $agenda->title }}</a></h3>

                            @if ($agenda->location)
                                <p class="mt-3 text-sm font-semibold text-slate-700">Lokasi: {{ $agenda->location }}</p>
                            @endif

                            @if ($agenda->description)
                                <p class="mt-4 text-sm leading-7 text-slate-600">{{ \Illuminate\Support\Str::limit(strip_tags($agenda->description), 280) }}</p>
                            @endif

                            @if ($agenda->registration_url)
                                <a href="{{ $agenda->registration_url }}" target="_blank" rel="noopener noreferrer" class="mt-6 inline-flex items-center rounded-full bg-emerald-700 px-5 py-3 text-sm font-bold text-white transition hover:bg-emerald-800">Info pendaftaran</a>
                            @endif
                            <a href="{{ route('agenda.show', $agenda) }}" class="mt-6 {{ $agenda->registration_url ? 'ml-3' : '' }} inline-flex items-center gap-2 text-sm font-extrabold text-emerald-700 transition hover:text-emerald-900">Lihat detail agenda <span aria-hidden="true">&rarr;</span></a>
                        </div>
                    </article>
                @empty
                    <div class="rounded-3xl border border-dashed border-emerald-200 bg-white p-8 text-sm leading-7 text-slate-500">Agenda yang telah diterbitkan akan tampil di sini.</div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
