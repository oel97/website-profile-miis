@extends('frontend.layouts.app')

@php
    $siteName = $siteSettings->get('site_name')?->value ?? $schoolProfile?->name ?? "MI Islamiyah Syafi'iyah";
    $tagline = $siteSettings->get('site_tagline')?->value ?? $schoolProfile?->tagline ?? 'Madrasah Unggul Berkarakter Islami';
    $profileImage = $schoolProfile?->foto_sekolah_path ? asset('storage/' . $schoolProfile->foto_sekolah_path) : null;
    $historyImage = $historyPage?->cover_image_path ? asset('storage/' . $historyPage->cover_image_path) : null;
    $principalPhoto = $principalMessage?->photo_path ? asset('storage/' . $principalMessage->photo_path) : null;
@endphp

@section('title', 'Profil Sekolah | ' . $siteName)
@section('meta_description', \Illuminate\Support\Str::limit($schoolProfile?->short_description ?? $tagline, 160))
@if ($profileImage)
    @section('meta_image', $profileImage)
@endif

@section('content')
    <section class="miis-page-hero relative isolate overflow-hidden bg-emerald-950 py-20 sm:py-24">
        @if ($profileImage)
            <img src="{{ $profileImage }}" alt="{{ $siteName }}" class="absolute inset-0 -z-20 h-full w-full object-cover" fetchpriority="high">
            <div class="absolute inset-0 -z-10 bg-emerald-950/85"></div>
        @else
            <div class="miis-page-hero-overlay absolute inset-0 -z-10" aria-hidden="true"></div>
        @endif

        <div class="mx-auto max-w-7xl px-5 sm:px-6 lg:px-8">
            <p class="text-sm font-bold uppercase tracking-[0.18em] text-amber-300">Profil Sekolah</p>
            <h1 class="mt-5 max-w-4xl text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">Mengenal {{ $siteName }}</h1>
            <p class="mt-6 max-w-3xl text-base leading-8 text-emerald-50/85 sm:text-lg">{{ $tagline }}</p>
            <div class="mt-10 flex flex-wrap gap-3 text-sm font-bold text-emerald-50">
                <a href="#tentang" class="rounded-full border border-white/25 px-5 py-3 transition hover:bg-white/10">Tentang Madrasah</a>
                <a href="#sejarah" class="rounded-full border border-white/25 px-5 py-3 transition hover:bg-white/10">Sejarah</a>
                <a href="#visi-misi" class="rounded-full border border-white/25 px-5 py-3 transition hover:bg-white/10">Visi & Misi</a>
            </div>
        </div>
    </section>

    <section id="tentang" class="scroll-mt-24 bg-white py-20 sm:py-24">
        <div class="mx-auto grid max-w-7xl gap-12 px-5 sm:px-6 lg:grid-cols-[0.9fr_1.1fr] lg:items-center lg:px-8">
            <div class="rounded-[2rem] bg-emerald-100 p-8 sm:p-10">
                <p class="text-sm font-bold uppercase tracking-[0.16em] text-emerald-700">Identitas Madrasah</p>
                <dl class="mt-7 space-y-5">
                    <div class="border-b border-emerald-200 pb-5">
                        <dt class="text-xs font-bold uppercase tracking-wider text-emerald-700">Nama Sekolah</dt>
                        <dd class="mt-2 text-lg font-extrabold text-emerald-950">{{ $schoolProfile?->name ?? "MI Islamiyah Syafi'iyah" }}</dd>
                    </div>
                    @if ($schoolProfile?->npsn)
                        <div class="border-b border-emerald-200 pb-5">
                            <dt class="text-xs font-bold uppercase tracking-wider text-emerald-700">NPSN</dt>
                            <dd class="mt-2 font-semibold text-emerald-950">{{ $schoolProfile->npsn }}</dd>
                        </div>
                    @endif
                    @if ($schoolProfile?->accreditation)
                        <div>
                            <dt class="text-xs font-bold uppercase tracking-wider text-emerald-700">Akreditasi</dt>
                            <dd class="mt-2 font-semibold text-emerald-950">{{ $schoolProfile->accreditation }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            <div>
                <p class="text-sm font-bold uppercase tracking-[0.16em] text-emerald-700">Tentang Kami</p>
                <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-emerald-950 sm:text-4xl">Pendidikan dasar yang menghidupkan ilmu dan akhlak.</h2>
                <div class="mt-6 whitespace-pre-line text-base leading-8 text-slate-600">{!! nl2br(e($schoolProfile?->about ?? $schoolProfile?->short_description ?? 'Informasi profil MI Islamiyah Syafi\'iyah akan ditampilkan setelah dilengkapi melalui CMS.')) !!}</div>
                @if ($schoolProfile?->address)
                    <p class="mt-7 inline-flex rounded-2xl bg-amber-50 px-4 py-3 text-sm font-semibold text-emerald-900">{{ $schoolProfile->address }}</p>
                @endif
            </div>
        </div>
    </section>

    <section id="sejarah" class="scroll-mt-24 bg-stone-100 py-20 sm:py-24">
        <div class="mx-auto grid max-w-7xl gap-12 px-5 sm:px-6 lg:grid-cols-2 lg:items-center lg:px-8">
            <div class="order-2 lg:order-1">
                <p class="text-sm font-bold uppercase tracking-[0.16em] text-emerald-700">Jejak Perjalanan</p>
                <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-emerald-950 sm:text-4xl">{{ $historyPage?->title ?? 'Sejarah Madrasah' }}</h2>
                <div class="mt-6 whitespace-pre-line text-base leading-8 text-slate-600">{!! nl2br(e($historyPage?->content ?? $historyPage?->excerpt ?? 'Kisah perjalanan dan perkembangan madrasah akan ditampilkan setelah dikelola melalui CMS.')) !!}</div>
            </div>
            <div class="order-1 overflow-hidden rounded-[2rem] shadow-xl shadow-emerald-950/10 lg:order-2">
                @if ($historyImage)
                    <img src="{{ $historyImage }}" alt="{{ $historyPage->title }}" class="aspect-[4/3] w-full object-cover" loading="lazy">
                @else
                    <div class="flex aspect-[4/3] items-end bg-[linear-gradient(145deg,_#065f46,_#10b981_58%,_#fbbf24)] p-8 sm:p-10">
                        <p class="max-w-sm text-3xl font-extrabold leading-tight text-white">Sebuah ikhtiar untuk melahirkan generasi berkarakter.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section id="visi-misi" class="scroll-mt-24 bg-emerald-950 py-20 text-white sm:py-24">
        <div class="mx-auto max-w-7xl px-5 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <p class="text-sm font-bold uppercase tracking-[0.16em] text-amber-300">Arah Pendidikan</p>
                <h2 class="mt-4 text-3xl font-extrabold tracking-tight sm:text-4xl">{{ $visionMissionPage?->title ?? 'Visi dan Misi' }}</h2>
            </div>
            <div class="mt-10 rounded-[2rem] border border-emerald-800 bg-emerald-900/50 p-7 shadow-xl shadow-black/10 sm:p-10">
                <div class="whitespace-pre-line text-base leading-8 text-emerald-50/90">{!! nl2br(e($visionMissionPage?->content ?? $visionMissionPage?->excerpt ?? 'Visi dan misi madrasah akan ditampilkan setelah dilengkapi melalui CMS.')) !!}</div>
            </div>
        </div>
    </section>

    <section class="bg-white py-20 sm:py-24">
        <div class="mx-auto max-w-7xl px-5 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <p class="text-sm font-bold uppercase tracking-[0.16em] text-emerald-700">Sambutan Kepala Madrasah</p>
                <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-emerald-950 sm:text-4xl">{{ $principalMessage?->title ?? 'Pesan dan Harapan' }}</h2>
            </div>

            @if ($principalMessage)
                <article class="mt-10 grid overflow-hidden rounded-[2rem] border border-slate-200 bg-stone-50 lg:grid-cols-[0.75fr_1.25fr]">
                    <div class="relative min-h-80 bg-emerald-100">
                        @if ($principalPhoto)
                            <img src="{{ $principalPhoto }}" alt="{{ $principalMessage->name }}" class="absolute inset-0 h-full w-full object-cover" loading="lazy">
                        @else
                            <div class="absolute inset-0 flex items-end bg-[linear-gradient(145deg,_#064e3b,_#059669_65%,_#fbbf24)] p-8">
                                <span class="text-5xl font-extrabold text-white">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($principalMessage->name, 0, 2)) }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="p-7 sm:p-10">
                        <div class="whitespace-pre-line text-base leading-8 text-slate-600">{!! nl2br(e($principalMessage->message)) !!}</div>
                        <div class="mt-8 border-t border-slate-200 pt-5">
                            <p class="font-extrabold text-emerald-950">{{ $principalMessage->name }}</p>
                            <p class="mt-1 text-sm text-emerald-700">{{ $principalMessage->position }}</p>
                        </div>
                    </div>
                </article>
            @else
                <div class="mt-10 rounded-[2rem] border border-dashed border-emerald-200 bg-emerald-50 p-8 text-sm leading-7 text-emerald-800">Sambutan Kepala Madrasah akan ditampilkan setelah dikelola melalui CMS.</div>
            @endif
        </div>
    </section>
@endsection
