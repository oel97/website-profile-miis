@extends('frontend.layouts.app')

@php
    $siteName = $siteSettings->get('site_name')?->value ?? $schoolProfile?->name ?? "MI Islamiyah Syafi'iyah";
    $tagline = $siteSettings->get('site_tagline')?->value ?? $schoolProfile?->tagline ?? 'Madrasah Unggul Berkarakter Islami';
    $heroSlide = $heroSlides->first();
    $defaultHeroImage = asset('storage/hero/gedung.jpeg');
    $heroImage = $heroSlide?->image_path ? asset('storage/' . $heroSlide->image_path) : $defaultHeroImage;
    $heroAlt = $heroSlide?->title ?? 'Upacara pagi di MI Islamiyah Syafi\'iyah';
    $heroOverlayOpacity = $heroSlide?->overlay_opacity ?? 52;
    $schoolPhoto = $schoolProfile?->foto_sekolah_path ? asset('storage/' . $schoolProfile->foto_sekolah_path) : null;
    $defaultAboutImage = asset('storage/home/kelas-al-quran.jpg');
    $aboutImage = $schoolPhoto ?? $defaultAboutImage;
    $aboutImageAlt = $schoolPhoto ? 'Lingkungan ' . $siteName : 'Kegiatan belajar Al-Quran di MI Islamiyah Syafi\'iyah';
    $decodeSetting = static function (?string $value): string {
        $decoded = $value ?? '';

        do {
            $previous = $decoded;
            $decoded = html_entity_decode($decoded, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        } while ($decoded !== $previous);

        return $decoded;
    };
    $siteName = $decodeSetting($siteName);
    $tagline = $decodeSetting($tagline);
    $section = static fn (string $key) => $homepageSections->get($key);
    $sectionIsActive = static fn (string $key): bool => $section($key)?->is_active ?? true;
    $sectionOrder = static function (string $key) use ($section): int {
        $defaults = [
            'about' => 10,
            'programs' => 20,
            'news' => 30,
            'achievements' => 40,
            'agenda_ppdb' => 50,
        ];

        return $section($key)?->sort_order ?? $defaults[$key];
    };
    $sectionTitle = static fn (string $key, string $fallback): string => $section($key)?->title_override ?: $fallback;
    $sectionSubtitle = static fn (string $key, string $fallback): string => $section($key)?->subtitle_override ?: $fallback;
@endphp

@section('title', $siteName . ' | ' . $tagline)
@section('meta_description', \Illuminate\Support\Str::limit($heroSlide?->subtitle ?? $schoolProfile?->short_description ?? $tagline, 160))
@if ($heroImage ?? $schoolPhoto)
    @section('meta_image', $heroImage ?? $schoolPhoto)
@endif

@section('content')
    <section id="beranda" class="miis-home-hero relative isolate overflow-hidden bg-emerald-950" data-has-hero-image="{{ $heroImage ? 'true' : 'false' }}">
        @if ($heroImage)
            @if ($heroSlide?->mobile_image_path)
                <picture class="absolute inset-0 -z-20">
                    <source media="(max-width: 639px)" srcset="{{ asset('storage/' . $heroSlide->mobile_image_path) }}">
                    <img src="{{ $heroImage }}" alt="{{ $heroAlt }}" class="h-full w-full object-cover" fetchpriority="high">
                </picture>
            @else
                <img src="{{ $heroImage }}" alt="{{ $heroAlt }}" class="absolute inset-0 -z-20 h-full w-full object-cover" fetchpriority="high">
            @endif
            <div class="absolute inset-0 -z-10 bg-emerald-950" style="opacity: {{ $heroOverlayOpacity / 100 }}"></div>
        @else
            <div class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_top_right,_#34d399_0,_transparent_32%),radial-gradient(circle_at_bottom_left,_#fbbf24_0,_transparent_20%)] opacity-40"></div>
        @endif

        <div class="miis-hero-grid mx-auto grid min-h-[620px] max-w-7xl items-center gap-10 px-5 py-24 sm:px-6 lg:grid-cols-[1.15fr_0.85fr] lg:px-8">
            <div class="max-w-3xl">
                <p class="inline-flex items-center gap-2 rounded-full border border-amber-200/30 bg-emerald-900/50 px-4 py-2 text-xs font-bold uppercase tracking-[0.16em] text-amber-200 backdrop-blur">
                    <span class="h-2 w-2 rounded-full bg-amber-300"></span>
                    Madrasah Ibtidaiyah Islamiyah Syafi'iyah
                </p>
                <h1 class="mt-6 text-4xl font-extrabold leading-tight tracking-tight text-white sm:text-5xl lg:text-6xl">
                    {{ $heroSlide?->title ?? $tagline }}
                </h1>
                <p class="mt-6 max-w-2xl text-base leading-8 text-emerald-50/85 sm:text-lg">
                    {{ $heroSlide?->subtitle ?? $schoolProfile?->short_description ?? 'Mendampingi generasi berilmu, berakhlak mulia, dan siap memberi manfaat bagi sesama.' }}
                </p>
                <div class="mt-9 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ $heroSlide?->button_url ?? '#profil' }}" class="inline-flex items-center justify-center rounded-full bg-amber-300 px-6 py-3.5 text-sm font-extrabold text-emerald-950 shadow-lg shadow-emerald-950/20 transition hover:bg-amber-200">
                        {{ $heroSlide?->button_label ?? 'Kenali Madrasah' }}
                    </a>
                    <a href="#kontak" class="inline-flex items-center justify-center rounded-full border border-white/25 bg-white/10 px-6 py-3.5 text-sm font-extrabold text-white backdrop-blur transition hover:bg-white/20">
                        Hubungi Kami
                    </a>
                </div>
            </div>

            <div class="miis-hero-fallback-card hidden justify-self-end lg:block">
                <div class="rounded-[2rem] border border-white/20 bg-white/10 p-7 text-white shadow-2xl backdrop-blur-md">
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-amber-200">Pendidikan bermakna</p>
                    <p class="mt-4 max-w-xs text-2xl font-bold leading-snug">Ilmu yang tumbuh bersama akhlak dan kepedulian.</p>
                    <div class="mt-7 flex items-center gap-3 border-t border-white/15 pt-5 text-sm text-emerald-50/80">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-amber-300 font-bold text-emerald-950">MI</span>
                        <span>{{ $siteName }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="flex flex-col">
    @if ($sectionIsActive('about'))
    <section id="profil" class="scroll-mt-24 bg-white py-20 sm:py-24" style="order: {{ $sectionOrder('about') }}">
        <div class="mx-auto grid max-w-7xl items-center gap-12 px-5 sm:px-6 lg:grid-cols-2 lg:px-8">
            <div class="relative overflow-hidden rounded-[2rem] bg-emerald-100 shadow-xl shadow-emerald-950/10">
                @if ($aboutImage)
                    <img src="{{ $aboutImage }}" alt="{{ $aboutImageAlt }}" class="aspect-[16/10] h-full w-full object-cover" loading="lazy">
                    <div class="absolute inset-x-0 top-0 h-28 bg-gradient-to-b from-emerald-950/35 to-transparent" aria-hidden="true"></div>
                @else
                    <div class="flex aspect-[4/3] items-end bg-[linear-gradient(145deg,_#064e3b,_#059669_55%,_#fbbf24)] p-8">
                        <p class="max-w-xs text-3xl font-extrabold leading-tight text-white">Ruang tumbuh bagi generasi Qurani.</p>
                    </div>
                @endif
                <div class="absolute left-5 top-5 rounded-2xl bg-white/95 px-4 py-3 shadow-lg backdrop-blur">
                    <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">{{ $schoolProfile?->accreditation ? 'Akreditasi ' . $schoolProfile->accreditation : 'Madrasah Berkarakter' }}</p>
                </div>
            </div>
            <div>
                <p class="text-sm font-bold uppercase tracking-[0.16em] text-emerald-700">{{ $sectionSubtitle('about', 'Tentang Madrasah') }}</p>
                <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-emerald-950 sm:text-4xl">{{ $sectionTitle('about', 'Belajar dengan ilmu, bertumbuh dengan nilai Islami.') }}</h2>
                <p class="mt-6 text-base leading-8 text-slate-600">{{ $schoolProfile?->short_description ?? 'MI Islamiyah Syafi\'iyah berkomitmen menghadirkan pendidikan dasar yang hangat, terarah, dan relevan bagi masa depan peserta didik.' }}</p>
                <a href="#program" class="mt-8 inline-flex items-center gap-2 text-sm font-extrabold text-emerald-700 transition hover:text-emerald-900">Lihat program unggulan <span aria-hidden="true">→</span></a>
            </div>
        </div>
    </section>
    @endif

    @if ($sectionIsActive('programs'))
    <section id="program" class="scroll-mt-24 bg-stone-100 py-20 sm:py-24" style="order: {{ $sectionOrder('programs') }}">
        <div class="mx-auto max-w-7xl px-5 sm:px-6 lg:px-8">
            <div class="max-w-2xl">
                <p class="text-sm font-bold uppercase tracking-[0.16em] text-emerald-700">{{ $sectionSubtitle('programs', 'Keunggulan Kami') }}</p>
                <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-emerald-950 sm:text-4xl">{{ $sectionTitle('programs', 'Program yang menguatkan potensi setiap anak.') }}</h2>
            </div>
            <div class="mt-10 grid gap-5 md:grid-cols-3">
                @forelse ($featuredPrograms as $program)
                    <article class="group overflow-hidden rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-emerald-950/10">
                        @if ($program->image_path)
                            <img src="{{ asset('storage/' . $program->image_path) }}" alt="{{ $program->title }}" class="mb-6 aspect-[16/10] w-full rounded-2xl object-cover" loading="lazy">
                        @else
                            <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 text-sm font-extrabold text-emerald-800">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($program->icon ?? $program->title, 0, 2)) }}</span>
                        @endif
                        <h3 class="mt-6 text-xl font-extrabold text-emerald-950">{{ $program->title }}</h3>
                        <p class="mt-3 text-sm leading-7 text-slate-600">{{ \Illuminate\Support\Str::limit(strip_tags($program->short_description ?? $program->description), 140) }}</p>
                    </article>
                @empty
                    <div class="rounded-3xl border border-dashed border-emerald-200 bg-white p-8 text-sm leading-7 text-slate-500 md:col-span-3">Program unggulan akan tampil di sini setelah dikelola melalui CMS.</div>
                @endforelse
            </div>
        </div>
    </section>
    @endif

    @if ($sectionIsActive('news'))
    <section id="berita" class="scroll-mt-24 bg-white py-20 sm:py-24" style="order: {{ $sectionOrder('news') }}">
        <div class="mx-auto max-w-7xl px-5 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-sm font-bold uppercase tracking-[0.16em] text-emerald-700">{{ $sectionSubtitle('news', 'Kabar Madrasah') }}</p>
                    <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-emerald-950 sm:text-4xl">{{ $sectionTitle('news', 'Berita dan kegiatan terbaru.') }}</h2>
                </div>
                <a href="{{ route('news') }}" class="text-sm font-extrabold text-emerald-700">Lihat semua berita →</a>
            </div>
            <div class="mt-10 grid gap-6 lg:grid-cols-3">
                @forelse ($latestPosts as $post)
                    <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white transition hover:shadow-lg hover:shadow-emerald-950/10">
                        @if ($post->featured_image_path)
                            <img src="{{ asset('storage/' . $post->featured_image_path) }}" alt="{{ $post->title }}" class="aspect-[16/10] w-full object-cover" loading="lazy">
                        @else
                            <div class="flex aspect-[16/10] items-end bg-emerald-100 p-6"><span class="rounded-full bg-white px-3 py-1.5 text-xs font-bold text-emerald-800">{{ $post->category?->name ?? 'Berita' }}</span></div>
                        @endif
                        <div class="p-6">
                            <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">{{ $post->category?->name ?? 'Berita' }} · {{ $post->published_at?->translatedFormat('d M Y') }}</p>
                            <h3 class="mt-3 text-xl font-extrabold leading-snug text-emerald-950">{{ $post->title }}</h3>
                            <p class="mt-3 text-sm leading-7 text-slate-600">{{ \Illuminate\Support\Str::limit(strip_tags($post->excerpt ?? $post->content), 130) }}</p>
                        </div>
                    </article>
                @empty
                    <div class="rounded-3xl border border-dashed border-emerald-200 p-8 text-sm text-slate-500 lg:col-span-3">Berita terbit dari CMS akan ditampilkan di area ini.</div>
                @endforelse
            </div>
        </div>
    </section>
    @endif

    @if ($sectionIsActive('achievements'))
    <section id="prestasi" class="scroll-mt-24 bg-emerald-950 py-20 text-white sm:py-24" style="order: {{ $sectionOrder('achievements') }}">
        <div class="mx-auto max-w-7xl px-5 sm:px-6 lg:px-8">
            <div class="max-w-2xl">
                <p class="text-sm font-bold uppercase tracking-[0.16em] text-amber-300">{{ $sectionSubtitle('achievements', 'Prestasi') }}</p>
                <h2 class="mt-4 text-3xl font-extrabold tracking-tight sm:text-4xl">{{ $sectionTitle('achievements', 'Merayakan setiap pencapaian.') }}</h2>
            </div>
            <div class="mt-10 grid gap-5 md:grid-cols-3">
                @forelse ($latestAchievements as $achievement)
                    <article class="rounded-3xl border border-emerald-800 bg-emerald-900/50 p-6 transition hover:border-amber-300/60">
                        <p class="text-sm font-bold text-amber-300">{{ $achievement->level ?? 'Prestasi Madrasah' }}</p>
                        <h3 class="mt-4 text-xl font-extrabold leading-snug">{{ $achievement->title }}</h3>
                        @if ($achievement->recipient_name)
                            <p class="mt-3 text-sm text-emerald-100/75">{{ $achievement->recipient_name }}</p>
                        @endif
                        @if ($achievement->achievement_date)
                            <p class="mt-5 text-xs font-semibold uppercase tracking-wider text-emerald-200">{{ $achievement->achievement_date->translatedFormat('Y') }}</p>
                        @endif
                    </article>
                @empty
                    <div class="rounded-3xl border border-dashed border-emerald-700 p-8 text-sm text-emerald-100/70 md:col-span-3">Prestasi sekolah akan tampil di sini setelah diterbitkan melalui CMS.</div>
                @endforelse
            </div>
        </div>
    </section>
    @endif

    @if ($sectionIsActive('agenda_ppdb'))
    <section id="ppdb" class="scroll-mt-24 bg-amber-50 py-20 sm:py-24" style="order: {{ $sectionOrder('agenda_ppdb') }}">
        <div class="mx-auto grid max-w-7xl gap-10 px-5 sm:px-6 lg:grid-cols-[1fr_auto] lg:items-center lg:px-8">
            <div>
                <p class="text-sm font-bold uppercase tracking-[0.16em] text-emerald-700">{{ $sectionSubtitle('agenda_ppdb', 'Agenda & PPDB') }}</p>
                <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-emerald-950 sm:text-4xl">{{ $sectionTitle('agenda_ppdb', 'Langkah awal menuju pengalaman belajar yang bermakna.') }}</h2>
                @if ($latestAgendas->isNotEmpty())
                    <div class="mt-7 space-y-3">
                        @foreach ($latestAgendas as $agenda)
                            <div class="flex gap-4 rounded-2xl border border-amber-200 bg-white/80 p-4">
                                <div class="min-w-14 rounded-xl bg-emerald-700 px-2 py-2 text-center text-white">
                                    <span class="block text-lg font-extrabold leading-none">{{ $agenda->start_at->format('d') }}</span>
                                    <span class="block mt-1 text-[10px] font-bold uppercase">{{ $agenda->start_at->translatedFormat('M') }}</span>
                                </div>
                                <div>
                                    <h3 class="font-extrabold text-emerald-950">{{ $agenda->title }}</h3>
                                    <p class="mt-1 text-sm text-slate-600">{{ $agenda->location ?? 'MI Islamiyah Syafi\'iyah' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="mt-6 text-base leading-8 text-slate-600">Informasi agenda dan penerimaan peserta didik baru akan diumumkan melalui website ini.</p>
                @endif
            </div>
            <a href="#kontak" class="inline-flex h-fit items-center justify-center rounded-full bg-emerald-700 px-7 py-4 text-sm font-extrabold text-white shadow-lg shadow-emerald-900/15 transition hover:bg-emerald-800">Tanya Informasi PPDB</a>
        </div>
    </section>
    @endif
    </div>
@endsection
