@extends('admin.layouts.app')

@section('title', 'Edit Foto Galeri')

@section('content')
    <div class="mx-auto max-w-5xl space-y-6">
        <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">Media</p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">Edit Foto Galeri</h1>
                <p class="mt-2 text-sm text-slate-500">Perbarui album, keterangan, atau gambar dokumentasi ini.</p>
            </div>
            <a href="{{ route('admin.gallery-photos.index', ['album_id' => $gallery_photo->gallery_album_id]) }}" class="text-sm font-semibold text-slate-600 transition hover:text-emerald-700">Kembali</a>
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

        <form action="{{ route('admin.gallery-photos.update', $gallery_photo) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="gallery_album_id" class="mb-2 block text-sm font-semibold text-slate-700">Album Galeri <span class="text-red-600">*</span></label>
                        <select id="gallery_album_id" name="gallery_album_id" required class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                            @foreach ($albums as $album)
                                <option value="{{ $album->id }}" @selected((string) old('gallery_album_id', $gallery_photo->gallery_album_id) === (string) $album->id)>{{ $album->title }}</option>
                            @endforeach
                        </select>
                        @error('gallery_album_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="sort_order" class="mb-2 block text-sm font-semibold text-slate-700">Urutan Tampil</label>
                        <input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $gallery_photo->sort_order) }}" class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                        @error('sort_order')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-5">
                    <label for="caption" class="mb-2 block text-sm font-semibold text-slate-700">Caption <span class="font-normal text-slate-400">(opsional)</span></label>
                    <input id="caption" name="caption" type="text" value="{{ old('caption', $gallery_photo->caption) }}" class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                    @error('caption')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-5">
                    <label for="alt_text" class="mb-2 block text-sm font-semibold text-slate-700">Teks Alternatif <span class="font-normal text-slate-400">(opsional)</span></label>
                    <input id="alt_text" name="alt_text" type="text" value="{{ old('alt_text', $gallery_photo->alt_text) }}" class="w-full rounded-xl border-slate-300 px-3 py-2.5 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                    @error('alt_text')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <label for="image" class="mb-2 block text-sm font-semibold text-slate-700">Ganti Foto <span class="font-normal text-slate-400">(opsional)</span></label>
                <input id="image" name="image" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-emerald-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-emerald-700 hover:file:bg-emerald-100">
                <p class="mt-2 text-xs text-slate-500">Pilih foto baru untuk mengganti foto saat ini. Format JPG, JPEG, PNG, atau WEBP; maksimal 2 MB.</p>
                @error('image')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
                <img id="image-preview" src="{{ asset('storage/' . $gallery_photo->image_path) }}" alt="{{ $gallery_photo->alt_text ?: $gallery_photo->caption ?: 'Preview foto galeri' }}" class="mt-4 h-64 w-full rounded-xl object-cover ring-1 ring-slate-200">
            </div>

            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <a href="{{ route('admin.gallery-photos.index', ['album_id' => $gallery_photo->gallery_album_id]) }}" class="rounded-xl border border-slate-300 px-4 py-2.5 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Batal</a>
                <button type="submit" class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">Simpan Perubahan</button>
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
                    return;
                }

                imagePreview.src = URL.createObjectURL(file);
            });
        </script>
    @endpush
@endsection
