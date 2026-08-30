@extends('frontend.layouts.app')

@php
    $siteName = $siteSettings->get('site_name')?->value ?? $schoolProfile?->name ?? "MI Islamiyah Syafi'iyah";
    $tagline = $siteSettings->get('site_tagline')?->value ?? $schoolProfile?->tagline ?? 'Madrasah Unggul Berkarakter Islami';
@endphp

@section('title', 'PPDB | ' . $siteName)
@section('meta_description', 'Informasi Penerimaan Peserta Didik Baru ' . $siteName . '. ' . $tagline)

@section('content')
    <section class="miis-page-hero relative isolate overflow-hidden bg-emerald-950 py-20 sm:py-24">
        @if ($period?->banner_path)
            <img src="{{ asset('storage/' . $period->banner_path) }}" alt="{{ $period->title }}" class="absolute inset-0 -z-20 h-full w-full object-cover" fetchpriority="high">
            <div class="absolute inset-0 -z-10 bg-emerald-950/85"></div>
        @else
            <div class="miis-page-hero-overlay absolute inset-0 -z-10" aria-hidden="true"></div>
        @endif

        <div class="mx-auto max-w-7xl px-5 sm:px-6 lg:px-8">
            <p class="text-sm font-bold uppercase tracking-[0.18em] text-amber-300">PPDB MIIS</p>
            <h1 class="mt-5 max-w-4xl text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">Mulai perjalanan belajar yang bermakna.</h1>
            <p class="mt-6 max-w-3xl text-base leading-8 text-emerald-50/85 sm:text-lg">{{ $tagline }} Temukan informasi Penerimaan Peserta Didik Baru MI Islamiyah Syafi'iyah di halaman ini.</p>

            @if ($period)
                <div class="mt-8 flex flex-wrap gap-3">
                    @if ($period->registration_url)
                        <a href="{{ $period->registration_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center rounded-full bg-amber-300 px-5 py-3 text-sm font-extrabold text-emerald-950 transition hover:bg-amber-200">Daftar Sekarang</a>
                    @endif
                    @if ($whatsappUrl)
                        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center rounded-full border border-emerald-100/60 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-white/10">Tanya via WhatsApp</a>
                    @endif
                </div>
            @endif
        </div>
    </section>

    <section class="bg-stone-50 py-20 sm:py-24">
        <div class="mx-auto max-w-7xl px-5 sm:px-6 lg:px-8">
            @if ($period)
                <div class="grid gap-8 lg:grid-cols-[minmax(0,1.25fr)_minmax(19rem,0.75fr)] lg:items-start">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-[0.16em] text-emerald-700">Informasi Periode</p>
                        <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-emerald-950 sm:text-4xl">{{ $period->title }}</h2>
                        <p class="mt-3 text-lg font-semibold text-emerald-700">Tahun Ajaran {{ $period->academic_year }}</p>
                        @if ($period->description)
                            <div class="mt-6 whitespace-pre-line text-base leading-8 text-slate-600">{{ $period->description }}</div>
                        @endif
                    </div>

                    <aside class="rounded-3xl bg-emerald-950 p-7 text-white shadow-xl shadow-emerald-950/15 sm:p-8">
                        <p class="text-sm font-bold uppercase tracking-[0.14em] text-amber-300">Jadwal Penting</p>
                        <dl class="mt-6 space-y-5 text-sm">
                            @if ($period->registration_start_at)
                                <div>
                                    <dt class="font-semibold text-emerald-100/75">Pendaftaran dibuka</dt>
                                    <dd class="mt-1 text-base font-extrabold">{{ $period->registration_start_at->translatedFormat('d F Y, H:i') }}</dd>
                                </div>
                            @endif
                            @if ($period->registration_end_at)
                                <div>
                                    <dt class="font-semibold text-emerald-100/75">Pendaftaran ditutup</dt>
                                    <dd class="mt-1 text-base font-extrabold">{{ $period->registration_end_at->translatedFormat('d F Y, H:i') }}</dd>
                                </div>
                            @endif
                            @if ($period->announcement_at)
                                <div>
                                    <dt class="font-semibold text-emerald-100/75">Pengumuman</dt>
                                    <dd class="mt-1 text-base font-extrabold">{{ $period->announcement_at->translatedFormat('d F Y, H:i') }}</dd>
                                </div>
                            @endif
                        </dl>
                    </aside>
                </div>

                <div class="mt-20 grid gap-12 lg:grid-cols-2 lg:gap-16">
                    <section>
                        <p class="text-sm font-bold uppercase tracking-[0.16em] text-emerald-700">Persyaratan</p>
                        <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-emerald-950">Siapkan sebelum mendaftar.</h2>
                        <div class="mt-8 space-y-4">
                            @forelse ($period->requirements as $requirement)
                                <article class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                                    <h3 class="font-extrabold text-emerald-950">{{ $requirement->title }}</h3>
                                    @if ($requirement->description)
                                        <p class="mt-2 text-sm leading-6 text-slate-600">{{ $requirement->description }}</p>
                                    @endif
                                </article>
                            @empty
                                <p class="rounded-2xl border border-dashed border-emerald-200 bg-white p-5 text-sm leading-7 text-slate-500">Persyaratan akan segera diumumkan.</p>
                            @endforelse
                        </div>
                    </section>

                    <section>
                        <p class="text-sm font-bold uppercase tracking-[0.16em] text-emerald-700">Alur Pendaftaran</p>
                        <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-emerald-950">Daftar dengan langkah yang mudah.</h2>
                        <ol class="mt-8 space-y-5">
                            @forelse ($period->steps as $step)
                                <li class="flex gap-4">
                                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-amber-300 text-sm font-extrabold text-emerald-950">{{ $loop->iteration }}</span>
                                    <div class="pt-1">
                                        <h3 class="font-extrabold text-emerald-950">{{ $step->title }}</h3>
                                        @if ($step->description)
                                            <p class="mt-2 text-sm leading-6 text-slate-600">{{ $step->description }}</p>
                                        @endif
                                    </div>
                                </li>
                            @empty
                                <li class="rounded-2xl border border-dashed border-emerald-200 bg-white p-5 text-sm leading-7 text-slate-500">Langkah pendaftaran akan segera diumumkan.</li>
                            @endforelse
                        </ol>
                    </section>
                </div>

                @if ($period->registration_url || $whatsappUrl)
                    <section class="mt-20 rounded-3xl bg-emerald-700 px-7 py-10 text-center text-white shadow-xl shadow-emerald-950/10 sm:px-10">
                        <p class="text-sm font-bold uppercase tracking-[0.16em] text-amber-200">Pendaftaran Peserta Didik Baru</p>
                        <h2 class="mt-4 text-3xl font-extrabold tracking-tight sm:text-4xl">Siap menjadi bagian dari MIIS?</h2>
                        <div class="mt-7 flex flex-wrap justify-center gap-3">
                            @if ($period->registration_url)
                                <a href="{{ $period->registration_url }}" target="_blank" rel="noopener noreferrer" class="rounded-full bg-amber-300 px-5 py-3 text-sm font-extrabold text-emerald-950 transition hover:bg-amber-200">Daftar Sekarang</a>
                            @endif
                            @if ($whatsappUrl)
                                <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="rounded-full border border-white/60 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-white/10">Hubungi Panitia</a>
                            @endif
                        </div>
                    </section>
                @endif
            @else
                <div class="mx-auto max-w-3xl rounded-3xl border border-dashed border-emerald-200 bg-white p-8 text-center shadow-sm sm:p-12">
                    <p class="text-sm font-bold uppercase tracking-[0.16em] text-emerald-700">Informasi PPDB</p>
                    <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-emerald-950">Periode PPDB belum tersedia.</h2>
                    <p class="mt-4 text-base leading-8 text-slate-600">Informasi pendaftaran akan tampil setelah periode PPDB aktif dan diterbitkan melalui CMS.</p>
                </div>
            @endif
        </div>
    </section>
@endsection
