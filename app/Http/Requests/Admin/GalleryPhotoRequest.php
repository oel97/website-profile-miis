<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GalleryPhotoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['super_admin', 'admin'], true);
    }

    public function rules(): array
    {
        $isCreating = $this->isMethod('post');

        return [
            'gallery_album_id' => [
                'required',
                'integer',
                Rule::exists('gallery_albums', 'id')->whereNull('deleted_at'),
            ],
            'image' => [
                $isCreating ? 'required_without:images' : 'nullable',
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            'images' => [
                $isCreating ? 'required_without:image' : 'prohibited',
                'nullable',
                'array',
                'min:1',
            ],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'caption' => ['nullable', 'string', 'max:255'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function attributes(): array
    {
        return [
            'gallery_album_id' => 'album galeri',
            'image' => 'foto galeri',
            'images' => 'foto galeri',
            'images.*' => 'foto galeri',
            'caption' => 'caption',
            'alt_text' => 'teks alternatif',
            'sort_order' => 'urutan tampil',
        ];
    }
}
