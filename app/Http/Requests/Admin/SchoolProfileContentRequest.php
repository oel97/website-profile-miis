<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SchoolProfileContentRequest extends FormRequest
{
    /**
     * Determine whether the authenticated user may edit public profile content.
     */
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['super_admin', 'admin'], true);
    }

    /**
     * Get validation rules for the selected content section.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return match ($this->route('section')) {
            'about' => [
                'about' => ['nullable', 'string'],
            ],
            'history' => [
                'history_title' => ['nullable', 'string', 'max:255'],
                'history_content' => ['nullable', 'string'],
                'history_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            ],
            'vision-mission' => [
                'vision_mission_title' => ['nullable', 'string', 'max:255'],
                'vision_mission_content' => ['nullable', 'string'],
            ],
            default => [],
        };
    }

    /**
     * Get human-readable attribute names for validation messages.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'about' => 'tentang madrasah',
            'history_title' => 'judul sejarah',
            'history_content' => 'isi sejarah',
            'history_image' => 'gambar sejarah',
            'vision_mission_title' => 'judul visi dan misi',
            'vision_mission_content' => 'isi visi dan misi',
        ];
    }
}
