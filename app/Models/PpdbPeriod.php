<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PpdbPeriod extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'academic_year',
        'title',
        'slug',
        'description',
        'banner_path',
        'registration_start_at',
        'registration_end_at',
        'announcement_at',
        'registration_url',
        'contact_whatsapp',
        'is_active',
        'is_published',
    ];

    /**
     * Get the requirements for the PPDB period.
     */
    public function requirements(): HasMany
    {
        return $this->hasMany(PpdbRequirement::class)->orderBy('sort_order');
    }

    /**
     * Get the registration steps for the PPDB period.
     */
    public function steps(): HasMany
    {
        return $this->hasMany(PpdbStep::class)->orderBy('sort_order');
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'registration_start_at' => 'datetime',
            'registration_end_at' => 'datetime',
            'announcement_at' => 'datetime',
            'is_active' => 'boolean',
            'is_published' => 'boolean',
        ];
    }
}
