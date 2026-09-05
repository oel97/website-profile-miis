@php
    $selectedType = old('type', isset($staff) ? $staff->type : \App\Models\Staff::TYPE_GURU);
    $selectedCategory = old('employment_type', $staff->employment_type ?? '');
    $selectedEducation = old('education', $staff->education ?? '');
@endphp

<div>
    <label for="type" class="mb-2 block text-sm font-medium text-slate-700">Jenis Staff</label>
    <select id="type" name="type" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
        @foreach ($typeOptions as $value => $label)
            <option value="{{ $value }}" @selected($selectedType === $value)>{{ $label }}</option>
        @endforeach
    </select>
    @error('type') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label for="employment_type" class="mb-2 block text-sm font-medium text-slate-700">Kategori</label>
    <select id="employment_type" name="employment_type" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
        <option value="">Pilih kategori</option>
        @foreach ($categoryOptions as $type => $categories)
            @foreach ($categories as $category)
                <option value="{{ $category }}" data-staff-type="{{ $type }}" @selected($selectedCategory === $category)>{{ $category }}</option>
            @endforeach
        @endforeach
    </select>
    @error('employment_type') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label for="education" class="mb-2 block text-sm font-medium text-slate-700">Pendidikan Terakhir</label>
    <select id="education" name="education" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
        <option value="">Pilih pendidikan terakhir</option>
        @foreach ($educationOptions as $education)
            <option value="{{ $education }}" @selected($selectedEducation === $education)>{{ $education }}</option>
        @endforeach
    </select>
    @error('education') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
</div>

<script>
    (() => {
        const typeField = document.getElementById('type');
        const categoryField = document.getElementById('employment_type');

        const syncCategoryOptions = (resetSelection = false) => {
            for (const option of categoryField.options) {
                if (! option.dataset.staffType) {
                    continue;
                }

                const isAvailable = option.dataset.staffType === typeField.value;
                option.hidden = ! isAvailable;
                option.disabled = ! isAvailable;
            }

            if (resetSelection && categoryField.selectedOptions[0]?.disabled) {
                categoryField.value = '';
            }
        };

        typeField.addEventListener('change', () => syncCategoryOptions(true));
        syncCategoryOptions();
    })();
</script>
