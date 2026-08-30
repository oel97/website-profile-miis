@extends('admin.layouts.app')

@section('title', 'Tambah Persyaratan PPDB')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">
        <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">PPDB</p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">Tambah Persyaratan PPDB</h1>
                <p class="mt-2 text-sm text-slate-500">Tambahkan dokumen atau ketentuan bagi calon peserta didik.</p>
            </div>
            <a href="{{ route('admin.ppdb-requirements.index') }}" class="text-sm font-semibold text-slate-600 transition hover:text-emerald-700">Kembali</a>
        </div>

        @if ($periods->isEmpty())
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 text-sm text-amber-800">
                Belum ada periode PPDB. Silakan <a href="{{ route('admin.ppdb-periods.create') }}" class="font-semibold underline">buat periode terlebih dahulu</a>.
            </div>
        @else
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

            <form action="{{ route('admin.ppdb-requirements.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="grid gap-5 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label for="ppdb_period_id" class="mb-2 block text-sm font-semibold text-slate-700">Periode PPDB <span class="text-red-600">*</span></label>
                            <select id="ppdb_period_id" name="ppdb_period_id" required class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                                <option value="">Pilih periode PPDB</option>
                                @foreach ($periods as $period)
                                    <option value="{{ $period->id }}" @selected((string) old('ppdb_period_id') === (string) $period->id)>{{ $period->title }} — {{ $period->academic_year }}</option>
                                @endforeach
                            </select>
                            @error('ppdb_period_id')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="title" class="mb-2 block text-sm font-semibold text-slate-700">Nama Persyaratan <span class="text-red-600">*</span></label>
                            <input id="title" name="title" type="text" value="{{ old('title') }}" required autofocus class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100" placeholder="Contoh: Fotokopi kartu keluarga">
                            @error('title')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="sort_order" class="mb-2 block text-sm font-semibold text-slate-700">Urutan Tampil</label>
                            <input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', 0) }}" class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                            @error('sort_order')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-5">
                        <label for="description" class="mb-2 block text-sm font-semibold text-slate-700">Deskripsi <span class="font-normal text-slate-400">(opsional)</span></label>
                        <textarea id="description" name="description" rows="6" class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm leading-6 shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100" placeholder="Berikan penjelasan tambahan apabila diperlukan.">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <input type="hidden" name="is_active" value="0">
                    <label class="flex cursor-pointer items-center gap-3">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true)) class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        <span>
                            <span class="block text-sm font-semibold text-slate-700">Persyaratan aktif</span>
                            <span class="block text-xs text-slate-500">Hanya persyaratan aktif yang akan ditampilkan pada halaman PPDB publik nantinya.</span>
                        </span>
                    </label>
                    @error('is_active')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <a href="{{ route('admin.ppdb-requirements.index') }}" class="rounded-xl border border-slate-300 px-4 py-2.5 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Batal</a>
                    <button type="submit" class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">Simpan Persyaratan</button>
                </div>
            </form>
        @endif
    </div>
@endsection
