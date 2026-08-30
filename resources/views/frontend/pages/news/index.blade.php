@extends('frontend.layouts.app')

@php
    $siteName = $siteSettings->get('site_name')?->value ?? $schoolProfile?->name ?? "MI Islamiyah Syafi'iyah";
    $tagline = $siteSettings->get('site_tagline')?->value ?? $schoolProfile?->tagline ?? 'Madrasah Unggul Berkarakter Islami';
@endphp

@section('title', 'Berita & Kegiatan | ' . $siteName)
@section('meta_description', 'Berita, kegiatan, dan informasi terbaru dari ' . $siteName . '.')

@section('content')
    <section class="miis-page-hero relative isolate overflow-hidden bg-emerald-950 py-20 sm:py-24">
        <div class="miis-page-hero-overlay absolute inset-0 -z-10" aria-hidden="true"></div>
        <div class="mx-auto max-w-7xl px-5 sm:px-6 lg:px-8">
            <p class="text-sm font-bold uppercase tracking-[0.18em] text-amber-300">Kabar Madrasah</p>
            <h1 class="mt-5 max-w-4xl text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">Berita, kegiatan, dan cerita baik dari madrasah.</h1>
            <p class="mt-6 max-w-3xl text-base leading-8 text-emerald-50/85 sm:text-lg">{{ $tagline }} Ikuti informasi terbaru dan perjalanan belajar MI Islamiyah Syafi'iyah.</p>
        </div>
    </section>

    <section class="bg-stone-50 py-20 sm:py-24">
        <div class="mx-auto max-w-7xl px-5 sm:px-6 lg:px-8">
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($posts as $post)
                    <article class="group overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200 transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-emerald-950/10">
                        <a href="{{ route('news.show', $post) }}" class="block bg-emerald-100">
                            @if ($post->featured_image_path)
                                <img src="{{ asset('storage/' . $post->featured_image_path) }}" alt="{{ $post->title }}" class="aspect-[16/10] w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
                            @else
                                <div class="flex aspect-[16/10] items-end bg-[linear-gradient(145deg,_#064e3b,_#059669_62%,_#fbbf24)] p-7">
                                    <span class="rounded-full bg-white/15 px-3 py-1.5 text-xs font-bold text-white backdrop-blur">{{ $post->category->name }}</span>
                                </div>
                            @endif
                        </a>
                        <div class="p-7">
                            <p class="text-xs font-bold uppercase tracking-[0.14em] text-emerald-700">{{ $post->category->name }} · {{ $post->published_at?->translatedFormat('d M Y') }}</p>
                            <h2 class="mt-3 text-2xl font-extrabold leading-snug tracking-tight text-emerald-950"><a href="{{ route('news.show', $post) }}" class="transition hover:text-emerald-700">{{ $post->title }}</a></h2>
                            <p class="mt-4 text-sm leading-7 text-slate-600">{{ \Illuminate\Support\Str::limit(strip_tags($post->excerpt ?? $post->content), 160) }}</p>
                            <a href="{{ route('news.show', $post) }}" class="mt-6 inline-flex items-center gap-2 text-sm font-extrabold text-emerald-700 transition hover:text-emerald-900">Baca selengkapnya <span aria-hidden="true">→</span></a>
                        </div>
                    </article>
                @empty
                    <div class="rounded-3xl border border-dashed border-emerald-200 bg-white p-8 text-sm leading-7 text-slate-500 sm:col-span-2 lg:col-span-3">Berita dan kegiatan yang telah diterbitkan melalui CMS akan tampil di sini.</div>
                @endforelse
            </div>

            @if ($posts->hasPages())
                <div class="mt-10">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
