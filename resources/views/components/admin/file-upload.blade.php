@props([
    'name',
    'id' => null,
    'accept' => 'image/*',
    'helpText' => null,
    'emptyText' => 'Belum ada file dipilih',
])

@php
    $inputId = $id ?? $name;
    $fileNameId = $inputId . '-file-name';
@endphp

<div>
    <input
        id="{{ $inputId }}"
        name="{{ $name }}"
        type="file"
        accept="{{ $accept }}"
        class="sr-only"
        data-file-input
        data-file-name-target="{{ $fileNameId }}"
    >

    <div class="flex flex-col gap-3 rounded-xl border border-dashed border-slate-300 bg-slate-50 p-3 sm:flex-row sm:items-center">
        <label for="{{ $inputId }}" class="inline-flex w-fit cursor-pointer items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M10 13V3m0 0L6.5 6.5M10 3l3.5 3.5M4 12.5v2A1.5 1.5 0 0 0 5.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-2" stroke-linecap="round" stroke-linejoin="round" /></svg>
            Pilih gambar
        </label>
        <p id="{{ $fileNameId }}" class="min-w-0 truncate text-sm text-slate-500" aria-live="polite">{{ $emptyText }}</p>
    </div>

    @if ($helpText)
        <p class="mt-2 text-xs text-slate-500">{{ $helpText }}</p>
    @endif
</div>
