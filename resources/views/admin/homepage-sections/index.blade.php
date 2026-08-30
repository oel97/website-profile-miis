@extends('admin.layouts.app')

@section('title', 'Homepage Section')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">Beranda</p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">Homepage Section</h1>
                <p class="mt-2 text-sm text-slate-500">Atur judul, subjudul, status, urutan, dan jumlah item section di beranda.</p>
            </div>
            <a href="{{ route('admin.homepage-sections.create') }}" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                + Tambah Section
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
                            <th class="px-4 py-3 font-semibold">Section</th>
                            <th class="px-4 py-3 font-semibold">Judul Tampilan</th>
                            <th class="px-4 py-3 font-semibold">Status</th>
                            <th class="px-4 py-3 font-semibold">Batas Item</th>
                            <th class="px-4 py-3 font-semibold">Urutan</th>
                            <th class="px-4 py-3 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($homepageSections as $homepageSection)
                            <tr class="border-t border-slate-200">
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-slate-900">{{ \App\Models\HomepageSection::MANAGEABLE_SECTIONS[$homepageSection->key] ?? $homepageSection->key }}</p>
                                    <p class="mt-1 font-mono text-xs text-slate-500">{{ $homepageSection->key }}</p>
                                </td>
                                <td class="max-w-sm px-4 py-3">
                                    <p class="font-medium text-slate-800">{{ $homepageSection->title_override ?: 'Judul bawaan' }}</p>
                                    @if ($homepageSection->subtitle_override)
                                        <p class="mt-1 text-xs text-slate-500">{{ \Illuminate\Support\Str::limit($homepageSection->subtitle_override, 90) }}</p>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if ($homepageSection->is_active)
                                        <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">Aktif</span>
                                    @else
                                        <span class="rounded-full bg-slate-200 px-2.5 py-1 text-xs font-semibold text-slate-700">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ $homepageSection->item_limit ?: 'Bawaan' }}</td>
                                <td class="px-4 py-3">{{ $homepageSection->sort_order }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.homepage-sections.edit', $homepageSection) }}" class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-700 transition hover:bg-amber-100">Edit</a>
                                        <form action="{{ route('admin.homepage-sections.destroy', $homepageSection) }}" method="POST" onsubmit="return confirm('Hapus pengaturan section ini dan kembalikan section ke tampilan bawaan?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700 transition hover:bg-red-100">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center text-slate-500">Belum ada pengaturan section. Beranda tetap memakai judul dan jumlah item bawaan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($homepageSections->hasPages())
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                {{ $homepageSections->links() }}
            </div>
        @endif
    </div>
@endsection
