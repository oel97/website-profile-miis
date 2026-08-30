<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageSection extends Model
{
    /**
     * Sections that are currently rendered on the public homepage.
     *
     * @var array<string, string>
     */
    public const MANAGEABLE_SECTIONS = [
        'about' => 'Tentang Madrasah',
        'programs' => 'Program Unggulan',
        'news' => 'Berita & Kegiatan',
        'achievements' => 'Prestasi',
        'agenda_ppdb' => 'Agenda & PPDB',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'key',
        'title_override',
        'subtitle_override',
        'sort_order',
        'is_active',
        'item_limit',
        'settings',
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
            'item_limit' => 'integer',
            'settings' => 'array',
        ];
    }
}
