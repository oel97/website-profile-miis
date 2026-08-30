<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SiteSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['super_admin', 'admin'], true);
    }

    public function rules(): array
    {
        $type = $this->input('type', 'text');

        return [
            'group' => ['required', 'string', 'max:50'],
            'key' => [
                'required',
                'string',
                'max:255',
                Rule::unique('site_settings', 'key')->ignore($this->route('site_setting')),
            ],
            'value' => $this->valueRules($type),
            'type' => ['required', Rule::in(['text', 'textarea', 'url', 'email', 'image', 'boolean', 'json'])],
            'is_public' => ['required', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'group' => 'kelompok pengaturan',
            'key' => 'key pengaturan',
            'value' => 'nilai pengaturan',
            'type' => 'tipe data',
            'is_public' => 'status publik',
        ];
    }

    /**
     * @return array<int, string>
     */
    private function valueRules(string $type): array
    {
        return match ($type) {
            'url' => ['required', 'url', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'boolean' => ['required', 'boolean'],
            'json' => ['required', 'json'],
            default => ['required', 'string'],
        };
    }
}
