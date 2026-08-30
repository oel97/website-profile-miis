<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'label',
        'type',
        'value',
        'url',
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
     * Get the destination for a public contact link.
     *
     * An explicit URL entered by an administrator always takes precedence. For
     * phone and WhatsApp contacts, a usable link is generated automatically
     * from the contact value when an explicit URL is not needed.
     */
    public function publicUrl(): ?string
    {
        $url = trim((string) $this->url);

        if ($url !== '') {
            return $url;
        }

        $value = trim((string) $this->value);

        return match ($this->type) {
            'phone' => ($phone = $this->normalizedPhoneNumber($value)) !== null ? 'tel:'.$phone : null,
            'whatsapp' => ($phone = $this->normalizedWhatsAppNumber($value)) !== null ? 'https://wa.me/'.$phone : null,
            'email' => filter_var($value, FILTER_VALIDATE_EMAIL) !== false ? 'mailto:'.$value : null,
            default => null,
        };
    }

    /**
     * Remove display formatting from a telephone number.
     */
    private function normalizedPhoneNumber(string $value): ?string
    {
        $number = preg_replace('/\D+/', '', $value);

        return $number !== '' ? $number : null;
    }

    /**
     * Normalize common Indonesian number formats for wa.me.
     */
    private function normalizedWhatsAppNumber(string $value): ?string
    {
        $number = $this->normalizedPhoneNumber($value);

        if ($number === null) {
            return null;
        }

        if (str_starts_with($number, '0')) {
            return '62'.substr($number, 1);
        }

        if (str_starts_with($number, '8')) {
            return '62'.$number;
        }

        return $number;
    }
}
