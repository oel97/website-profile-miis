@extends('admin.layouts.app')

@section('title', 'Langkah Pendaftaran PPDB')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">PPDB</p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">Langkah Pendaftaran</h1>
                <p class="mt-2 text-sm text-slate-500">Kelola urutan proses pendaftaran untuk setiap periode PPDB.</p>
            </div>
            @if ($periods->isNotEmpty())
                <a href="{{ route('admin.ppdb-steps.create') }}" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                    + Tambah Langkah
                </a>
            @endif
        </div>

        @if ($periods->isEmpty())
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
                Buat <a href="{{ route('admin.ppdb-periods.create') }}" class="font-semibold underline">Periode PPDB</a> terlebih dahulu sebelum menambahkan langkah pendaftaran.
            </div>
        @endif

        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.ppdb-steps.index') }}" method="GET" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end">
                <div class="w-full sm:max-w-md">
                    <label for="ppdb_period_id" class="mb-2 block text-sm font-semibold text-slate-700">Filter Periode PPDB</label>
                    <select id="ppdb_period_id" name="ppdb_period_id" class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                        <option value="">Semua Periode</option>
                        @foreach ($periods as $period)
                            <option value="{{ $period->id }}" @selected($selectedPeriodId === $period->id)>{{ $period->title }} - {{ $period->academic_year }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="rounded-xl bg-slate-800 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700">Terapkan</button>
                    @if ($selectedPeriodId)
                        <a href="{{ route('admin.ppdb-steps.index') }}" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Reset</a>
                    @endif
                </div>
            </div>
        </form>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse text-left text-sm text-slate-700">
                    <thead class="bg-slate-50 text-slate-700">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Periode PPDB</th>
                            <th class="px-4 py-3 font-semibold">Urutan</th>
                            <th class="px-4 py-3 font-semibold">Langkah Pendaftaran</th>
                            <th class="px-4 py-3 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($steps as $step)
                            <tr class="border-t border-slate-200 align-top">
                                <td class="max-w-xs px-4 py-3">
                                    <p class="font-semibold text-slate-900">{{ $step->period->title }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $step->period->academic_year }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex h-8 min-w-8 items-center justify-center rounded-full bg-emerald-100 px-2 text-xs font-bold text-emerald-700">{{ $step->sort_order }}</span>
                                </td>
                                <td class="max-w-xl px-4 py-3">
                                    <p class="font-semibold text-slate-900">{{ $step->title }}</p>
                                    @if ($step->description)
                                        <p class="mt-1 text-xs leading-5 text-slate-500">{{ \Illuminate\Support\Str::limit($step->description, 130) }}</p>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.ppdb-steps.edit', $step) }}" class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-700 transition hover:bg-amber-100">Edit</a>
                                        <form action="{{ route('admin.ppdb-steps.destroy', $step) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus langkah ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700 transition hover:bg-red-100">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-12 text-center text-slate-500">
                                    Belum ada langkah pendaftaran PPDB.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($steps->hasPages())
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                {{ $steps->links() }}
            </div>
        @endif
    </div>
@endsection
