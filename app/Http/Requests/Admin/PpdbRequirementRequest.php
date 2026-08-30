<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PpdbRequirementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['super_admin', 'admin'], true);
    }

    public function rules(): array
    {
        return [
            'ppdb_period_id' => ['required', 'integer', Rule::exists('ppdb_periods', 'id')],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'ppdb_period_id' => 'periode PPDB',
            'title' => 'nama persyaratan',
            'description' => 'deskripsi',
            'sort_order' => 'urutan tampil',
            'is_active' => 'status aktif',
        ];
    }
}
