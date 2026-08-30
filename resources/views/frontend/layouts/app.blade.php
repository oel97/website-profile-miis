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
    $siteTagline = $decodeSetting($siteSettings->get('site_tagline')?->value ?? $schoolProfile?->tagline ?? 'Madrasah Unggul Berkarakter Islami');
    $metaDescription = $decodeSetting($siteSettings->get('meta_description')?->value ?? $schoolProfile?->short_description ?? "Website resmi MI Islamiyah Syafi'iyah");
    $faviconPath = $siteSettings->get('favicon')?->value ?? 'favicon.ico';
    $faviconUrl = \Illuminate\Support\Str::startsWith($faviconPath, ['http://', 'https://', '/']) ? $faviconPath : asset($faviconPath);
    $logoPath = $schoolProfile?->logo_path ?? 'logos/logomiis.png';
    $defaultMetaImage = asset('storage/' . $logoPath);
    $pageTitle = $decodeSetting(trim($__env->yieldContent('title')) ?: $siteName . ' | ' . $siteTagline);
    $pageDescription = $decodeSetting(trim($__env->yieldContent('meta_description')) ?: $metaDescription);
    $pageImage = trim($__env->yieldContent('meta_image')) ?: $defaultMetaImage;
    $canonicalUrl = trim($__env->yieldContent('canonical_url')) ?: url()->current();
    $robots = $siteSettings->get('robots')?->value ?? 'index,follow';
    $twitterSite = $siteSettings->get('twitter_site')?->value;
@endphp
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $pageDescription }}">
    <meta name="robots" content="{{ $robots }}">
    <meta name="theme-color" content="#047857">
    <link rel="icon" href="{{ $faviconUrl }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <meta property="og:locale" content="id_ID">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    @if ($pageImage)
        <meta property="og:image" content="{{ $pageImage }}">
    @endif
    <meta name="twitter:card" content="{{ $pageImage ? 'summary_large_image' : 'summary' }}">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    @if ($pageImage)
        <meta name="twitter:image" content="{{ $pageImage }}">
    @endif
    @if ($twitterSite)
        <meta name="twitter:site" content="{{ $twitterSite }}">
    @endif
    <title>{{ $pageTitle }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .skip-link { left: 1rem; position: fixed; top: 1rem; transform: translateY(-200%); transition: transform .2s ease; z-index: 100; }
        .skip-link:focus { transform: translateY(0); }
        :where(a, button, summary, [tabindex]):focus-visible { outline: 3px solid #fbbf24; outline-offset: 3px; }
    </style>
</head>
<body class="miis-site overflow-x-hidden bg-stone-50 font-sans text-slate-800 antialiased">
    <a href="#main-content" class="skip-link rounded-lg bg-amber-300 px-4 py-3 text-sm font-extrabold text-emerald-950 shadow-lg">Lewati ke konten utama</a>
    <x-frontend::navbar :school-profile="$schoolProfile" :site-settings="$siteSettings" />

    <main id="main-content" tabindex="-1">
        @yield('content')
    </main>

    <x-frontend::footer :school-profile="$schoolProfile" :site-settings="$siteSettings" :contacts="$contacts" :social-links="$socialLinks" />

    @stack('scripts')
</body>
</html>
