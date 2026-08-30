@php
    $decodeSetting = static function (?string $value): string {
        $decoded = $value ?? '';

        do {
            $previous = $decoded;
            $decoded = html_entity_decode($decoded, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        } while ($decoded !== $previous);

        return $decoded;
    };
    $siteName = $decodeSetting($siteSettings->get('site_name')?->value ?? $schoolProfile?->name ?? "MI Islamiyah Syafi'iyah");
    $tagline = $decodeSetting($siteSettings->get('site_tagline')?->value ?? $schoolProfile?->tagline ?? 'Madrasah Unggul Berkarakter Islami');
    $primaryNavigation = [
        ['label' => 'Beranda', 'href' => route('home') . '#beranda', 'route' => 'home'],
        ['label' => 'Profil', 'href' => route('profile'), 'route' => 'profile'],
        ['label' => 'Program', 'href' => route('program'), 'route' => 'program'],
        ['label' => 'Berita', 'href' => route('news'), 'route' => 'news*'],
        ['label' => 'Galeri', 'href' => route('gallery'), 'route' => 'gallery*'],
        ['label' => 'Kontak', 'href' => route('contact'), 'route' => 'contact'],
    ];
    $exploreNavigation = [
        ['label' => 'Guru & Tendik', 'href' => route('staff'), 'route' => 'staff'],
        ['label' => 'Prestasi', 'href' => route('achievement'), 'route' => 'achievement'],
        ['label' => 'Agenda', 'href' => route('agenda'), 'route' => 'agenda'],
        ['label' => 'Sarana Prasarana', 'href' => route('facility'), 'route' => 'facility'],
    ];
    $navigation = [...$primaryNavigation, ...$exploreNavigation, ['label' => 'PPDB', 'href' => route('ppdb'), 'route' => 'ppdb']];
    $isCurrent = static fn (array $item): bool => request()->routeIs($item['route']);
    $logoPath = $schoolProfile?->logo_path ?? 'logos/logomiis.png';
@endphp

<header class="miis-navbar sticky top-0 z-50 border-b border-emerald-950/10 bg-white/95 backdrop-blur-xl">
    <div class="miis-container flex h-[4.9rem] items-center gap-5">
        <a href="{{ route('home') }}" class="flex min-w-0 items-center gap-3" aria-label="Beranda {{ $siteName }}">
            <img src="{{ asset('storage/' . $logoPath) }}" alt="Logo {{ $siteName }}" class="miis-nav-brand-mark h-11 w-11 rounded-2xl object-contain bg-white p-1 ring-1 ring-emerald-900/10 sm:h-12 sm:w-12">
            <span class="min-w-0">
                <span class="block truncate text-sm font-extrabold tracking-tight text-emerald-950 sm:text-base">{{ $siteName }}</span>
                <span class="hidden truncate text-[.7rem] font-medium tracking-wide text-slate-500 sm:block">{{ $tagline }}</span>
            </span>
        </a>

        <div class="ml-auto flex items-center gap-5">
        <nav class="hidden items-center gap-5 xl:flex" aria-label="Navigasi utama">
            @foreach ($primaryNavigation as $item)
                <a href="{{ $item['href'] }}" class="miis-nav-link text-[.78rem] font-bold text-slate-600 transition hover:text-emerald-800" @if ($isCurrent($item)) aria-current="page" @endif>
                    {{ $item['label'] }}
                </a>
            @endforeach
            <details class="group relative">
                <summary class="flex cursor-pointer list-none items-center gap-1 text-[.78rem] font-bold text-slate-600 transition hover:text-emerald-800 [&::-webkit-details-marker]:hidden">
                    Lainnya
                    <svg class="h-3.5 w-3.5 transition group-open:rotate-180" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m5 7 5 5 5-5" stroke-linecap="round" stroke-linejoin="round" /></svg>
                </summary>
                <div class="absolute right-0 top-8 w-56 rounded-2xl border border-emerald-950/10 bg-white p-2 shadow-2xl shadow-emerald-950/10">
                    @foreach ($exploreNavigation as $item)
                        <a href="{{ $item['href'] }}" class="block rounded-xl px-3 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-emerald-50 hover:text-emerald-800" @if ($isCurrent($item)) aria-current="page" @endif>{{ $item['label'] }}</a>
                    @endforeach
                </div>
            </details>
        </nav>

        <a href="{{ route('ppdb') }}" class="hidden items-center gap-2 rounded-xl bg-emerald-800 px-4 py-2.5 text-[.78rem] font-extrabold text-white shadow-lg shadow-emerald-950/15 transition hover:-translate-y-0.5 hover:bg-emerald-900 sm:inline-flex">
            PPDB
            <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 10h12m-5-5 5 5-5 5" stroke-linecap="round" stroke-linejoin="round" /></svg>
        </a>

        <details class="relative xl:hidden">
            <summary class="flex h-10 w-10 cursor-pointer list-none items-center justify-center rounded-xl border border-emerald-950/10 text-emerald-900 transition hover:border-emerald-200 hover:bg-emerald-50 [&::-webkit-details-marker]:hidden" aria-label="Buka menu navigasi">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16" />
                </svg>
            </summary>
            <nav class="absolute right-0 top-14 max-h-[calc(100vh-6rem)] w-[min(19rem,calc(100vw-2rem))] overflow-y-auto rounded-2xl border border-emerald-950/10 bg-white p-2.5 shadow-2xl shadow-emerald-950/15" aria-label="Navigasi mobile">
                <p class="px-3 pb-2 pt-1 text-[.68rem] font-extrabold uppercase tracking-[.16em] text-emerald-700">Jelajahi MIIS</p>
                @foreach ($navigation as $item)
                    <a href="{{ $item['href'] }}" class="block rounded-xl px-3 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-emerald-50 hover:text-emerald-800" @if ($isCurrent($item)) aria-current="page" @endif>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>
        </details>
        </div>
    </div>
</header>
