<?php

namespace App\Http\Requests\Admin;

use App\Models\SchoolProfile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SchoolProfileRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $schoolProfile = $this->route('school_profile');
        $schoolProfileId = $schoolProfile instanceof SchoolProfile
            ? $schoolProfile->getKey()
            : $schoolProfile;

        return [
            'name' => ['required', 'string', 'max:255'],
            'npsn' => ['nullable', 'string', 'max:50', Rule::unique('school_profiles', 'npsn')->ignore($schoolProfileId)],
            'ns_madrasah' => ['nullable', 'string', 'max:50', Rule::unique('school_profiles', 'ns_madrasah')->ignore($schoolProfileId)],
            'accreditation' => ['nullable', 'string', 'max:50'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'short_description' => ['nullable', 'string'],
            'about' => ['nullable', 'string'],
            'history_title' => ['nullable', 'string', 'max:255'],
            'history_content' => ['nullable', 'string'],
            'vision_mission_title' => ['nullable', 'string', 'max:255'],
            'vision_mission_content' => ['nullable', 'string'],
            'address' => ['required', 'string', 'max:500'],
            'village' => ['nullable', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
            'regency' => ['nullable', 'string', 'max:100'],
            'province' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'phone' => ['nullable', 'string', 'max:30'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'foto_sekolah' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'map_embed_url' => ['nullable', 'url', 'max:500'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
        ];
    }

    public function attributes(): array
    {
        return [
            'logo' => 'logo sekolah',
            'foto_sekolah' => 'foto sekolah',
            'history_title' => 'judul sejarah sekolah',
            'history_content' => 'sejarah sekolah',
            'vision_mission_title' => 'judul visi dan misi',
            'vision_mission_content' => 'visi dan misi',
        ];
    }
}
