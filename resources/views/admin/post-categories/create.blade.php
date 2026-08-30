@extends('admin.layouts.app')

@section('title', 'Tambah Kategori Berita')

@section('content')
    <div class="mx-auto max-w-3xl space-y-6">
        <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">Konten</p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">Tambah Kategori Berita</h1>
                <p class="mt-2 text-sm text-slate-500">Buat kategori untuk mengelompokkan berita dan kegiatan sekolah.</p>
            </div>
            <a href="{{ route('admin.post-categories.index') }}" class="text-sm font-semibold text-slate-600 transition hover:text-emerald-700">Kembali</a>
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

        <form action="{{ route('admin.post-categories.store') }}" method="POST" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            @csrf

            <div class="space-y-5">
                <div>
                    <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">Nama Kategori <span class="text-red-600">*</span></label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100" placeholder="Contoh: Kegiatan Sekolah">
                    @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="slug" class="mb-2 block text-sm font-semibold text-slate-700">Slug</label>
                    <input id="slug" name="slug" type="text" value="{{ old('slug') }}" class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100" placeholder="Dibuat otomatis dari nama jika dikosongkan">
                    <p class="mt-1 text-xs text-slate-500">Gunakan slug khusus bila diperlukan. Sistem akan membuat slug unik bila kolom ini kosong.</p>
                    @error('slug')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="mb-2 block text-sm font-semibold text-slate-700">Deskripsi</label>
                    <textarea id="description" name="description" rows="5" class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100" placeholder="Deskripsi singkat kategori (opsional)">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="rounded-xl bg-slate-50 p-4">
                    <input type="hidden" name="is_active" value="0">
                    <label class="flex cursor-pointer items-center gap-3">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true)) class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        <span>
                            <span class="block text-sm font-semibold text-slate-700">Kategori aktif</span>
                            <span class="block text-xs text-slate-500">Kategori aktif dapat digunakan untuk berita yang akan ditampilkan.</span>
                        </span>
                    </label>
                    @error('is_active')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex flex-col-reverse gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:justify-end">
                <a href="{{ route('admin.post-categories.index') }}" class="rounded-xl border border-slate-300 px-4 py-2.5 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Batal</a>
                <button type="submit" class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">Simpan Kategori</button>
            </div>
        </form>
    </div>
@endsection
