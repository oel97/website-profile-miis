@extends('frontend.layouts.app')

@php
    $siteName = $siteSettings->get('site_name')?->value ?? $schoolProfile?->name ?? "MI Islamiyah Syafi'iyah";
    $pageTitle = $post->meta_title ?: $post->title;
    $metaDescription = $post->meta_description ?: $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 160);
@endphp

@section('title', $pageTitle . ' | ' . $siteName)
@section('meta_description', $metaDescription)
@section('og_type', 'article')
@if ($post->featured_image_path)
    @section('meta_image', asset('storage/' . $post->featured_image_path))
@endif

@section('content')
    <article class="bg-stone-50 pb-20 sm:pb-24">
        <header class="miis-page-hero relative isolate overflow-hidden bg-emerald-950 py-20 sm:py-24">
            @if ($post->featured_image_path)
                <img src="{{ asset('storage/' . $post->featured_image_path) }}" alt="{{ $post->title }}" class="absolute inset-0 -z-20 h-full w-full object-cover" fetchpriority="high">
                <div class="absolute inset-0 -z-10 bg-emerald-950/85"></div>
            @else
                <div class="miis-page-hero-overlay absolute inset-0 -z-10" aria-hidden="true"></div>
            @endif
            <div class="mx-auto max-w-4xl px-5 sm:px-6 lg:px-8">
                <a href="{{ route('news') }}" class="inline-flex items-center gap-2 text-sm font-bold text-emerald-100 transition hover:text-amber-200"><span aria-hidden="true">←</span> Semua Berita</a>
                <p class="mt-9 text-sm font-bold uppercase tracking-[0.16em] text-amber-300">{{ $post->category->name }}</p>
                <h1 class="mt-5 text-4xl font-extrabold leading-tight tracking-tight text-white sm:text-5xl">{{ $post->title }}</h1>
                <p class="mt-6 text-sm font-semibold text-emerald-100/80">{{ $post->published_at?->translatedFormat('d F Y') }}</p>
            </div>
        </header>

        <div class="mx-auto max-w-4xl px-5 pt-12 sm:px-6 sm:pt-16 lg:px-8">
            @if ($post->excerpt)
                <p class="border-l-4 border-amber-300 pl-5 text-xl font-semibold leading-9 text-emerald-900 sm:text-2xl">{{ $post->excerpt }}</p>
            @endif

            @if ($post->featured_image_path)
                <img src="{{ asset('storage/' . $post->featured_image_path) }}" alt="{{ $post->title }}" class="mt-10 aspect-[16/9] w-full rounded-3xl object-cover shadow-xl shadow-emerald-950/10" loading="lazy">
            @endif

            <div class="mt-10 whitespace-pre-line text-base leading-8 text-slate-700">{!! nl2br(e($post->content)) !!}</div>
        </div>
    </article>

    @if ($relatedPosts->isNotEmpty())
        <section class="bg-white py-20 sm:py-24">
            <div class="mx-auto max-w-7xl px-5 sm:px-6 lg:px-8">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-[0.16em] text-emerald-700">Berita Terkait</p>
                        <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-emerald-950">Masih dari {{ $post->category->name }}</h2>
                    </div>
                    <a href="{{ route('news') }}" class="text-sm font-extrabold text-emerald-700">Lihat semua berita →</a>
                </div>
                <div class="mt-10 grid gap-6 md:grid-cols-3">
                    @foreach ($relatedPosts as $relatedPost)
                        <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white transition hover:shadow-lg hover:shadow-emerald-950/10">
                            @if ($relatedPost->featured_image_path)
                                <img src="{{ asset('storage/' . $relatedPost->featured_image_path) }}" alt="{{ $relatedPost->title }}" class="aspect-[16/10] w-full object-cover" loading="lazy">
                            @else
                                <div class="aspect-[16/10] bg-emerald-100"></div>
                            @endif
                            <div class="p-6">
                                <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">{{ $relatedPost->published_at?->translatedFormat('d M Y') }}</p>
                                <h3 class="mt-3 text-xl font-extrabold leading-snug text-emerald-950"><a href="{{ route('news.show', $relatedPost) }}" class="transition hover:text-emerald-700">{{ $relatedPost->title }}</a></h3>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
