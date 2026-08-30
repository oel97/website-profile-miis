<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['super_admin', 'admin'], true);
    }

    /**
     * Phone numbers belong in the contact information field, not the optional
     * URL field. Clear an accidentally entered number so the public website
     * can generate its tel: or wa.me link automatically.
     */
    protected function prepareForValidation(): void
    {
        $type = $this->input('type');
        $url = trim((string) $this->input('url', ''));

        if (in_array($type, ['phone', 'whatsapp'], true)
            && $url !== ''
            && filter_var($url, FILTER_VALIDATE_URL) === false) {
            $this->merge(['url' => null]);
        }
    }

    public function rules(): array
    {
        $valueRules = ['required', 'string', 'max:255'];
        $urlRules = ['nullable', 'url', 'max:255'];

        if ($this->input('type') === 'email') {
            $valueRules[] = 'email';
        }

        if (in_array($this->input('type'), ['website', 'maps'], true)) {
            array_unshift($urlRules, 'required');
        }

        return [
            'label' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(['address', 'phone', 'whatsapp', 'email', 'website', 'maps', 'other'])],
            'value' => $valueRules,
            'url' => $urlRules,
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'label' => 'nama kontak',
            'type' => 'jenis kontak',
            'value' => 'informasi kontak',
            'url' => 'tautan',
            'sort_order' => 'urutan tampil',
            'is_active' => 'status aktif',
        ];
    }
}
