<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolProfile extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'npsn',
        'ns_madrasah',
        'accreditation',
        'logo_path',
        'tagline',
        'short_description',
        'about',
        'address',
        'village',
        'district',
        'regency',
        'province',
        'postal_code',
        'phone',
        'whatsapp',
        'email',
        'website',
        'map_embed_url',
        'latitude',
        'longitude',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }
}
