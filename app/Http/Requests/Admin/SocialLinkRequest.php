<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SocialLinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['super_admin', 'admin'], true);
    }

    public function rules(): array
    {
        return [
            'platform' => [
                'required',
                'string',
                'max:50',
                Rule::unique('social_links', 'platform')->ignore($this->route('social_link')),
            ],
            'label' => ['nullable', 'string', 'max:255'],
            'url' => ['required', 'url', 'max:255'],
            'icon' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'platform' => 'nama platform',
            'label' => 'nama tampilan',
            'url' => 'URL sosial media',
            'icon' => 'ikon',
            'sort_order' => 'urutan tampil',
            'is_active' => 'status aktif',
        ];
    }
}
