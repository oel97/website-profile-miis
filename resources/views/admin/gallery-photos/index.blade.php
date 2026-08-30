@extends('admin.layouts.app')

@section('title', 'Foto Galeri')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">Media</p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">Foto Galeri</h1>
                <p class="mt-2 text-sm text-slate-500">Kelola foto dokumentasi berdasarkan album kegiatan sekolah.</p>
            </div>
            @if ($albums->isNotEmpty())
                <a href="{{ route('admin.gallery-photos.create') }}" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                    + Tambah Foto
                </a>
            @endif
        </div>

        @if ($albums->isEmpty())
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
                Buat <a href="{{ route('admin.gallery-albums.create') }}" class="font-semibold underline">Album Galeri</a> terlebih dahulu sebelum menambahkan foto.
            </div>
        @endif

        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.gallery-photos.index') }}" method="GET" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end">
                <div class="w-full sm:max-w-sm">
                    <label for="album_id" class="mb-2 block text-sm font-semibold text-slate-700">Filter Album</label>
                    <select id="album_id" name="album_id" class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                        <option value="">Semua Album</option>
                        @foreach ($albums as $album)
                            <option value="{{ $album->id }}" @selected($selectedAlbumId === $album->id)>{{ $album->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="rounded-xl bg-slate-800 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700">Terapkan</button>
                    @if ($selectedAlbumId)
                        <a href="{{ route('admin.gallery-photos.index') }}" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Reset</a>
                    @endif
                </div>
            </div>
        </form>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse text-left text-sm text-slate-700">
                    <thead class="bg-slate-50 text-slate-700">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Foto</th>
                            <th class="px-4 py-3 font-semibold">Album</th>
                            <th class="px-4 py-3 font-semibold">Caption</th>
                            <th class="px-4 py-3 font-semibold">Status Album</th>
                            <th class="px-4 py-3 font-semibold">Urutan</th>
                            <th class="px-4 py-3 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($photos as $photo)
                            <tr class="border-t border-slate-200 align-top">
                                <td class="px-4 py-3">
                                    <img src="{{ asset('storage/' . $photo->image_path) }}" alt="{{ $photo->alt_text ?: $photo->caption ?: 'Foto galeri' }}" class="h-16 w-24 rounded-xl object-cover ring-1 ring-slate-200">
                                </td>
                                <td class="px-4 py-3 font-semibold text-slate-900">{{ $photo->album->title }}</td>
                                <td class="max-w-xs px-4 py-3">
                                    <p>{{ $photo->caption ?: '-' }}</p>
                                    @if ($photo->alt_text)
                                        <p class="mt-1 text-xs text-slate-500">Alt: {{ $photo->alt_text }}</p>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if ($photo->album->is_published)
                                        <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">Album tampil</span>
                                    @else
                                        <span class="rounded-full bg-slate-200 px-2.5 py-1 text-xs font-semibold text-slate-700">Album disembunyikan</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ $photo->sort_order }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.gallery-photos.edit', $photo) }}" class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-700 transition hover:bg-amber-100">Edit</a>
                                        <form action="{{ route('admin.gallery-photos.destroy', $photo) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700 transition hover:bg-red-100">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center text-slate-500">
                                    Belum ada foto galeri untuk ditampilkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($photos->hasPages())
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                {{ $photos->links() }}
            </div>
        @endif
    </div>
@endsection
