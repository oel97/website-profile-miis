@extends('admin.layouts.app')

@section('title', 'Tambah Agenda')

@section('content')
    <div class="mx-auto max-w-5xl space-y-6">
        <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">Konten</p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">Tambah Agenda</h1>
                <p class="mt-2 text-sm text-slate-500">Buat jadwal kegiatan baru untuk sekolah.</p>
            </div>
            <a href="{{ route('admin.agendas.index') }}" class="text-sm font-semibold text-slate-600 transition hover:text-emerald-700">Kembali</a>
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

        <form action="{{ route('admin.agendas.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="grid gap-5 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label for="title" class="mb-2 block text-sm font-semibold text-slate-700">Judul Agenda <span class="text-red-600">*</span></label>
                        <input id="title" name="title" type="text" value="{{ old('title') }}" required autofocus class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100" placeholder="Contoh: Rapat Komite dan Wali Murid">
                        @error('title')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="slug" class="mb-2 block text-sm font-semibold text-slate-700">Slug</label>
                        <input id="slug" name="slug" type="text" value="{{ old('slug') }}" class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100" placeholder="Dibuat otomatis dari judul jika dikosongkan">
                        @error('slug')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="location" class="mb-2 block text-sm font-semibold text-slate-700">Lokasi</label>
                        <input id="location" name="location" type="text" value="{{ old('location') }}" class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100" placeholder="Contoh: Aula Madrasah">
                        @error('location')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="start_at" class="mb-2 block text-sm font-semibold text-slate-700">Tanggal Mulai <span class="text-red-600">*</span></label>
                        <input id="start_at" name="start_at" type="datetime-local" value="{{ old('start_at') }}" required class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                        @error('start_at')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_at" class="mb-2 block text-sm font-semibold text-slate-700">Tanggal Selesai</label>
                        <input id="end_at" name="end_at" type="datetime-local" value="{{ old('end_at') }}" class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                        <p class="mt-1 text-xs text-slate-500">Kosongkan untuk agenda satu waktu atau satu hari.</p>
                        @error('end_at')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="registration_url" class="mb-2 block text-sm font-semibold text-slate-700">Tautan Pendaftaran</label>
                        <input id="registration_url" name="registration_url" type="url" value="{{ old('registration_url') }}" class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100" placeholder="https://...">
                        @error('registration_url')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-5">
                    <label for="description" class="mb-2 block text-sm font-semibold text-slate-700">Deskripsi Agenda <span class="text-red-600">*</span></label>
                    <textarea id="description" name="description" rows="9" required class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm leading-6 shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100" placeholder="Tuliskan informasi lengkap mengenai agenda sekolah">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <label for="image" class="mb-2 block text-sm font-semibold text-slate-700">Gambar Agenda</label>
                <input id="image" name="image" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-emerald-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-emerald-700 hover:file:bg-emerald-100">
                <p class="mt-2 text-xs text-slate-500">Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.</p>
                @error('image')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
                <img id="image-preview" src="" alt="Preview gambar agenda" class="mt-4 hidden h-52 w-full max-w-xl rounded-xl object-cover ring-1 ring-slate-200">
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <input type="hidden" name="is_published" value="0">
                <label class="flex cursor-pointer items-center gap-3">
                    <input type="checkbox" name="is_published" value="1" @checked(old('is_published', true)) class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                    <span>
                        <span class="block text-sm font-semibold text-slate-700">Tampilkan di website</span>
                        <span class="block text-xs text-slate-500">Agenda aktif akan tersedia pada halaman publik nantinya.</span>
                    </span>
                </label>
                @error('is_published')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <a href="{{ route('admin.agendas.index') }}" class="rounded-xl border border-slate-300 px-4 py-2.5 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Batal</a>
                <button type="submit" class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">Simpan Agenda</button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            const imageInput = document.getElementById('image');
            const imagePreview = document.getElementById('image-preview');

            imageInput.addEventListener('change', () => {
                const [file] = imageInput.files;

                if (! file) {
                    imagePreview.src = '';
                    imagePreview.classList.add('hidden');
                    return;
                }

                imagePreview.src = URL.createObjectURL(file);
                imagePreview.classList.remove('hidden');
            });
        </script>
    @endpush
@endsection
