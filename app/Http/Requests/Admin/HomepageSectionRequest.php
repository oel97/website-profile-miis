<?php

namespace App\Http\Requests\Admin;

use App\Models\HomepageSection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class HomepageSectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['super_admin', 'admin'], true);
    }

    /**
     * Normalize a section key before validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->filled('key')) {
            $this->merge([
                'key' => Str::snake(Str::lower($this->input('key'))),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'key' => [
                'required',
                'string',
                Rule::in(array_keys(HomepageSection::MANAGEABLE_SECTIONS)),
                Rule::unique('homepage_sections', 'key')->ignore($this->route('homepage_section')),
            ],
            'title_override' => ['nullable', 'string', 'max:255'],
            'subtitle_override' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['required', 'boolean'],
            'item_limit' => ['nullable', 'integer', 'min:1', 'max:12'],
            'settings' => ['nullable', 'json'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'key' => 'section beranda',
            'title_override' => 'judul section',
            'subtitle_override' => 'subjudul section',
            'sort_order' => 'urutan tampil',
            'is_active' => 'status',
            'item_limit' => 'batas item',
            'settings' => 'pengaturan lanjutan',
        ];
    }
}
