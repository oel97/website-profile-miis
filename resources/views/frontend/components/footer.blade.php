@php
    $siteName = $siteSettings->get('site_name')?->value ?? $schoolProfile?->name ?? "MI Islamiyah Syafi'iyah";
    $tagline = $siteSettings->get('site_tagline')?->value ?? $schoolProfile?->tagline ?? 'Madrasah Unggul Berkarakter Islami';
    $copyright = $siteSettings->get('copyright')?->value ?? '© ' . now()->year . ' ' . $siteName . '. Seluruh hak cipta dilindungi.';
    $address = $contacts->firstWhere('type', 'address')?->value ?? $schoolProfile?->address;
    $contactItems = $contacts->reject(fn ($contact) => $contact->type === 'address');
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
    $copyright = $decodeSetting($siteSettings->get('copyright')?->value ?? '&copy; ' . now()->year . ' ' . $siteName . '. Seluruh hak cipta dilindungi.');
    $quickLinks = [
        ['label' => 'Profil Madrasah', 'href' => route('profile')],
        ['label' => 'Program Unggulan', 'href' => route('program')],
        ['label' => 'Berita & Kegiatan', 'href' => route('news')],
        ['label' => 'PPDB', 'href' => route('ppdb')],
    ];
    $logoPath = $schoolProfile?->logo_path ?? 'logos/logomiis.png';
@endphp

<footer id="kontak" class="relative overflow-hidden bg-emerald-950 text-emerald-50">
    <div class="miis-footer-grid-pattern absolute inset-0 opacity-30" aria-hidden="true"></div>
    <div class="miis-container relative py-14 sm:py-16">
        <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-[1.25fr_.8fr_1fr_1fr]">
            <div>
                <div class="flex items-center gap-3">
                    <img src="{{ asset('storage/' . $logoPath) }}" alt="Logo {{ $siteName }}" class="h-12 w-12 rounded-2xl bg-white p-1 object-contain ring-1 ring-emerald-100/30">
                    <p class="text-lg font-extrabold tracking-tight">{{ $siteName }}</p>
                </div>
                <p class="mt-5 max-w-sm text-sm leading-7 text-emerald-100/80">{{ $tagline }}</p>
                @if ($address)
                    <p class="mt-5 max-w-sm text-sm leading-7 text-emerald-100/70">{{ $address }}</p>
                @endif
            </div>

            <div>
                <h2 class="text-xs font-extrabold uppercase tracking-[.16em] text-amber-300">Eksplorasi</h2>
                <ul class="mt-5 space-y-3 text-sm text-emerald-100/75">
                    @foreach ($quickLinks as $link)
                        <li><a href="{{ $link['href'] }}" class="transition hover:text-amber-200">{{ $link['label'] }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h2 class="text-xs font-extrabold uppercase tracking-[.16em] text-amber-300">Hubungi Kami</h2>
                <ul class="mt-5 space-y-3 text-sm text-emerald-100/80">
                    @forelse ($contactItems as $contact)
                        @php($contactUrl = $contact->publicUrl())
                        <li>
                            @if ($contactUrl)
                                <a href="{{ $contactUrl }}" class="transition hover:text-amber-200" @if (str_starts_with($contactUrl, 'http')) target="_blank" rel="noopener noreferrer" @endif>
                                    <span class="font-semibold text-emerald-50">{{ $contact->label }}:</span> {{ $contact->value }}
                                </a>
                            @else
                                <span><span class="font-semibold text-emerald-50">{{ $contact->label }}:</span> {{ $contact->value }}</span>
                            @endif
                        </li>
                    @empty
                        @if ($schoolProfile?->email)
                            <li><a href="mailto:{{ $schoolProfile->email }}" class="transition hover:text-amber-200">{{ $schoolProfile->email }}</a></li>
                        @endif
                        @if ($schoolProfile?->phone)
                            <li><a href="tel:{{ preg_replace('/\D+/', '', $schoolProfile->phone) }}" class="transition hover:text-amber-200">{{ $schoolProfile->phone }}</a></li>
                        @endif
                        @if (! $schoolProfile?->email && ! $schoolProfile?->phone)
                            <li class="text-emerald-100/60">Kontak sekolah akan segera tersedia.</li>
                        @endif
                    @endforelse
                </ul>
            </div>

            <div>
                <h2 class="text-xs font-extrabold uppercase tracking-[.16em] text-amber-300">Ikuti Kami</h2>
                <div class="mt-5 flex flex-wrap gap-2">
                    @forelse ($socialLinks as $socialLink)
                        <a href="{{ $socialLink->url }}" target="_blank" rel="noopener noreferrer" class="rounded-lg border border-emerald-100/20 px-3 py-2 text-sm font-semibold text-emerald-50 transition hover:border-amber-300 hover:bg-emerald-900 hover:text-amber-200" aria-label="{{ $socialLink->label ?? $socialLink->platform }}">
                            {{ $socialLink->label ?? $socialLink->platform }}
                        </a>
                    @empty
                        <p class="text-sm text-emerald-100/60">Akun sosial media akan segera tersedia.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="mt-12 flex flex-col gap-3 border-t border-emerald-900/80 pt-6 text-xs text-emerald-100/60 sm:flex-row sm:items-center sm:justify-between">
            <p>{{ $copyright }}</p>
            <a href="{{ url('/') }}#beranda" class="font-semibold transition hover:text-amber-200">Kembali ke atas ↑</a>
        </div>
    </div>
</footer>
