<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Achievement extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'recipient_name',
        'level',
        'organizer',
        'achievement_date',
        'description',
        'image_path',
        'certificate_path',
        'sort_order',
        'is_published',
    ];

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
            'achievement_date' => 'date',
            'sort_order' => 'integer',
            'is_published' => 'boolean',
        ];
    }
}
