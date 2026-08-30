@extends('admin.layouts.app')

@section('title', 'Tambah Berita & Kegiatan')

@section('content')
    <div class="mx-auto max-w-5xl space-y-6">
        <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">Konten</p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">Tambah Berita & Kegiatan</h1>
                <p class="mt-2 text-sm text-slate-500">Buat informasi baru untuk website MI Islamiyah Syafi'iyah.</p>
            </div>
            <a href="{{ route('admin.posts.index') }}" class="text-sm font-semibold text-slate-600 transition hover:text-emerald-700">Kembali</a>
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

        <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="post_category_id" class="mb-2 block text-sm font-semibold text-slate-700">Kategori Berita <span class="text-red-600">*</span></label>
                        <select id="post_category_id" name="post_category_id" required class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                            <option value="">Pilih kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected((string) old('post_category_id') === (string) $category->id)>
                                    {{ $category->name }}{{ $category->is_active ? '' : ' (nonaktif)' }}
                                </option>
                            @endforeach
                        </select>
                        @error('post_category_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="mb-2 block text-sm font-semibold text-slate-700">Status <span class="text-red-600">*</span></label>
                        <select id="status" name="status" required class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                            <option value="draft" @selected(old('status', 'draft') === 'draft')>Draft</option>
                            <option value="published" @selected(old('status') === 'published')>Publikasikan</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="title" class="mb-2 block text-sm font-semibold text-slate-700">Judul Berita <span class="text-red-600">*</span></label>
                        <input id="title" name="title" type="text" value="{{ old('title') }}" required autofocus class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100" placeholder="Masukkan judul berita atau kegiatan">
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
                        <label for="published_at" class="mb-2 block text-sm font-semibold text-slate-700">Tanggal Publikasi</label>
                        <input id="published_at" name="published_at" type="datetime-local" value="{{ old('published_at') }}" class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                        <p class="mt-1 text-xs text-slate-500">Untuk status publikasi, kosongkan agar memakai waktu saat disimpan.</p>
                        @error('published_at')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="space-y-5">
                    <div>
                        <label for="excerpt" class="mb-2 block text-sm font-semibold text-slate-700">Ringkasan Berita</label>
                        <textarea id="excerpt" name="excerpt" rows="3" class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100" placeholder="Ringkasan singkat untuk kartu berita (opsional)">{{ old('excerpt') }}</textarea>
                        @error('excerpt')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="content" class="mb-2 block text-sm font-semibold text-slate-700">Isi Berita <span class="text-red-600">*</span></label>
                        <textarea id="content" name="content" rows="12" required class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm leading-6 shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100" placeholder="Tulis isi berita atau kegiatan secara lengkap">{{ old('content') }}</textarea>
                        @error('content')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <label for="thumbnail" class="mb-2 block text-sm font-semibold text-slate-700">Thumbnail Berita</label>
                <x-admin.file-upload id="thumbnail" name="thumbnail" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" help-text="Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB." />
                @error('thumbnail')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
                <img id="thumbnail-preview" src="" alt="Preview thumbnail" class="mt-4 hidden h-48 w-full max-w-md rounded-xl object-cover ring-1 ring-slate-200">
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-sm font-semibold text-slate-700">Optimasi Mesin Pencari <span class="font-normal text-slate-400">(opsional)</span></p>
                <div class="mt-5 grid gap-5">
                    <div>
                        <label for="meta_title" class="mb-2 block text-sm font-medium text-slate-700">Meta Title</label>
                        <input id="meta_title" name="meta_title" type="text" value="{{ old('meta_title') }}" class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                        @error('meta_title')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="meta_description" class="mb-2 block text-sm font-medium text-slate-700">Meta Description</label>
                        <textarea id="meta_description" name="meta_description" rows="3" class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">{{ old('meta_description') }}</textarea>
                        @error('meta_description')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <a href="{{ route('admin.posts.index') }}" class="rounded-xl border border-slate-300 px-4 py-2.5 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Batal</a>
                <button type="submit" class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">Simpan Berita</button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            const thumbnailInput = document.getElementById('thumbnail');
            const thumbnailPreview = document.getElementById('thumbnail-preview');

            thumbnailInput.addEventListener('change', () => {
                const [file] = thumbnailInput.files;

                if (! file) {
                    thumbnailPreview.src = '';
                    thumbnailPreview.classList.add('hidden');
                    return;
                }

                thumbnailPreview.src = URL.createObjectURL(file);
                thumbnailPreview.classList.remove('hidden');
            });
        </script>
    @endpush
@endsection
