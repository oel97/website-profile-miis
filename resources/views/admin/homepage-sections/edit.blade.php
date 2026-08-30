@extends('admin.layouts.app')

@section('title', 'Edit Homepage Section')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">Beranda</p>
            <h1 class="mt-2 text-3xl font-bold text-slate-900">Edit Homepage Section</h1>
            <p class="mt-2 text-sm text-slate-500">Perbarui konfigurasi section {{ $sectionOptions[$homepageSection->key] ?? $homepageSection->key }}.</p>
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

        <form action="{{ route('admin.homepage-sections.update', $homepageSection) }}" method="POST" class="space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            @csrf
            @method('PUT')

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label for="key" class="mb-2 block text-sm font-medium text-slate-700">Section Beranda</label>
                    <select id="key" name="key" required class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                        @foreach ($sectionOptions as $key => $label)
                            <option value="{{ $key }}" @selected(old('key', $homepageSection->key) === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('key') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="sort_order" class="mb-2 block text-sm font-medium text-slate-700">Urutan Tampil</label>
                    <input id="sort_order" type="number" min="0" name="sort_order" value="{{ old('sort_order', $homepageSection->sort_order) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                    @error('sort_order') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="title_override" class="mb-2 block text-sm font-medium text-slate-700">Judul Tampilan <span class="font-normal text-slate-400">(opsional)</span></label>
                    <input id="title_override" type="text" name="title_override" value="{{ old('title_override', $homepageSection->title_override) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                    @error('title_override') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="item_limit" class="mb-2 block text-sm font-medium text-slate-700">Batas Item <span class="font-normal text-slate-400">(opsional)</span></label>
                    <input id="item_limit" type="number" min="1" max="12" name="item_limit" value="{{ old('item_limit', $homepageSection->item_limit) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                    @error('item_limit') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="subtitle_override" class="mb-2 block text-sm font-medium text-slate-700">Subjudul Tampilan <span class="font-normal text-slate-400">(opsional)</span></label>
                    <textarea id="subtitle_override" name="subtitle_override" rows="3" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">{{ old('subtitle_override', $homepageSection->subtitle_override) }}</textarea>
                    @error('subtitle_override') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="settings" class="mb-2 block text-sm font-medium text-slate-700">Pengaturan Lanjutan JSON <span class="font-normal text-slate-400">(opsional)</span></label>
                    <textarea id="settings" name="settings" rows="4" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 font-mono text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">{{ old('settings', $homepageSection->settings ? json_encode($homepageSection->settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '') }}</textarea>
                    @error('settings') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="inline-flex items-center gap-3 text-sm font-medium text-slate-700">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $homepageSection->is_active) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        Tampilkan section ini pada beranda
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('admin.homepage-sections.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Batal</a>
                <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">Perbarui Section</button>
            </div>
        </form>
    </div>
@endsection
