@extends('admin.layouts.app')

@section('title', 'Edit Hero Slide')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">Beranda</p>
            <h1 class="mt-2 text-3xl font-bold text-slate-900">Edit Hero Slide</h1>
            <p class="mt-2 text-sm text-slate-500">Perbarui tampilan banner utama halaman beranda.</p>
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

        <form action="{{ route('admin.hero-slides.update', $heroSlide) }}" method="POST" enctype="multipart/form-data" class="space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            @csrf
            @method('PUT')

            <div class="grid gap-6 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label for="title" class="mb-2 block text-sm font-medium text-slate-700">Judul Hero</label>
                    <input id="title" type="text" name="title" value="{{ old('title', $heroSlide->title) }}" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                    @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="subtitle" class="mb-2 block text-sm font-medium text-slate-700">Deskripsi Hero</label>
                    <textarea id="subtitle" name="subtitle" rows="4" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">{{ old('subtitle', $heroSlide->subtitle) }}</textarea>
                    @error('subtitle') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="button_label" class="mb-2 block text-sm font-medium text-slate-700">Teks Tombol <span class="font-normal text-slate-400">(opsional)</span></label>
                    <input id="button_label" type="text" name="button_label" value="{{ old('button_label', $heroSlide->button_label) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                    @error('button_label') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="button_url" class="mb-2 block text-sm font-medium text-slate-700">Tautan Tombol <span class="font-normal text-slate-400">(opsional)</span></label>
                    <input id="button_url" type="url" name="button_url" value="{{ old('button_url', $heroSlide->button_url) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                    @error('button_url') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="sort_order" class="mb-2 block text-sm font-medium text-slate-700">Urutan Tampil</label>
                    <input id="sort_order" type="number" min="0" name="sort_order" value="{{ old('sort_order', $heroSlide->sort_order) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                    @error('sort_order') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="overlay_opacity" class="mb-2 block text-sm font-medium text-slate-700">Opacity Overlay (%)</label>
                    <input id="overlay_opacity" type="number" min="0" max="100" name="overlay_opacity" value="{{ old('overlay_opacity', $heroSlide->overlay_opacity) }}" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                    @error('overlay_opacity') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="image" class="mb-2 block text-sm font-medium text-slate-700">Gambar Desktop</label>
                    <div id="image-preview-wrapper" class="mb-4 overflow-hidden rounded-xl border border-slate-200 bg-slate-50 p-3">
                        <img id="image-preview" src="{{ asset('storage/' . $heroSlide->image_path) }}" alt="Pratinjau gambar {{ $heroSlide->title }}" class="h-40 w-full rounded-lg object-cover">
                    </div>
                    <input id="image" type="file" name="image" accept="image/jpeg,image/png,image/webp" class="w-full rounded-xl border border-dashed border-slate-300 bg-slate-50 px-3 py-3 text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-emerald-600 file:px-3 file:py-2 file:text-sm file:font-medium file:text-white">
                    <p class="mt-2 text-xs text-slate-500">Kosongkan jika ingin mempertahankan gambar desktop saat ini.</p>
                    @error('image') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="mobile_image" class="mb-2 block text-sm font-medium text-slate-700">Gambar Mobile <span class="font-normal text-slate-400">(opsional)</span></label>
                    <div id="mobile-image-preview-wrapper" @class([
                        'mb-4 overflow-hidden rounded-xl border border-slate-200 bg-slate-50 p-3',
                        'hidden' => ! $heroSlide->mobile_image_path,
                    ])>
                        <img id="mobile-image-preview" src="{{ $heroSlide->mobile_image_path ? asset('storage/' . $heroSlide->mobile_image_path) : '' }}" alt="Pratinjau gambar mobile {{ $heroSlide->title }}" class="h-52 w-40 rounded-lg object-cover">
                    </div>
                    <input id="mobile_image" type="file" name="mobile_image" accept="image/jpeg,image/png,image/webp" class="w-full rounded-xl border border-dashed border-slate-300 bg-slate-50 px-3 py-3 text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-emerald-600 file:px-3 file:py-2 file:text-sm file:font-medium file:text-white">
                    <p class="mt-2 text-xs text-slate-500">Kosongkan jika ingin mempertahankan gambar mobile saat ini. Maksimal 2 MB.</p>
                    @error('mobile_image') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="inline-flex items-center gap-3 text-sm font-medium text-slate-700">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $heroSlide->is_active) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        Aktifkan hero slide ini
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('admin.hero-slides.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Batal</a>
                <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">Perbarui Hero Slide</button>
            </div>
        </form>
    </div>

    <script>
        [['image', 'image-preview', 'image-preview-wrapper'], ['mobile_image', 'mobile-image-preview', 'mobile-image-preview-wrapper']].forEach(([inputId, previewId, wrapperId]) => {
            document.getElementById(inputId).addEventListener('change', (event) => {
                const [file] = event.target.files;

                if (! file) return;

                document.getElementById(previewId).src = URL.createObjectURL(file);
                document.getElementById(wrapperId).classList.remove('hidden');
            });
        });
    </script>
@endsection
