<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Competition extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'event_name',
        'description',
        'poster',
        'category',
        'price',
        'price_community',
        'price_early_bird',
        'quota',
        'event_date',
        'location',
        'status',
        'unit',
        'rules',
        'requirements',
        'schedule',
    ];

    protected $casts = [
        'event_date'      => 'date',
        'price'           => 'integer',
        'price_community' => 'integer',
        'price_early_bird'=> 'integer',
        'quota'           => 'integer',
    ];

    /** Auto-generate slug from name if not provided */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    /** Format harga utama ke Rupiah */
    public function getPriceFormattedAttribute(): string
    {
        return 'Rp' . number_format($this->price, 0, ',', '.');
    }

    /** Apakah lomba memiliki harga khusus */
    public function hasSpecialPrice(): bool
    {
        return $this->price_community !== null || $this->price_early_bird !== null;
    }
}
