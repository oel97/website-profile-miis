@extends('frontend.layouts.app')

@php
    $siteName = $siteSettings->get('site_name')?->value ?? $schoolProfile?->name ?? "MI Islamiyah Syafi'iyah";
    $metaDescription = $staff->bio ?: $staff->position . ' di ' . $siteName . '.';
@endphp

@section('title', $staff->name . ' | Guru & Tendik | ' . $siteName)
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($metaDescription), 160))
@if ($staff->photo_path)
    @section('meta_image', asset('storage/' . $staff->photo_path))
@endif

@section('content')
    <article class="bg-stone-50 pb-20 sm:pb-24">
        <header class="miis-page-hero relative isolate overflow-hidden bg-emerald-950 py-20 sm:py-24">
            @if ($staff->photo_path)
                <img src="{{ asset('storage/' . $staff->photo_path) }}" alt="Foto {{ $staff->name }}" class="absolute inset-0 -z-20 h-full w-full object-cover" fetchpriority="high">
                <div class="absolute inset-0 -z-10 bg-emerald-950/85"></div>
            @else
                <div class="miis-page-hero-overlay absolute inset-0 -z-10" aria-hidden="true"></div>
            @endif

            <div class="mx-auto max-w-4xl px-5 sm:px-6 lg:px-8">
                <a href="{{ route('staff') }}" class="inline-flex items-center gap-2 text-sm font-bold text-emerald-100 transition hover:text-amber-200"><span aria-hidden="true">&larr;</span> Semua Guru &amp; Tendik</a>
                <p class="mt-9 text-sm font-bold uppercase tracking-[0.16em] text-amber-300">Pendidik &amp; Tenaga Kependidikan</p>
                <h1 class="mt-5 text-4xl font-extrabold leading-tight tracking-tight text-white sm:text-5xl">{{ $staff->name }}</h1>
                <p class="mt-6 text-lg font-semibold text-emerald-100/90">{{ $staff->position }}</p>
            </div>
        </header>

        <div class="mx-auto max-w-4xl px-5 pt-12 sm:px-6 sm:pt-16 lg:px-8">
            <div class="grid gap-10 md:grid-cols-[minmax(12rem,0.75fr)_minmax(0,1.25fr)]">
                <div>
                    @if ($staff->photo_path)
                        <img src="{{ asset('storage/' . $staff->photo_path) }}" alt="Foto {{ $staff->name }}" class="aspect-[4/5] w-full rounded-3xl object-cover shadow-xl shadow-emerald-950/10" loading="lazy">
                    @else
                        <div class="flex aspect-[4/5] items-end rounded-3xl bg-[linear-gradient(145deg,_#064e3b,_#059669_62%,_#fbbf24)] p-7 shadow-xl shadow-emerald-950/10">
                            <span class="text-6xl font-extrabold text-white">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($staff->name, 0, 2)) }}</span>
                        </div>
                    @endif
                </div>

                <div>
                    <dl class="grid gap-4 sm:grid-cols-2">
                        <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm sm:col-span-2">
                            <dt class="text-xs font-bold uppercase tracking-[0.14em] text-emerald-700">Jabatan</dt>
                            <dd class="mt-2 text-lg font-extrabold text-emerald-950">{{ $staff->position }}</dd>
                        </div>
                        @if ($staff->employment_type)
                            <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm">
                                <dt class="text-xs font-bold uppercase tracking-[0.14em] text-emerald-700">Status</dt>
                                <dd class="mt-2 text-lg font-extrabold text-emerald-950">{{ $staff->employment_type }}</dd>
                            </div>
                        @endif
                        @if ($staff->education)
                            <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm">
                                <dt class="text-xs font-bold uppercase tracking-[0.14em] text-emerald-700">Pendidikan</dt>
                                <dd class="mt-2 text-lg font-extrabold text-emerald-950">{{ $staff->education }}</dd>
                            </div>
                        @endif
                        @if ($staff->subject_or_duty)
                            <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm sm:col-span-2">
                                <dt class="text-xs font-bold uppercase tracking-[0.14em] text-emerald-700">Bidang Tugas</dt>
                                <dd class="mt-2 text-lg font-extrabold text-emerald-950">{{ $staff->subject_or_duty }}</dd>
                            </div>
                        @endif
                    </dl>

                    @if ($staff->bio)
                        <div class="mt-8 whitespace-pre-line text-base leading-8 text-slate-700">{!! nl2br(e($staff->bio)) !!}</div>
                    @endif
                </div>
            </div>
        </div>
    </article>
@endsection
