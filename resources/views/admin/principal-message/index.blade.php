@extends('admin.layouts.app')

@section('title', 'Sambutan Kepala Madrasah')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">Master Sekolah</p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">Sambutan Kepala Madrasah</h1>
            </div>
            <a href="{{ route('admin.principal-message.create') }}" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                + Tambah Sambutan
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
                            <th class="px-4 py-3 font-semibold">Nama</th>
                            <th class="px-4 py-3 font-semibold">Jabatan</th>
                            <th class="px-4 py-3 font-semibold">Judul</th>
                            <th class="px-4 py-3 font-semibold">Status</th>
                            <th class="px-4 py-3 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($principalMessages as $message)
                            <tr class="border-t border-slate-200">
                                <td class="px-4 py-3 font-medium text-slate-900">{{ $message->name }}</td>
                                <td class="px-4 py-3">{{ $message->position }}</td>
                                <td class="px-4 py-3">{{ $message->title ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    @if ($message->is_active)
                                        <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">Aktif</span>
                                    @else
                                        <span class="rounded-full bg-slate-200 px-2.5 py-1 text-xs font-semibold text-slate-700">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.principal-message.edit', $message) }}" class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-700 hover:bg-amber-100">Edit</a>
                                        <form action="{{ route('admin.principal-message.destroy', $message) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus sambutan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700 hover:bg-red-100">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-12 text-center text-slate-500">
                                    Belum ada data sambutan kepala madrasah.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($principalMessages->hasPages())
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                {{ $principalMessages->links() }}
            </div>
        @endif
    </div>
@endsection
