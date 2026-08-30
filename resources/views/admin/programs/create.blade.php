@extends('admin.layouts.app')

@section('title', 'Tambah Program Unggulan')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">Master Sekolah</p>
            <h1 class="mt-2 text-3xl font-bold text-slate-900">Tambah Program Unggulan</h1>
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

        <form action="{{ route('admin.programs.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            @csrf

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label for="title" class="mb-2 block text-sm font-medium text-slate-700">Judul Program</label>
                    <input id="title" type="text" name="title" value="{{ old('title') }}" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                    @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="slug" class="mb-2 block text-sm font-medium text-slate-700">Slug <span class="font-normal text-slate-400">(opsional)</span></label>
                    <input id="slug" type="text" name="slug" value="{{ old('slug') }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100" placeholder="Otomatis dari judul bila dikosongkan">
                    @error('slug') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="icon" class="mb-2 block text-sm font-medium text-slate-700">Ikon <span class="font-normal text-slate-400">(opsional)</span></label>
                    <input id="icon" type="text" name="icon" value="{{ old('icon') }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100" placeholder="Contoh: ✦ atau nama ikon">
                    @error('icon') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="sort_order" class="mb-2 block text-sm font-medium text-slate-700">Urutan Tampil</label>
                    <input id="sort_order" type="number" min="0" name="sort_order" value="{{ old('sort_order', 0) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                    @error('sort_order') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="short_description" class="mb-2 block text-sm font-medium text-slate-700">Deskripsi Singkat <span class="font-normal text-slate-400">(opsional)</span></label>
                    <textarea id="short_description" name="short_description" rows="3" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100" placeholder="Ringkasan yang akan tampil pada kartu program.">{{ old('short_description') }}</textarea>
                    @error('short_description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="mb-2 block text-sm font-medium text-slate-700">Deskripsi Program</label>
                    <textarea id="description" name="description" rows="6" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100" placeholder="Jelaskan tujuan, kegiatan, dan manfaat program.">{{ old('description') }}</textarea>
                    @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="image" class="mb-2 block text-sm font-medium text-slate-700">Gambar Program <span class="font-normal text-slate-400">(opsional)</span></label>
                    <div id="image-preview-wrapper" class="mb-4 hidden overflow-hidden rounded-xl border border-slate-200 bg-slate-50 p-3">
                        <img id="image-preview" src="" alt="Pratinjau gambar program" class="h-36 w-52 rounded-lg object-cover">
                    </div>
                    <input id="image" type="file" name="image" accept="image/jpeg,image/png,image/webp" class="w-full rounded-xl border border-dashed border-slate-300 bg-slate-50 px-3 py-3 text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-emerald-600 file:px-3 file:py-2 file:text-sm file:font-medium file:text-white">
                    <p class="mt-2 text-xs text-slate-500">JPG, JPEG, PNG, atau WebP. Maksimal 2 MB.</p>
                    @error('image') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="inline-flex items-center gap-3 text-sm font-medium text-slate-700">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        Aktifkan program ini
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('admin.programs.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Batal</a>
                <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">Simpan</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('image').addEventListener('change', function (event) {
            const [file] = event.target.files;

            if (! file) {
                return;
            }

            document.getElementById('image-preview').src = URL.createObjectURL(file);
            document.getElementById('image-preview-wrapper').classList.remove('hidden');
        });
    </script>
@endsection
