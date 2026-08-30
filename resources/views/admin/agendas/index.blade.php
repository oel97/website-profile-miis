@extends('admin.layouts.app')

@section('title', 'Agenda Sekolah')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">Konten</p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">Agenda Sekolah</h1>
                <p class="mt-2 text-sm text-slate-500">Kelola jadwal kegiatan MI Islamiyah Syafi'iyah.</p>
            </div>
            <a href="{{ route('admin.agendas.create') }}" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                + Tambah Agenda
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
                            <th class="px-4 py-3 font-semibold">Judul Agenda</th>
                            <th class="px-4 py-3 font-semibold">Tanggal</th>
                            <th class="px-4 py-3 font-semibold">Lokasi</th>
                            <th class="px-4 py-3 font-semibold">Status</th>
                            <th class="px-4 py-3 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($agendas as $agenda)
                            <tr class="border-t border-slate-200 align-top">
                                <td class="px-4 py-3">
                                    @if ($agenda->cover_image_path)
                                        <img src="{{ asset('storage/' . $agenda->cover_image_path) }}" alt="{{ $agenda->title }}" class="h-14 w-20 rounded-xl object-cover ring-1 ring-slate-200">
                                    @else
                                        <div class="flex h-14 w-20 items-center justify-center rounded-xl bg-emerald-100 text-xs font-bold text-emerald-700">
                                            MIIS
                                        </div>
                                    @endif
                                </td>
                                <td class="max-w-xs px-4 py-3">
                                    <p class="font-semibold text-slate-900">{{ $agenda->title }}</p>
                                    <p class="mt-1 text-xs leading-5 text-slate-500">{{ \Illuminate\Support\Str::limit($agenda->description, 80) }}</p>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-slate-600">
                                    <p>{{ $agenda->start_at->format('d M Y, H:i') }}</p>
                                    @if ($agenda->end_at)
                                        <p class="mt-1 text-xs text-slate-500">s.d. {{ $agenda->end_at->format('d M Y, H:i') }}</p>
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ $agenda->location ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    @if ($agenda->is_published)
                                        <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">Tampil</span>
                                    @else
                                        <span class="rounded-full bg-slate-200 px-2.5 py-1 text-xs font-semibold text-slate-700">Disembunyikan</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.agendas.edit', $agenda) }}" class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-700 transition hover:bg-amber-100">Edit</a>
                                        <form action="{{ route('admin.agendas.destroy', $agenda) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus agenda ini?')">
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
                                    Belum ada agenda sekolah.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($agendas->hasPages())
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                {{ $agendas->links() }}
            </div>
        @endif
    </div>
@endsection
