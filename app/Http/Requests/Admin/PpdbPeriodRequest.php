<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PpdbPeriodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['super_admin', 'admin'], true);
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('slug')) {
            $this->merge([
                'slug' => Str::slug($this->input('slug')),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'academic_year' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('ppdb_periods', 'slug')->ignore($this->route('ppdb_period')),
            ],
            'description' => ['nullable', 'string'],
            'banner' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'registration_start_at' => ['required', 'date'],
            'registration_end_at' => ['required', 'date', 'after_or_equal:registration_start_at'],
            'announcement_at' => ['nullable', 'date'],
            'registration_url' => ['nullable', 'url', 'max:255'],
            'contact_whatsapp' => ['nullable', 'string', 'max:30'],
            'is_active' => ['required', 'boolean'],
            'is_published' => ['required', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'academic_year' => 'tahun ajaran',
            'title' => 'nama periode',
            'slug' => 'slug',
            'description' => 'deskripsi',
            'banner' => 'banner PPDB',
            'registration_start_at' => 'tanggal mulai pendaftaran',
            'registration_end_at' => 'tanggal selesai pendaftaran',
            'announcement_at' => 'tanggal pengumuman',
            'registration_url' => 'tautan pendaftaran',
            'contact_whatsapp' => 'nomor WhatsApp',
            'is_active' => 'status aktif',
            'is_published' => 'status publikasi',
        ];
    }
}
