@extends('admin.layouts.app')

@section('title', 'Tambah Profil Sekolah')

@section('content')
    <div class="mx-auto max-w-5xl space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">Master Sekolah</p>
            <h1 class="mt-2 text-3xl font-bold text-slate-900">Tambah Profil Sekolah</h1>
            <p class="mt-2 text-sm leading-6 text-slate-500">Simpan data utama terlebih dahulu. Form Tentang Madrasah, Sejarah, serta Visi & Misi tersedia terpisah setelah profil dibuat.</p>
        </div>

        @if ($errors->any())
            <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <ul class="list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.school-profile.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            @csrf

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Nama Sekolah</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100" required>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Nama Singkat</label>
                    <input type="text" name="npsn" value="{{ old('npsn') }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Alamat</label>
                    <textarea name="address" rows="3" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100" required>{{ old('address') }}</textarea>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Nomor Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Website</label>
                    <input type="url" name="website" value="{{ old('website') }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Akreditasi</label>
                    <input type="text" name="accreditation" value="{{ old('accreditation') }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Tagline</label>
                    <input type="text" name="tagline" value="{{ old('tagline') }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">WhatsApp</label>
                    <input type="text" name="whatsapp" value="{{ old('whatsapp') }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Kecamatan</label>
                    <input type="text" name="district" value="{{ old('district') }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Kabupaten</label>
                    <input type="text" name="regency" value="{{ old('regency') }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Provinsi</label>
                    <input type="text" name="province" value="{{ old('province') }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Kode Pos</label>
                    <input type="text" name="postal_code" value="{{ old('postal_code') }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                </div>

                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-slate-700">Deskripsi Singkat</label>
                    <textarea name="short_description" rows="3" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">{{ old('short_description') }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-slate-700">Logo Sekolah</label>
                    <x-admin.file-upload name="logo" help-text="Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB." />
                </div>

                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-slate-700">Foto Sekolah</label>
                    <x-admin.file-upload name="foto_sekolah" help-text="Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB." />
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('admin.school-profile.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Batal</a>
                <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700">Simpan</button>
            </div>
        </form>
    </div>
@endsection
