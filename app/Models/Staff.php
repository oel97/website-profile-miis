<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Staff extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'nip',
        'position',
        'employment_type',
        'education',
        'subject_or_duty',
        'photo_path',
        'bio',
        'sort_order',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Determine whether the configured photo is present on the public disk.
     */
    public function hasPhoto(): bool
    {
        return filled($this->photo_path)
            && Storage::disk('public')->exists($this->photo_path);
    }
}
