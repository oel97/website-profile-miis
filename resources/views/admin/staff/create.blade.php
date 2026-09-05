@extends('admin.layouts.app')

@section('title', 'Tambah Guru & Tendik')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">Master Sekolah</p>
            <h1 class="mt-2 text-3xl font-bold text-slate-900">Tambah Guru & Tenaga Kependidikan</h1>
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

        <form action="{{ route('admin.staff.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            @csrf

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label for="name" class="mb-2 block text-sm font-medium text-slate-700">Nama</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                    @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="position" class="mb-2 block text-sm font-medium text-slate-700">Jabatan</label>
                    <input id="position" type="text" name="position" value="{{ old('position') }}" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100" placeholder="Contoh: Guru Kelas">
                    @error('position') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                @include('admin.staff._classification-fields')

                <div>
                    <label for="sort_order" class="mb-2 block text-sm font-medium text-slate-700">Urutan Tampil</label>
                    <input id="sort_order" type="number" min="0" name="sort_order" value="{{ old('sort_order', 0) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                    @error('sort_order') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-end pb-2">
                    <label class="inline-flex items-center gap-3 text-sm font-medium text-slate-700">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        Tampilkan sebagai data aktif
                    </label>
                </div>

                <div class="md:col-span-2">
                    <label for="bio" class="mb-2 block text-sm font-medium text-slate-700">Deskripsi</label>
                    <textarea id="bio" name="bio" rows="5" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100" placeholder="Deskripsi singkat guru atau tenaga kependidikan.">{{ old('bio') }}</textarea>
                    @error('bio') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="photo" class="mb-2 block text-sm font-medium text-slate-700">Foto</label>
                    <div id="photo-preview-wrapper" class="mb-4 hidden overflow-hidden rounded-xl border border-slate-200 bg-slate-50 p-3">
                        <img id="photo-preview" src="" alt="Pratinjau foto guru atau tenaga kependidikan" class="h-36 w-28 rounded-lg object-cover">
                    </div>
                    <input id="photo" type="file" name="photo" accept="image/jpeg,image/png,image/webp" class="w-full rounded-xl border border-dashed border-slate-300 bg-slate-50 px-3 py-3 text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-emerald-600 file:px-3 file:py-2 file:text-sm file:font-medium file:text-white">
                    <p class="mt-2 text-xs text-slate-500">JPG, JPEG, PNG, atau WebP. Maksimal 2 MB.</p>
                    @error('photo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('admin.staff.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Batal</a>
                <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">Simpan</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('photo').addEventListener('change', function (event) {
            const [file] = event.target.files;

            if (! file) {
                return;
            }

            document.getElementById('photo-preview').src = URL.createObjectURL(file);
            document.getElementById('photo-preview-wrapper').classList.remove('hidden');
        });
    </script>
@endsection
