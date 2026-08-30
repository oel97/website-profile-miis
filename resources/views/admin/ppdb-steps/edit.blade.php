@extends('admin.layouts.app')

@section('title', 'Edit Langkah Pendaftaran')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">
        <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">PPDB</p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">Edit Langkah Pendaftaran</h1>
                <p class="mt-2 text-sm text-slate-500">Perbarui informasi {{ $ppdb_step->title }}.</p>
            </div>
            <a href="{{ route('admin.ppdb-steps.index', ['ppdb_period_id' => $ppdb_step->ppdb_period_id]) }}" class="text-sm font-semibold text-slate-600 transition hover:text-emerald-700">Kembali</a>
        </div>

        @if ($errors->any())
            <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <p class="font-semibold">Data belum dapat disimpan.</p>
                <ul class="mt-2 list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.ppdb-steps.update', $ppdb_step) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="grid gap-5 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label for="ppdb_period_id" class="mb-2 block text-sm font-semibold text-slate-700">Periode PPDB <span class="text-red-600">*</span></label>
                        <select id="ppdb_period_id" name="ppdb_period_id" required class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                            @foreach ($periods as $period)
                                <option value="{{ $period->id }}" @selected((string) old('ppdb_period_id', $ppdb_step->ppdb_period_id) === (string) $period->id)>{{ $period->title }} - {{ $period->academic_year }}</option>
                            @endforeach
                        </select>
                        @error('ppdb_period_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="title" class="mb-2 block text-sm font-semibold text-slate-700">Judul Langkah <span class="text-red-600">*</span></label>
                        <input id="title" name="title" type="text" value="{{ old('title', $ppdb_step->title) }}" required autofocus class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                        @error('title')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="sort_order" class="mb-2 block text-sm font-semibold text-slate-700">Nomor Urutan</label>
                        <input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $ppdb_step->sort_order) }}" class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                        @error('sort_order')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-5">
                    <label for="description" class="mb-2 block text-sm font-semibold text-slate-700">Deskripsi Langkah <span class="font-normal text-slate-400">(opsional)</span></label>
                    <textarea id="description" name="description" rows="6" class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm leading-6 shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">{{ old('description', $ppdb_step->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <a href="{{ route('admin.ppdb-steps.index', ['ppdb_period_id' => $ppdb_step->ppdb_period_id]) }}" class="rounded-xl border border-slate-300 px-4 py-2.5 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Batal</a>
                <button type="submit" class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">Simpan Perubahan</button>
            </div>
        </form>
    </div>
@endsection
