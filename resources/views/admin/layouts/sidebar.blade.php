@php
    $mobile = isset($mobile) ? $mobile : false;
    $schoolProfile = \App\Models\SchoolProfile::first();
@endphp

<aside @class([
    'hidden w-72 flex-shrink-0 bg-slate-900 text-slate-100 md:flex md:flex-col',
    'mt-4 flex flex-col overflow-hidden rounded-2xl bg-slate-100 shadow-lg' => $mobile,
])>
    <div class="flex items-center gap-3 px-5 py-4">
    @if($schoolProfile?->logo_path)
        <img 
            src="{{ asset('storage/' . $schoolProfile->logo_path) }}"
            class="h-10 w-10 rounded-lg object-contain bg-white"
            alt="Logo Sekolah"
        >
    @else
        <div class="h-10 w-10 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-bold">
            MI
        </div>
    @endif

    <div class="leading-tight">
        <p class="text-[11px] tracking-[3px] text-slate-400 uppercase">
            Admin
        </p>

        <p class="text-sm font-bold text-white">
            MIIS
        </p>
    </div>
</div>

    <nav class="flex-1 space-y-6 px-4 py-6">
        <div>
            <p class="px-3 text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Navigasi</p>
            <ul class="mt-3 space-y-1">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-xl bg-emerald-500/15 px-3 py-2.5 text-sm font-medium text-emerald-300">
                        Dashboard
                    </a>
                </li>
            </ul>
        </div>

        <div>
            <p class="px-3 text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Beranda</p>
            <ul class="mt-3 space-y-1 text-sm text-slate-300">
                <li>
                    <a href="{{ route('admin.hero-slides.index') }}" @class([
                        'block rounded-xl px-3 py-2 font-medium transition',
                        'bg-emerald-500/15 text-emerald-300' => request()->routeIs('admin.hero-slides.*'),
                        'text-slate-300 hover:bg-slate-800 hover:text-white' => ! request()->routeIs('admin.hero-slides.*'),
                    ])>
                        Hero Slide
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.homepage-sections.index') }}" @class([
                        'block rounded-xl px-3 py-2 font-medium transition',
                        'bg-emerald-500/15 text-emerald-300' => request()->routeIs('admin.homepage-sections.*'),
                        'text-slate-300 hover:bg-slate-800 hover:text-white' => ! request()->routeIs('admin.homepage-sections.*'),
                    ])>
                        Homepage Section
                    </a>
                </li>
            </ul>
        </div>

        <div>
            <p class="px-3 text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Master Sekolah</p>
            <ul class="mt-3 space-y-1 text-sm text-slate-300">
                <li><a href="{{ route('admin.school-profile.index') }}" class="block rounded-xl px-3 py-2 hover:bg-slate-800 hover:text-white">Profil Sekolah</a></li>
                <li><a href="{{ route('admin.principal-message.index') }}" class="block rounded-xl px-3 py-2 hover:bg-slate-800 hover:text-white">Sambutan Kepala Madrasah</a></li>
                <li>
                    <a href="{{ route('admin.staff.index') }}" @class([
                        'block rounded-xl px-3 py-2 font-medium transition',
                        'bg-emerald-500/15 text-emerald-300' => request()->routeIs('admin.staff.*'),
                        'text-slate-300 hover:bg-slate-800 hover:text-white' => ! request()->routeIs('admin.staff.*'),
                    ])>
                        Guru & Tendik
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.programs.index') }}" @class([
                        'block rounded-xl px-3 py-2 font-medium transition',
                        'bg-emerald-500/15 text-emerald-300' => request()->routeIs('admin.programs.*'),
                        'text-slate-300 hover:bg-slate-800 hover:text-white' => ! request()->routeIs('admin.programs.*'),
                    ])>
                        Program Unggulan
                    </a>
                </li>
            </ul>
        </div>

        <div>
            <p class="px-3 text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Konten</p>
            <ul class="mt-3 space-y-1 text-sm text-slate-300">
                <li>
                    <a href="{{ route('admin.post-categories.index') }}" @class([
                        'block rounded-xl px-3 py-2 font-medium transition',
                        'bg-emerald-500/15 text-emerald-300' => request()->routeIs('admin.post-categories.*'),
                        'text-slate-300 hover:bg-slate-800 hover:text-white' => ! request()->routeIs('admin.post-categories.*'),
                    ])>
                        Kategori Berita
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.posts.index') }}" @class([
                        'block rounded-xl px-3 py-2 font-medium transition',
                        'bg-emerald-500/15 text-emerald-300' => request()->routeIs('admin.posts.*'),
                        'text-slate-300 hover:bg-slate-800 hover:text-white' => ! request()->routeIs('admin.posts.*'),
                    ])>
                        Berita & Kegiatan
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.achievements.index') }}" @class([
                        'block rounded-xl px-3 py-2 font-medium transition',
                        'bg-emerald-500/15 text-emerald-300' => request()->routeIs('admin.achievements.*'),
                        'text-slate-300 hover:bg-slate-800 hover:text-white' => ! request()->routeIs('admin.achievements.*'),
                    ])>
                        Prestasi
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.agendas.index') }}" @class([
                        'block rounded-xl px-3 py-2 font-medium transition',
                        'bg-emerald-500/15 text-emerald-300' => request()->routeIs('admin.agendas.*'),
                        'text-slate-300 hover:bg-slate-800 hover:text-white' => ! request()->routeIs('admin.agendas.*'),
                    ])>
                        Agenda Sekolah
                    </a>
                </li>
            </ul>
        </div>

        <div>
            <p class="px-3 text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Media</p>
            <ul class="mt-3 space-y-1 text-sm text-slate-300">
                <li>
                    <a href="{{ route('admin.gallery-albums.index') }}" @class([
                        'block rounded-xl px-3 py-2 font-medium transition',
                        'bg-emerald-500/15 text-emerald-300' => request()->routeIs('admin.gallery-albums.*'),
                        'text-slate-300 hover:bg-slate-800 hover:text-white' => ! request()->routeIs('admin.gallery-albums.*'),
                    ])>
                        Galeri Album
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.gallery-photos.index') }}" @class([
                        'block rounded-xl px-3 py-2 font-medium transition',
                        'bg-emerald-500/15 text-emerald-300' => request()->routeIs('admin.gallery-photos.*'),
                        'text-slate-300 hover:bg-slate-800 hover:text-white' => ! request()->routeIs('admin.gallery-photos.*'),
                    ])>
                        Foto Galeri
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.facilities.index') }}" @class([
                        'block rounded-xl px-3 py-2 font-medium transition',
                        'bg-emerald-500/15 text-emerald-300' => request()->routeIs('admin.facilities.*'),
                        'text-slate-300 hover:bg-slate-800 hover:text-white' => ! request()->routeIs('admin.facilities.*'),
                    ])>
                        Sarana Prasarana
                    </a>
                </li>
            </ul>
        </div>

        <div>
            <p class="px-3 text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">PPDB</p>
            <ul class="mt-3 space-y-1 text-sm text-slate-300">
                <li>
                    <a href="{{ route('admin.ppdb-periods.index') }}" @class([
                        'block rounded-xl px-3 py-2 font-medium transition',
                        'bg-emerald-500/15 text-emerald-300' => request()->routeIs('admin.ppdb-periods.*'),
                        'text-slate-300 hover:bg-slate-800 hover:text-white' => ! request()->routeIs('admin.ppdb-periods.*'),
                    ])>
                        Periode PPDB
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.ppdb-requirements.index') }}" @class([
                        'block rounded-xl px-3 py-2 font-medium transition',
                        'bg-emerald-500/15 text-emerald-300' => request()->routeIs('admin.ppdb-requirements.*'),
                        'text-slate-300 hover:bg-slate-800 hover:text-white' => ! request()->routeIs('admin.ppdb-requirements.*'),
                    ])>
                        Persyaratan PPDB
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.ppdb-steps.index') }}" @class([
                        'block rounded-xl px-3 py-2 font-medium transition',
                        'bg-emerald-500/15 text-emerald-300' => request()->routeIs('admin.ppdb-steps.*'),
                        'text-slate-300 hover:bg-slate-800 hover:text-white' => ! request()->routeIs('admin.ppdb-steps.*'),
                    ])>
                        Langkah Pendaftaran
                    </a>
                </li>
            </ul>
        </div>

        <div>
            <p class="px-3 text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Pengaturan</p>
            <ul class="mt-3 space-y-1 text-sm text-slate-300">
                <li>
                    <a href="{{ route('admin.contacts.index') }}" @class([
                        'block rounded-xl px-3 py-2 font-medium transition',
                        'bg-emerald-500/15 text-emerald-300' => request()->routeIs('admin.contacts.*'),
                        'text-slate-300 hover:bg-slate-800 hover:text-white' => ! request()->routeIs('admin.contacts.*'),
                    ])>
                        Kontak Sekolah
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.social-links.index') }}" @class([
                        'block rounded-xl px-3 py-2 font-medium transition',
                        'bg-emerald-500/15 text-emerald-300' => request()->routeIs('admin.social-links.*'),
                        'text-slate-300 hover:bg-slate-800 hover:text-white' => ! request()->routeIs('admin.social-links.*'),
                    ])>
                        Sosial Media
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.site-settings.index') }}" @class([
                        'block rounded-xl px-3 py-2 font-medium transition',
                        'bg-emerald-500/15 text-emerald-300' => request()->routeIs('admin.site-settings.*'),
                        'text-slate-300 hover:bg-slate-800 hover:text-white' => ! request()->routeIs('admin.site-settings.*'),
                    ])>
                        Website Setting
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.account.edit') }}" @class([
                        'block rounded-xl px-3 py-2 font-medium transition',
                        'bg-emerald-500/15 text-emerald-300' => request()->routeIs('admin.account.*'),
                        'text-slate-300 hover:bg-slate-800 hover:text-white' => ! request()->routeIs('admin.account.*'),
                    ])>
                        Akun Saya
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</aside>
