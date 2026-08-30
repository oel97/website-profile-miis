@extends('admin.layouts.app')

@section('title', 'Edit Sambutan Kepala Madrasah')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">Master Sekolah</p>
            <h1 class="mt-2 text-3xl font-bold text-slate-900">Edit Sambutan Kepala Madrasah</h1>
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

        <form action="{{ route('admin.principal-message.update', $principal_message) }}" method="POST" enctype="multipart/form-data" class="space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            @csrf
            @method('PUT')

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Nama Kepala Madrasah</label>
                    <input type="text" name="name" value="{{ old('name', $principal_message->name) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100" required>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Jabatan</label>
                    <input type="text" name="position" value="{{ old('position', $principal_message->position) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100" required>
                </div>

                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-slate-700">Judul Sambutan</label>
                    <input type="text" name="title" value="{{ old('title', $principal_message->title) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                </div>

                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-slate-700">Isi Sambutan</label>
                    <textarea name="message" rows="8" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100" required>{{ old('message', $principal_message->message) }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-slate-700">Foto Kepala Madrasah</label>
                    @if ($principal_message->photo_path)
                        <div class="mb-4 overflow-hidden rounded-xl border border-slate-200 bg-slate-50 p-3">
                            <img src="{{ asset('storage/' . $principal_message->photo_path) }}" alt="Foto kepala madrasah" class="h-32 w-auto rounded-lg object-cover">
                        </div>
                    @endif
                    <input type="file" name="photo" accept="image/*" class="w-full rounded-xl border border-dashed border-slate-300 bg-slate-50 px-3 py-3 text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-emerald-600 file:px-3 file:py-2 file:text-sm file:font-medium file:text-white">
                </div>

                <div class="md:col-span-2">
                    <label class="inline-flex items-center gap-3 text-sm font-medium text-slate-700">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $principal_message->is_active) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        Aktifkan sambutan ini
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('admin.principal-message.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Batal</a>
                <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700">Perbarui</button>
            </div>
        </form>
    </div>
@endsection
