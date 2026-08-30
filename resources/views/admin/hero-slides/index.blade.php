@extends('admin.layouts.app')

@section('title', 'Hero Slide')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">Beranda</p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">Hero Slide</h1>
                <p class="mt-2 text-sm text-slate-500">Kelola banner utama yang tampil pada halaman beranda.</p>
            </div>
            <a href="{{ route('admin.hero-slides.create') }}" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                + Tambah Hero Slide
            </a>
        </div>

        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse text-left text-sm text-slate-700">
                    <thead class="bg-slate-50 text-slate-700">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Gambar</th>
                            <th class="px-4 py-3 font-semibold">Judul & Deskripsi</th>
                            <th class="px-4 py-3 font-semibold">Tombol</th>
                            <th class="px-4 py-3 font-semibold">Status</th>
                            <th class="px-4 py-3 font-semibold">Urutan</th>
                            <th class="px-4 py-3 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($heroSlides as $heroSlide)
                            <tr class="border-t border-slate-200">
                                <td class="px-4 py-3">
                                    <img src="{{ asset('storage/' . $heroSlide->image_path) }}" alt="{{ $heroSlide->title }}" class="h-14 w-24 rounded-xl object-cover ring-1 ring-slate-200">
                                </td>
                                <td class="max-w-sm px-4 py-3">
                                    <p class="font-semibold text-slate-900">{{ $heroSlide->title }}</p>
                                    <p class="mt-1 text-xs leading-5 text-slate-500">{{ \Illuminate\Support\Str::limit($heroSlide->subtitle, 100) }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    @if ($heroSlide->button_label)
                                        <p class="font-medium text-slate-800">{{ $heroSlide->button_label }}</p>
                                        @if ($heroSlide->button_url)
                                            <p class="mt-1 max-w-40 truncate text-xs text-slate-500">{{ $heroSlide->button_url }}</p>
                                        @endif
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if ($heroSlide->is_active)
                                        <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">Aktif</span>
                                    @else
                                        <span class="rounded-full bg-slate-200 px-2.5 py-1 text-xs font-semibold text-slate-700">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <p>{{ $heroSlide->sort_order }}</p>
                                    <p class="mt-1 text-xs text-slate-500">Overlay {{ $heroSlide->overlay_opacity }}%</p>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.hero-slides.edit', $heroSlide) }}" class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-700 transition hover:bg-amber-100">Edit</a>
                                        <form action="{{ route('admin.hero-slides.destroy', $heroSlide) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus hero slide ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700 transition hover:bg-red-100">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center text-slate-500">Belum ada hero slide. Halaman beranda menggunakan gambar fallback.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($heroSlides->hasPages())
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                {{ $heroSlides->links() }}
            </div>
        @endif
    </div>
@endsection
