@extends('admin.layouts.app')

@section('title', 'Berita & Kegiatan')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">Konten</p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">Berita & Kegiatan</h1>
                <p class="mt-2 text-sm text-slate-500">Kelola informasi, pengumuman, dan kegiatan MI Islamiyah Syafi'iyah.</p>
            </div>
            <a href="{{ route('admin.posts.create') }}" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                + Tambah Berita
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
                            <th class="px-4 py-3 font-semibold">Thumbnail</th>
                            <th class="px-4 py-3 font-semibold">Judul Berita</th>
                            <th class="px-4 py-3 font-semibold">Kategori</th>
                            <th class="px-4 py-3 font-semibold">Tanggal</th>
                            <th class="px-4 py-3 font-semibold">Status</th>
                            <th class="px-4 py-3 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($posts as $post)
                            <tr class="border-t border-slate-200 align-top">
                                <td class="px-4 py-3">
                                    @if ($post->featured_image_path)
                                        <img src="{{ asset('storage/' . $post->featured_image_path) }}" alt="{{ $post->title }}" class="h-14 w-20 rounded-xl object-cover ring-1 ring-slate-200">
                                    @else
                                        <div class="flex h-14 w-20 items-center justify-center rounded-xl bg-emerald-100 text-xs font-bold text-emerald-700">
                                            MIIS
                                        </div>
                                    @endif
                                </td>
                                <td class="max-w-sm px-4 py-3">
                                    <p class="font-semibold text-slate-900">{{ $post->title }}</p>
                                    <p class="mt-1 text-xs leading-5 text-slate-500">{{ \Illuminate\Support\Str::limit($post->excerpt ?: $post->content, 95) }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">{{ $post->category?->name ?? '-' }}</span>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-slate-600">
                                    {{ $post->published_at?->format('d M Y, H:i') ?? 'Belum dipublikasikan' }}
                                </td>
                                <td class="px-4 py-3">
                                    @if ($post->status === 'published')
                                        <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">Dipublikasikan</span>
                                    @else
                                        <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700">Draft</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.posts.edit', $post) }}" class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-700 transition hover:bg-amber-100">Edit</a>
                                        <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus berita atau kegiatan ini?')">
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
                                    Belum ada berita atau kegiatan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($posts->hasPages())
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
@endsection
