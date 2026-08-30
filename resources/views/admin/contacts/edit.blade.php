@extends('admin.layouts.app')

@section('title', 'Edit Kontak Sekolah')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">
        <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">Pengaturan</p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">Edit Kontak Sekolah</h1>
                <p class="mt-2 text-sm text-slate-500">Perbarui informasi {{ $contact->label }}.</p>
            </div>
            <a href="{{ route('admin.contacts.index') }}" class="text-sm font-semibold text-slate-600 transition hover:text-emerald-700">Kembali</a>
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

        <form action="{{ route('admin.contacts.update', $contact) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="label" class="mb-2 block text-sm font-semibold text-slate-700">Nama Kontak <span class="text-red-600">*</span></label>
                        <input id="label" name="label" type="text" value="{{ old('label', $contact->label) }}" required autofocus class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                        @error('label')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="type" class="mb-2 block text-sm font-semibold text-slate-700">Jenis Kontak <span class="text-red-600">*</span></label>
                        <select id="type" name="type" required class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                            <option value="address" @selected(old('type', $contact->type) === 'address')>Alamat</option>
                            <option value="phone" @selected(old('type', $contact->type) === 'phone')>Telepon</option>
                            <option value="whatsapp" @selected(old('type', $contact->type) === 'whatsapp')>WhatsApp</option>
                            <option value="email" @selected(old('type', $contact->type) === 'email')>Email</option>
                            <option value="website" @selected(old('type', $contact->type) === 'website')>Website</option>
                            <option value="maps" @selected(old('type', $contact->type) === 'maps')>Google Maps</option>
                            <option value="other" @selected(old('type', $contact->type) === 'other')>Lainnya</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="value" class="mb-2 block text-sm font-semibold text-slate-700">Informasi Kontak <span class="text-red-600">*</span></label>
                        <input id="value" name="value" type="text" value="{{ old('value', $contact->value) }}" required class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                        @error('value')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="url" class="mb-2 block text-sm font-semibold text-slate-700">Tautan Resmi <span class="font-normal text-slate-400">(opsional)</span></label>
                        <input id="url" name="url" type="text" inputmode="url" value="{{ old('url', $contact->url) }}" class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100" placeholder="https://...">
                        <p class="mt-1 text-xs text-slate-500">Biarkan kosong untuk Telepon atau WhatsApp; nomor akan otomatis dapat ditekan di website. Wajib diisi dengan URL lengkap untuk Website dan Google Maps.</p>
                        @error('url')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="sort_order" class="mb-2 block text-sm font-semibold text-slate-700">Urutan Tampil</label>
                        <input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $contact->sort_order) }}" class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                        @error('sort_order')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <input type="hidden" name="is_active" value="0">
                <label class="flex cursor-pointer items-center gap-3">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $contact->is_active)) class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                    <span>
                        <span class="block text-sm font-semibold text-slate-700">Kontak aktif</span>
                        <span class="block text-xs text-slate-500">Nonaktifkan bila kontak belum ingin ditampilkan pada website publik.</span>
                    </span>
                </label>
                @error('is_active')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <a href="{{ route('admin.contacts.index') }}" class="rounded-xl border border-slate-300 px-4 py-2.5 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Batal</a>
                <button type="submit" class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">Simpan Perubahan</button>
            </div>
        </form>
    </div>
@endsection
