<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Staff extends Model
{
    use SoftDeletes;

    public const TYPE_GURU = 'guru';

    public const TYPE_EDUCATION_STAFF = 'tenaga_kependidikan';

    public const TYPE_OPTIONS = [
        self::TYPE_GURU => 'Guru',
        self::TYPE_EDUCATION_STAFF => 'Tenaga Kependidikan',
    ];

    public const CATEGORY_OPTIONS = [
        self::TYPE_GURU => [
            'Kepala Madrasah',
            'Guru Kelas',
            'Guru Mata Pelajaran',
            'Guru Tahfidz',
            'Guru Pendamping',
        ],
        self::TYPE_EDUCATION_STAFF => [
            'Tata Usaha',
            'Operator Madrasah',
            'Bendahara',
            'Pustakawan',
            'Tenaga Administrasi',
            'Staff Pendukung',
        ],
    ];

    public const EDUCATION_OPTIONS = [
        'SD',
        'SMP',
        'SMA',
        'SMK',
        'MA',
        'D1',
        'D2',
        'D3',
        'D4',
        'S1',
        'S2',
        'S3',
        'S.Pd',
        'S.Pd.I',
        'M.Pd',
        'M.Pd.I',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'type',
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

    /**
     * Get the human-readable staff type.
     */
    public function typeLabel(): string
    {
        return self::TYPE_OPTIONS[$this->type] ?? 'Guru';
    }
}
