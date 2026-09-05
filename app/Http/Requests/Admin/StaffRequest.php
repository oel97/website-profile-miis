<?php

namespace App\Http\Requests\Admin;

use App\Models\Staff;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StaffRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['super_admin', 'admin'], true);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $staff = $this->route('staff');
        $type = (string) $this->input('type');
        $categories = Staff::CATEGORY_OPTIONS[$type] ?? [];
        $educationOptions = Staff::EDUCATION_OPTIONS;

        if ($staff instanceof Staff && $staff->type === $type && filled($staff->employment_type)) {
            $categories[] = $staff->employment_type;
        }

        if ($staff instanceof Staff && filled($staff->education)) {
            $educationOptions[] = $staff->education;
        }

        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(array_keys(Staff::TYPE_OPTIONS))],
            'position' => ['required', 'string', 'max:255'],
            'employment_type' => ['required', 'string', 'max:255', Rule::in(array_unique($categories))],
            'education' => ['nullable', 'string', 'max:255', Rule::in(array_unique($educationOptions))],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'bio' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['required', 'boolean'],
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
            'name' => 'nama',
            'type' => 'jenis staff',
            'position' => 'jabatan',
            'employment_type' => 'kategori',
            'education' => 'pendidikan terakhir',
            'photo' => 'foto',
            'bio' => 'deskripsi',
            'sort_order' => 'urutan',
            'is_active' => 'status',
        ];
    }
}
