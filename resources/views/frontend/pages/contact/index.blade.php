@extends('frontend.layouts.app')

@php
    $siteName = $siteSettings->get('site_name')?->value ?? $schoolProfile?->name ?? "MI Islamiyah Syafi'iyah";
    $tagline = $siteSettings->get('site_tagline')?->value ?? $schoolProfile?->tagline ?? 'Madrasah Unggul Berkarakter Islami';
@endphp

@section('title', 'Kontak | ' . $siteName)
@section('meta_description', 'Kontak, lokasi Google Maps, dan sosial media resmi ' . $siteName . '.')

@section('content')
    <section class="miis-page-hero relative isolate overflow-hidden bg-emerald-950 py-20 sm:py-24">
        <div class="miis-page-hero-overlay absolute inset-0 -z-10" aria-hidden="true"></div>
        <div class="mx-auto max-w-7xl px-5 sm:px-6 lg:px-8">
            <p class="text-sm font-bold uppercase tracking-[0.18em] text-amber-300">Kontak MIIS</p>
            <h1 class="mt-5 max-w-4xl text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">Mari terhubung dengan madrasah kami.</h1>
            <p class="mt-6 max-w-3xl text-base leading-8 text-emerald-50/85 sm:text-lg">{{ $tagline }} Kami siap membantu Anda memperoleh informasi seputar sekolah, pembelajaran, dan penerimaan peserta didik baru.</p>
        </div>
    </section>

    <section class="bg-stone-50 py-20 sm:py-24">
        <div class="mx-auto max-w-7xl px-5 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <p class="text-sm font-bold uppercase tracking-[0.16em] text-emerald-700">Informasi Kontak</p>
                <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-emerald-950 sm:text-4xl">Kami senang mendengar dari Anda.</h2>
            </div>

            <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($contacts as $contact)
                    @php($contactUrl = $contact->publicUrl())
                    <article class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 transition duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-emerald-950/10">
                        <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-amber-300 text-sm font-extrabold text-emerald-950">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($contact->type, 0, 2)) }}</span>
                        <p class="mt-5 text-xs font-bold uppercase tracking-[0.14em] text-emerald-700">{{ $contact->label }}</p>
                        @if ($contactUrl)
                            <a href="{{ $contactUrl }}" class="mt-2 block break-words text-lg font-extrabold leading-7 text-emerald-950 transition hover:text-emerald-700" @if (str_starts_with($contactUrl, 'http')) target="_blank" rel="noopener noreferrer" @endif>{{ $contact->value }}</a>
                        @else
                            <p class="mt-2 break-words text-lg font-extrabold leading-7 text-emerald-950">{{ $contact->value }}</p>
                        @endif
                    </article>
                @empty
                    <article class="rounded-3xl bg-white p-7 shadow-sm ring-1 ring-slate-200 sm:col-span-2 lg:col-span-3">
                        <p class="text-sm leading-7 text-slate-600">Kontak resmi sekolah akan segera tersedia.</p>
                        @if ($schoolProfile?->email || $schoolProfile?->phone || $schoolProfile?->address)
                            <div class="mt-5 space-y-2 text-sm leading-7 text-slate-700">
                                @if ($schoolProfile?->email)<p><a href="mailto:{{ $schoolProfile->email }}" class="font-semibold text-emerald-800 transition hover:text-emerald-600">{{ $schoolProfile->email }}</a></p>@endif
                                @if ($schoolProfile?->phone)<p><a href="tel:{{ preg_replace('/\D+/', '', $schoolProfile->phone) }}" class="font-semibold text-emerald-800 transition hover:text-emerald-600">{{ $schoolProfile->phone }}</a></p>@endif
                                @if ($schoolProfile?->address)<p>{{ $schoolProfile->address }}</p>@endif
                            </div>
                        @endif
                    </article>
                @endforelse
            </div>

            <div class="mt-20 grid gap-10 lg:grid-cols-[minmax(0,1.25fr)_minmax(18rem,0.75fr)] lg:items-start">
                <section>
                    <p class="text-sm font-bold uppercase tracking-[0.16em] text-emerald-700">Lokasi Madrasah</p>
                    <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-emerald-950">Temukan kami di sini.</h2>
                    <div class="mt-8 overflow-hidden rounded-3xl bg-emerald-100 shadow-sm ring-1 ring-slate-200">
                        @if ($mapEmbedUrl)
                            <iframe src="{{ $mapEmbedUrl }}" title="Lokasi {{ $siteName }}" class="aspect-[16/10] w-full border-0" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                        @else
                            <div class="flex aspect-[16/10] items-center justify-center bg-[linear-gradient(145deg,_#064e3b,_#059669_62%,_#fbbf24)] p-8 text-center text-white">
                                <p class="max-w-sm text-base font-semibold leading-8">Peta lokasi akan tampil setelah URL Google Maps atau alamat sekolah dilengkapi melalui CMS.</p>
                            </div>
                        @endif
                    </div>
                </section>

                <aside class="rounded-3xl bg-emerald-950 p-7 text-white shadow-xl shadow-emerald-950/15 sm:p-8">
                    <p class="text-sm font-bold uppercase tracking-[0.16em] text-amber-300">Sosial Media</p>
                    <h2 class="mt-4 text-2xl font-extrabold tracking-tight">Ikuti kabar terbaru kami.</h2>
                    <div class="mt-7 space-y-3">
                        @forelse ($socialLinks as $socialLink)
                            <a href="{{ $socialLink->url }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-between gap-4 rounded-2xl border border-emerald-100/20 px-4 py-3 text-sm font-bold text-emerald-50 transition hover:border-amber-300 hover:bg-emerald-900 hover:text-amber-200">
                                <span>{{ $socialLink->label ?? $socialLink->platform }}</span>
                                <span aria-hidden="true">&rarr;</span>
                            </a>
                        @empty
                            <p class="text-sm leading-7 text-emerald-100/70">Akun sosial media sekolah akan segera tersedia.</p>
                        @endforelse
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection
