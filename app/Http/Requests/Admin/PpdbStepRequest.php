<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PpdbStepRequest extends FormRequest
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
        ];
    }

    public function attributes(): array
    {
        return [
            'ppdb_period_id' => 'periode PPDB',
            'title' => 'judul langkah',
            'description' => 'deskripsi langkah',
            'sort_order' => 'nomor urutan',
        ];
    }
}
