@extends('admin.layouts.app')

@section('title', 'Edit Website Setting')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">
        <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">Pengaturan</p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">Edit Website Setting</h1>
                <p class="mt-2 text-sm text-slate-500">Perbarui nilai untuk key <code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs text-slate-700">{{ $siteSetting->key }}</code>.</p>
            </div>
            <a href="{{ route('admin.site-settings.index') }}" class="text-sm font-semibold text-slate-600 transition hover:text-emerald-700">Kembali</a>
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

        <form action="{{ route('admin.site-settings.update', $siteSetting) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="group" class="mb-2 block text-sm font-semibold text-slate-700">Kelompok <span class="text-red-600">*</span></label>
                        <input id="group" name="group" type="text" value="{{ old('group', $siteSetting->group) }}" required autofocus maxlength="50" class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                        @error('group')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="key" class="mb-2 block text-sm font-semibold text-slate-700">Key Pengaturan <span class="text-red-600">*</span></label>
                        <input id="key" name="key" type="text" value="{{ old('key', $siteSetting->key) }}" required maxlength="255" class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                        @error('key')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="type" class="mb-2 block text-sm font-semibold text-slate-700">Tipe Data <span class="text-red-600">*</span></label>
                        <select id="type" name="type" required class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                            <option value="text" @selected(old('type', $siteSetting->type) === 'text')>Teks</option>
                            <option value="textarea" @selected(old('type', $siteSetting->type) === 'textarea')>Teks Panjang</option>
                            <option value="url" @selected(old('type', $siteSetting->type) === 'url')>URL</option>
                            <option value="email" @selected(old('type', $siteSetting->type) === 'email')>Email</option>
                            <option value="image" @selected(old('type', $siteSetting->type) === 'image')>Path Gambar</option>
                            <option value="boolean" @selected(old('type', $siteSetting->type) === 'boolean')>Boolean</option>
                            <option value="json" @selected(old('type', $siteSetting->type) === 'json')>JSON</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="is_public" class="mb-2 block text-sm font-semibold text-slate-700">Visibilitas <span class="text-red-600">*</span></label>
                        <select id="is_public" name="is_public" required class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                            <option value="0" @selected(old('is_public', $siteSetting->is_public) == false)>Internal</option>
                            <option value="1" @selected(old('is_public', $siteSetting->is_public) == true)>Publik</option>
                        </select>
                        @error('is_public')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="value" class="mb-2 block text-sm font-semibold text-slate-700">Nilai Pengaturan <span class="text-red-600">*</span></label>
                        <textarea id="value" name="value" rows="6" required class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">{{ old('value', $siteSetting->value) }}</textarea>
                        <p class="mt-1 text-xs text-slate-500">Untuk tipe URL, Email, Boolean, atau JSON, nilai akan divalidasi sesuai tipe yang dipilih.</p>
                        @error('value')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <a href="{{ route('admin.site-settings.index') }}" class="rounded-xl border border-slate-300 px-4 py-2.5 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Batal</a>
                <button type="submit" class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">Simpan Perubahan</button>
            </div>
        </form>
    </div>
@endsection
