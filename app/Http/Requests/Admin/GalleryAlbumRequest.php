<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class GalleryAlbumRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('gallery_albums', 'slug')->ignore($this->route('gallery_album')),
            ],
            'description' => ['nullable', 'string'],
            'event_date' => ['nullable', 'date'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_published' => ['required', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'nama album',
            'slug' => 'slug',
            'description' => 'deskripsi',
            'event_date' => 'tanggal kegiatan',
            'image' => 'cover album',
            'sort_order' => 'urutan tampil',
            'is_published' => 'status publikasi',
        ];
    }
}
