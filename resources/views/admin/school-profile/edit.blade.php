@extends('admin.layouts.app')

@section('title', 'Edit Profil Sekolah')

@section('content')
    @php
        $activeSection = in_array(request('section'), ['about', 'history', 'vision-mission'], true)
            ? request('section')
            : 'about';
    @endphp

    <div class="mx-auto max-w-5xl space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">Master Sekolah</p>
            <h1 class="mt-2 text-3xl font-bold text-slate-900">Edit Profil Sekolah</h1>
            <p class="mt-2 text-sm leading-6 text-slate-500">Data utama dan konten halaman Profil dikelola melalui form terpisah agar lebih mudah diperbarui.</p>
        </div>

        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <ul class="list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.school-profile.update', $school_profile) }}" method="POST" enctype="multipart/form-data" class="space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            @csrf
            @method('PUT')

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Nama Sekolah</label>
                    <input type="text" name="name" value="{{ old('name', $school_profile->name) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100" required>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Nama Singkat</label>
                    <input type="text" name="npsn" value="{{ old('npsn', $school_profile->npsn) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Alamat</label>
                    <textarea name="address" rows="3" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100" required>{{ old('address', $school_profile->address) }}</textarea>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Nomor Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $school_profile->phone) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Email</label>
                    <input type="email" name="email" value="{{ old('email', $school_profile->email) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Website</label>
                    <input type="url" name="website" value="{{ old('website', $school_profile->website) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Akreditasi</label>
                    <input type="text" name="accreditation" value="{{ old('accreditation', $school_profile->accreditation) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Tagline</label>
                    <input type="text" name="tagline" value="{{ old('tagline', $school_profile->tagline) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">WhatsApp</label>
                    <input type="text" name="whatsapp" value="{{ old('whatsapp', $school_profile->whatsapp) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Kecamatan</label>
                    <input type="text" name="district" value="{{ old('district', $school_profile->district) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Kabupaten</label>
                    <input type="text" name="regency" value="{{ old('regency', $school_profile->regency) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Provinsi</label>
                    <input type="text" name="province" value="{{ old('province', $school_profile->province) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Kode Pos</label>
                    <input type="text" name="postal_code" value="{{ old('postal_code', $school_profile->postal_code) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                </div>

                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-slate-700">Deskripsi Singkat</label>
                    <textarea name="short_description" rows="3" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">{{ old('short_description', $school_profile->short_description) }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-slate-700">Logo Sekolah</label>
                    @if ($school_profile->logo_path)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $school_profile->logo_path) }}" alt="Logo sekolah" class="h-20 w-20 rounded-xl object-cover border border-slate-200">
                        </div>
                    @endif
                    <x-admin.file-upload name="logo" empty-text="Biarkan kosong untuk mempertahankan logo saat ini." help-text="Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB." />
                </div>

                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-slate-700">Foto Sekolah</label>
                    @if ($school_profile->foto_sekolah_path)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $school_profile->foto_sekolah_path) }}" alt="Foto sekolah" class="h-32 w-full rounded-xl object-cover border border-slate-200">
                        </div>
                    @endif
                    <x-admin.file-upload name="foto_sekolah" empty-text="Biarkan kosong untuk mempertahankan foto saat ini." help-text="Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB." />
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('admin.school-profile.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Batal</a>
                <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700">Simpan Data Utama</button>
            </div>
        </form>

        <section class="space-y-6" aria-labelledby="profile-content-heading">
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">Halaman Publik</p>
                <h2 id="profile-content-heading" class="mt-2 text-2xl font-bold text-emerald-950">Konten Profil Sekolah</h2>
                <p class="mt-2 text-sm leading-6 text-emerald-800">Pilih satu bagian di bawah ini. Hanya formulir dari bagian yang dipilih yang akan ditampilkan.</p>
            </div>

            <nav class="grid gap-3 sm:grid-cols-3" aria-label="Pilih konten profil yang ingin diedit">
                <a href="{{ route('admin.school-profile.edit', ['school_profile' => $school_profile, 'section' => 'about']) }}" @class([
                    'rounded-2xl border px-4 py-4 text-sm font-semibold transition',
                    'border-emerald-600 bg-emerald-600 text-white shadow-sm' => $activeSection === 'about',
                    'border-slate-200 bg-white text-slate-700 hover:border-emerald-300 hover:bg-emerald-50' => $activeSection !== 'about',
                ])>
                    Tentang Madrasah
                </a>
                <a href="{{ route('admin.school-profile.edit', ['school_profile' => $school_profile, 'section' => 'history']) }}" @class([
                    'rounded-2xl border px-4 py-4 text-sm font-semibold transition',
                    'border-emerald-600 bg-emerald-600 text-white shadow-sm' => $activeSection === 'history',
                    'border-slate-200 bg-white text-slate-700 hover:border-emerald-300 hover:bg-emerald-50' => $activeSection !== 'history',
                ])>
                    Sejarah Sekolah
                </a>
                <a href="{{ route('admin.school-profile.edit', ['school_profile' => $school_profile, 'section' => 'vision-mission']) }}" @class([
                    'rounded-2xl border px-4 py-4 text-sm font-semibold transition',
                    'border-emerald-600 bg-emerald-600 text-white shadow-sm' => $activeSection === 'vision-mission',
                    'border-slate-200 bg-white text-slate-700 hover:border-emerald-300 hover:bg-emerald-50' => $activeSection !== 'vision-mission',
                ])>
                    Visi & Misi
                </a>
            </nav>

            @if ($activeSection === 'about')
            <form action="{{ route('admin.school-profile.content.update', ['school_profile' => $school_profile, 'section' => 'about']) }}" method="POST" class="space-y-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                @csrf
                @method('PUT')

                <div>
                    <p class="text-sm font-semibold text-emerald-800">Tentang Madrasah</p>
                    <p class="mt-1 text-xs leading-5 text-slate-500">Deskripsi utama yang tampil pada bagian Tentang Kami.</p>
                </div>
                <div>
                    <label for="about" class="mb-2 block text-sm font-medium text-slate-700">Isi Tentang Madrasah</label>
                    <textarea id="about" name="about" rows="7" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100" placeholder="Tuliskan gambaran umum madrasah.">{{ old('about', $school_profile->about) }}</textarea>
                    @error('about')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">Simpan Tentang Madrasah</button>
                </div>
            </form>
            @endif

            @if ($activeSection === 'history')
            <form action="{{ route('admin.school-profile.content.update', ['school_profile' => $school_profile, 'section' => 'history']) }}" method="POST" enctype="multipart/form-data" class="space-y-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                @csrf
                @method('PUT')

                <div>
                    <p class="text-sm font-semibold text-emerald-800">Sejarah Sekolah</p>
                    <p class="mt-1 text-xs leading-5 text-slate-500">Konten ini akan tampil pada bagian Sejarah di halaman Profil Sekolah.</p>
                </div>
                <div>
                    <label for="history_title" class="mb-2 block text-sm font-medium text-slate-700">Judul Sejarah</label>
                    <input id="history_title" type="text" name="history_title" value="{{ old('history_title', $historyPage?->title ?? 'Sejarah Madrasah') }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                    @error('history_title')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="history_content" class="mb-2 block text-sm font-medium text-slate-700">Isi Sejarah</label>
                    <textarea id="history_content" name="history_content" rows="8" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100" placeholder="Tuliskan perjalanan dan perkembangan madrasah.">{{ old('history_content', $historyPage?->content) }}</textarea>
                    @error('history_content')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Gambar Sejarah</label>
                    <p class="mb-3 text-xs leading-5 text-slate-500">Gambar ini akan menggantikan panel ilustrasi di sisi kanan bagian Sejarah pada halaman Profil Sekolah.</p>
                    @if ($historyPage?->cover_image_path)
                        <img src="{{ asset('storage/' . $historyPage->cover_image_path) }}" alt="Gambar sejarah saat ini" class="mb-3 h-48 w-full max-w-lg rounded-xl border border-slate-200 object-cover">
                    @endif
                    <x-admin.file-upload id="history_image" name="history_image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" empty-text="Biarkan kosong untuk mempertahankan gambar sejarah saat ini." help-text="Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB." />
                    @error('history_image')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">Simpan Sejarah Sekolah</button>
                </div>
            </form>
            @endif

            @if ($activeSection === 'vision-mission')
            <form action="{{ route('admin.school-profile.content.update', ['school_profile' => $school_profile, 'section' => 'vision-mission']) }}" method="POST" class="space-y-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                @csrf
                @method('PUT')

                <div>
                    <p class="text-sm font-semibold text-emerald-800">Visi & Misi</p>
                    <p class="mt-1 text-xs leading-5 text-slate-500">Gunakan baris baru untuk memisahkan visi dan setiap poin misi.</p>
                </div>
                <div>
                    <label for="vision_mission_title" class="mb-2 block text-sm font-medium text-slate-700">Judul Visi & Misi</label>
                    <input id="vision_mission_title" type="text" name="vision_mission_title" value="{{ old('vision_mission_title', $visionMissionPage?->title ?? 'Visi dan Misi') }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                    @error('vision_mission_title')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="vision_mission_content" class="mb-2 block text-sm font-medium text-slate-700">Isi Visi & Misi</label>
                    <textarea id="vision_mission_content" name="vision_mission_content" rows="9" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100" placeholder="Visi: ...&#10;&#10;Misi:&#10;1. ...&#10;2. ...">{{ old('vision_mission_content', $visionMissionPage?->content) }}</textarea>
                    @error('vision_mission_content')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">Simpan Visi & Misi</button>
                </div>
            </form>
            @endif
        </section>
    </div>
@endsection
