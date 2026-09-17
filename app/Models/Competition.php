<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'min_purchase',
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
        'event_date'   => 'date',
        'price'        => 'integer',
        'min_purchase' => 'integer',
        'quota'        => 'integer',
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

    /** Relasi ke pendaftaran */
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    /** Jumlah pendaftaran lunas */
    public function getPaidRegistrationsCountAttribute(): int
    {
        return $this->registrations()->where('status', 'paid')->count();
    }
}
