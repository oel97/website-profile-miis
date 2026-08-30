@extends('admin.layouts.app')

@section('title', 'Edit Periode PPDB')

@section('content')
    <div class="mx-auto max-w-5xl space-y-6">
        <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">PPDB</p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">Edit Periode PPDB</h1>
                <p class="mt-2 text-sm text-slate-500">Perbarui informasi {{ $ppdb_period->title }}.</p>
            </div>
            <a href="{{ route('admin.ppdb-periods.index') }}" class="text-sm font-semibold text-slate-600 transition hover:text-emerald-700">Kembali</a>
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

        <form action="{{ route('admin.ppdb-periods.update', $ppdb_period) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="academic_year" class="mb-2 block text-sm font-semibold text-slate-700">Tahun Ajaran <span class="text-red-600">*</span></label>
                        <input id="academic_year" name="academic_year" type="text" value="{{ old('academic_year', $ppdb_period->academic_year) }}" required autofocus class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                        @error('academic_year')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="title" class="mb-2 block text-sm font-semibold text-slate-700">Nama Periode <span class="text-red-600">*</span></label>
                        <input id="title" name="title" type="text" value="{{ old('title', $ppdb_period->title) }}" required class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                        @error('title')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="slug" class="mb-2 block text-sm font-semibold text-slate-700">Slug</label>
                        <input id="slug" name="slug" type="text" value="{{ old('slug', $ppdb_period->slug) }}" class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                        @error('slug')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="registration_start_at" class="mb-2 block text-sm font-semibold text-slate-700">Mulai Pendaftaran <span class="text-red-600">*</span></label>
                        <input id="registration_start_at" name="registration_start_at" type="datetime-local" value="{{ old('registration_start_at', $ppdb_period->registration_start_at?->format('Y-m-d\TH:i')) }}" required class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                        @error('registration_start_at')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="registration_end_at" class="mb-2 block text-sm font-semibold text-slate-700">Selesai Pendaftaran <span class="text-red-600">*</span></label>
                        <input id="registration_end_at" name="registration_end_at" type="datetime-local" value="{{ old('registration_end_at', $ppdb_period->registration_end_at?->format('Y-m-d\TH:i')) }}" required class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                        @error('registration_end_at')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="announcement_at" class="mb-2 block text-sm font-semibold text-slate-700">Tanggal Pengumuman <span class="font-normal text-slate-400">(opsional)</span></label>
                        <input id="announcement_at" name="announcement_at" type="datetime-local" value="{{ old('announcement_at', $ppdb_period->announcement_at?->format('Y-m-d\TH:i')) }}" class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                        @error('announcement_at')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="contact_whatsapp" class="mb-2 block text-sm font-semibold text-slate-700">WhatsApp PPDB <span class="font-normal text-slate-400">(opsional)</span></label>
                        <input id="contact_whatsapp" name="contact_whatsapp" type="text" value="{{ old('contact_whatsapp', $ppdb_period->contact_whatsapp) }}" class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                        @error('contact_whatsapp')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="registration_url" class="mb-2 block text-sm font-semibold text-slate-700">Tautan Pendaftaran <span class="font-normal text-slate-400">(opsional)</span></label>
                        <input id="registration_url" name="registration_url" type="url" value="{{ old('registration_url', $ppdb_period->registration_url) }}" class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                        @error('registration_url')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-5">
                    <label for="description" class="mb-2 block text-sm font-semibold text-slate-700">Deskripsi <span class="font-normal text-slate-400">(opsional)</span></label>
                    <textarea id="description" name="description" rows="7" class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm leading-6 shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">{{ old('description', $ppdb_period->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <label for="banner" class="mb-2 block text-sm font-semibold text-slate-700">Ganti Banner PPDB <span class="font-normal text-slate-400">(opsional)</span></label>
                <input id="banner" name="banner" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-emerald-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-emerald-700 hover:file:bg-emerald-100">
                <p class="mt-2 text-xs text-slate-500">Pilih banner baru untuk mengganti banner saat ini. Maksimal 2 MB.</p>
                @error('banner')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
                <img id="banner-preview" src="{{ $ppdb_period->banner_path ? asset('storage/' . $ppdb_period->banner_path) : '' }}" alt="Preview banner {{ $ppdb_period->title }}" @class([
                    'mt-4 h-56 w-full rounded-xl object-cover ring-1 ring-slate-200',
                    'hidden' => ! $ppdb_period->banner_path,
                ])>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <input type="hidden" name="is_active" value="0">
                    <label class="flex cursor-pointer items-center gap-3">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $ppdb_period->is_active)) class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        <span>
                            <span class="block text-sm font-semibold text-slate-700">Jadikan periode aktif</span>
                            <span class="block text-xs text-slate-500">Tandai jika pendaftaran periode ini sedang berjalan.</span>
                        </span>
                    </label>
                    @error('is_active')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <input type="hidden" name="is_published" value="0">
                    <label class="flex cursor-pointer items-center gap-3">
                        <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $ppdb_period->is_published)) class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        <span>
                            <span class="block text-sm font-semibold text-slate-700">Tampilkan di website</span>
                            <span class="block text-xs text-slate-500">Nonaktifkan bila periode belum ingin ditampilkan di halaman PPDB publik.</span>
                        </span>
                    </label>
                    @error('is_published')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <a href="{{ route('admin.ppdb-periods.index') }}" class="rounded-xl border border-slate-300 px-4 py-2.5 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Batal</a>
                <button type="submit" class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">Simpan Perubahan</button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            const bannerInput = document.getElementById('banner');
            const bannerPreview = document.getElementById('banner-preview');

            bannerInput.addEventListener('change', () => {
                const [file] = bannerInput.files;

                if (! file) {
                    return;
                }

                bannerPreview.src = URL.createObjectURL(file);
                bannerPreview.classList.remove('hidden');
            });
        </script>
    @endpush
@endsection
