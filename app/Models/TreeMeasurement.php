<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class TreeMeasurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'tree_id',
        'week_number',
        'measurement_cm',
        'leaf_count',
        'pruned_weight_g',
        'health',
        'note',
        'photo_path',
        'carbon_absorbed_kg',
        'is_published',
        'logged_at',
    ];

    protected function casts(): array
    {
        return [
            'measurement_cm' => 'float',
            'leaf_count' => 'integer',
            'pruned_weight_g' => 'float',
            'carbon_absorbed_kg' => 'float',
            'is_published' => 'boolean',
            'logged_at' => 'date',
            'week_number' => 'integer',
        ];
    }

    public function tree(): BelongsTo
    {
        return $this->belongsTo(Tree::class);
    }

    public function getMonthNumberAttribute(): int
    {
        return (int) $this->week_number;
    }

    public function getPhotoUrlAttribute(): ?string
    {
        if (!$this->photo_path) {
            return null;
        }

        return '/storage/' . ltrim($this->photo_path, '/');
    }
}
