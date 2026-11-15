<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tree extends Model
{
    use HasFactory;

    protected $fillable = [
        'tree_code',
        'token',
        'species_id',
        'adopter_name',
        'user_id',
        'planted_date',
        'plot',
        'current_week',
        'total_weeks',
        'last_updated_at',
    ];

    protected function casts(): array
    {
        return [
            'planted_date' => 'date',
            'last_updated_at' => 'date',
            'current_week' => 'integer',
            'total_weeks' => 'integer',
        ];
    }

    public function species(): BelongsTo
    {
        return $this->belongsTo(Species::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function measurements(): HasMany
    {
        return $this->hasMany(TreeMeasurement::class)->orderBy('week_number', 'asc');
    }

    public function latestMeasurement(): HasOne
    {
        return $this->hasOne(TreeMeasurement::class)->latestOfMany('week_number');
    }

    public function isUpdatedThisMonth(): bool
    {
        if (!$this->last_updated_at) {
            return false;
        }

        return Carbon::parse($this->last_updated_at)->diffInDays(now()) < 30;
    }

    public function isUpdatedThisWeek(): bool
    {
        return $this->isUpdatedThisMonth();
    }

    public function isLate(): bool
    {
        if (!$this->last_updated_at) {
            return true;
        }

        return Carbon::parse($this->last_updated_at)->diffInDays(now()) > 45;
    }

    public function getFullCodeTokenAttribute(): string
    {
        return "{$this->tree_code}-{$this->token}";
    }

    public function getCertificateNumberAttribute(): string
    {
        $cleanCode = str_replace('-', '', $this->tree_code);
        $cleanToken = strtoupper($this->token);
        return "CERT-{$cleanCode}-{$cleanToken}";
    }

    public function getTotalMonthsAttribute(): int
    {
        return (int) ($this->attributes['total_weeks'] ?? 12);
    }

    public function getCurrentMonthAttribute(): int
    {
        return (int) ($this->attributes['current_week'] ?? 0);
    }

    public function getTotalWeeksAttribute($value): int
    {
        return (int) ($value ?: 12);
    }
}
