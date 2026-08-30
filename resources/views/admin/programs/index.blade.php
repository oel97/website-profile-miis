@extends('admin.layouts.app')

@section('title', 'Program Unggulan')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">Master Sekolah</p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">Program Unggulan</h1>
                <p class="mt-2 text-sm text-slate-500">Kelola program yang menjadi keunggulan MI Islamiyah Syafi'iyah.</p>
            </div>
            <a href="{{ route('admin.programs.create') }}" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                + Tambah Program
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
                            <th class="px-4 py-3 font-semibold">Nama Program</th>
                            <th class="px-4 py-3 font-semibold">Deskripsi Singkat</th>
                            <th class="px-4 py-3 font-semibold">Status</th>
                            <th class="px-4 py-3 font-semibold">Urutan</th>
                            <th class="px-4 py-3 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($programs as $program)
                            <tr class="border-t border-slate-200">
                                <td class="px-4 py-3">
                                    @if ($program->image_path)
                                        <img src="{{ asset('storage/' . $program->image_path) }}" alt="{{ $program->title }}" class="h-12 w-16 rounded-xl object-cover ring-1 ring-slate-200">
                                    @else
                                        <div class="flex h-12 w-16 items-center justify-center rounded-xl bg-emerald-100 text-sm font-bold text-emerald-700">
                                            {{ $program->icon ?: strtoupper(substr($program->title, 0, 1)) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 font-medium text-slate-900">{{ $program->title }}</td>
                                <td class="max-w-xs px-4 py-3 text-slate-600">
                                    {{ \Illuminate\Support\Str::limit($program->short_description ?: $program->description, 90) }}
                                </td>
                                <td class="px-4 py-3">
                                    @if ($program->is_active)
                                        <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">Aktif</span>
                                    @else
                                        <span class="rounded-full bg-slate-200 px-2.5 py-1 text-xs font-semibold text-slate-700">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ $program->sort_order }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.programs.edit', $program) }}" class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-700 transition hover:bg-amber-100">Edit</a>
                                        <form action="{{ route('admin.programs.destroy', $program) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus program ini?')">
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
                                    Belum ada program unggulan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($programs->hasPages())
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                {{ $programs->links() }}
            </div>
        @endif
    </div>
@endsection
