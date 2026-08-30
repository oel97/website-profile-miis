@extends('admin.layouts.app')

@section('title', 'Tambah Album Galeri')

@section('content')
    <div class="mx-auto max-w-5xl space-y-6">
        <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">Media</p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">Tambah Album Galeri</h1>
                <p class="mt-2 text-sm text-slate-500">Buat album baru untuk mengelompokkan dokumentasi kegiatan sekolah.</p>
            </div>
            <a href="{{ route('admin.gallery-albums.index') }}" class="text-sm font-semibold text-slate-600 transition hover:text-emerald-700">Kembali</a>
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

        <form action="{{ route('admin.gallery-albums.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="grid gap-5 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label for="title" class="mb-2 block text-sm font-semibold text-slate-700">Nama Album <span class="text-red-600">*</span></label>
                        <input id="title" name="title" type="text" value="{{ old('title') }}" required autofocus class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100" placeholder="Contoh: Pesantren Ramadan 1448 H">
                        @error('title')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="slug" class="mb-2 block text-sm font-semibold text-slate-700">Slug</label>
                        <input id="slug" name="slug" type="text" value="{{ old('slug') }}" class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100" placeholder="Dibuat otomatis dari nama album jika dikosongkan">
                        @error('slug')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="event_date" class="mb-2 block text-sm font-semibold text-slate-700">Tanggal Kegiatan</label>
                        <input id="event_date" name="event_date" type="date" value="{{ old('event_date') }}" class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                        @error('event_date')
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
                    <textarea id="description" name="description" rows="7" class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm leading-6 shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100" placeholder="Jelaskan isi atau konteks dokumentasi album ini.">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <label for="image" class="mb-2 block text-sm font-semibold text-slate-700">Cover Album</label>
                <input id="image" name="image" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-emerald-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-emerald-700 hover:file:bg-emerald-100">
                <p class="mt-2 text-xs text-slate-500">Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.</p>
                @error('image')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
                <img id="image-preview" src="" alt="Preview cover album" class="mt-4 hidden h-56 w-full rounded-xl object-cover ring-1 ring-slate-200">
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <input type="hidden" name="is_published" value="0">
                <label class="flex cursor-pointer items-center gap-3">
                    <input type="checkbox" name="is_published" value="1" @checked(old('is_published', true)) class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                    <span>
                        <span class="block text-sm font-semibold text-slate-700">Tampilkan di website</span>
                        <span class="block text-xs text-slate-500">Album yang aktif dapat ditampilkan pada halaman galeri publik nantinya.</span>
                    </span>
                </label>
                @error('is_published')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <a href="{{ route('admin.gallery-albums.index') }}" class="rounded-xl border border-slate-300 px-4 py-2.5 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Batal</a>
                <button type="submit" class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">Simpan Album</button>
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
