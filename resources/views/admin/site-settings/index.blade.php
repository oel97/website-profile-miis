@extends('admin.layouts.app')

@section('title', 'Pengaturan Website')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">Pengaturan</p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">Pengaturan Website</h1>
                <p class="mt-2 text-sm text-slate-500">Kelola konfigurasi umum untuk website MI Islamiyah Syafi'iyah.</p>
            </div>
            <a href="{{ route('admin.site-settings.create') }}" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                + Tambah Pengaturan
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
                            <th class="px-6 py-4">Kelompok</th>
                            <th class="px-6 py-4">Key</th>
                            <th class="px-6 py-4">Nilai</th>
                            <th class="px-6 py-4">Tipe</th>
                            <th class="px-6 py-4">Visibilitas</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse ($siteSettings as $siteSetting)
                            <tr class="hover:bg-slate-50/70">
                                <td class="px-6 py-4">
                                    <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">{{ $siteSetting->group }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <code class="rounded bg-slate-100 px-2 py-1 text-xs font-medium text-slate-700">{{ $siteSetting->key }}</code>
                                </td>
                                <td class="max-w-xs px-6 py-4">
                                    <p class="truncate text-slate-600" title="{{ $siteSetting->value }}">{{ $siteSetting->value }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="rounded bg-slate-100 px-2 py-1 text-xs font-medium text-slate-600">{{ $siteSetting->type }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span @class([
                                        'inline-flex rounded-full px-2.5 py-1 text-xs font-semibold',
                                        'bg-emerald-100 text-emerald-700' => $siteSetting->is_public,
                                        'bg-slate-100 text-slate-600' => ! $siteSetting->is_public,
                                    ])>
                                        {{ $siteSetting->is_public ? 'Publik' : 'Internal' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.site-settings.edit', $siteSetting) }}" class="rounded-lg border border-slate-300 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:border-emerald-600 hover:text-emerald-700">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.site-settings.destroy', $siteSetting) }}" method="POST" onsubmit="return confirm('Hapus pengaturan website ini?')">
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
                                    Belum ada pengaturan website. Tambahkan konfigurasi umum untuk mulai mengelolanya dari CMS.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($siteSettings->hasPages())
                <div class="border-t border-slate-200 px-6 py-4">
                    {{ $siteSettings->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
