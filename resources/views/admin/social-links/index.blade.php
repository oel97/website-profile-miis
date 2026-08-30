@extends('admin.layouts.app')

@section('title', 'Sosial Media')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">Pengaturan</p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">Sosial Media Sekolah</h1>
                <p class="mt-2 text-sm text-slate-500">Kelola akun sosial media resmi MI Islamiyah Syafi'iyah.</p>
            </div>
            <a href="{{ route('admin.social-links.create') }}" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                + Tambah Sosial Media
            </a>
        </div>

        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-6 py-4">Platform</th>
                            <th class="px-6 py-4">URL</th>
                            <th class="px-6 py-4">Ikon</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Urutan</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse ($socialLinks as $socialLink)
                            <tr class="hover:bg-slate-50/70">
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-slate-900">{{ $socialLink->platform }}</p>
                                    @if ($socialLink->label)
                                        <p class="mt-1 text-xs text-slate-500">{{ $socialLink->label }}</p>
                                    @endif
                                </td>
                                <td class="max-w-xs px-6 py-4">
                                    <a href="{{ $socialLink->url }}" target="_blank" rel="noopener noreferrer" class="block truncate font-medium text-emerald-700 hover:text-emerald-800 hover:underline">
                                        {{ $socialLink->url }}
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($socialLink->icon)
                                        <code class="rounded bg-slate-100 px-2 py-1 text-xs text-slate-600">{{ $socialLink->icon }}</code>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span @class([
                                        'inline-flex rounded-full px-2.5 py-1 text-xs font-semibold',
                                        'bg-emerald-100 text-emerald-700' => $socialLink->is_active,
                                        'bg-slate-100 text-slate-600' => ! $socialLink->is_active,
                                    ])>
                                        {{ $socialLink->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">{{ $socialLink->sort_order }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.social-links.edit', $socialLink) }}" class="rounded-lg border border-slate-300 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:border-emerald-600 hover:text-emerald-700">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.social-links.destroy', $socialLink) }}" method="POST" onsubmit="return confirm('Hapus sosial media ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg border border-red-200 px-3 py-2 text-xs font-semibold text-red-700 transition hover:bg-red-50">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-sm text-slate-500">
                                    Belum ada data sosial media. Tambahkan akun resmi sekolah untuk mulai menampilkannya.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($socialLinks->hasPages())
                <div class="border-t border-slate-200 px-6 py-4">
                    {{ $socialLinks->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
