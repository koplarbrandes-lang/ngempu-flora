<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Species extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name',
        'display_name',
        'label',
        'start',
        'gain',
        'tau',
        'param_a',
        'param_b',
        'root_share',
        'has_pruning',
    ];

    protected function casts(): array
    {
        return [
            'start' => 'float',
            'gain' => 'float',
            'tau' => 'float',
            'param_a' => 'float',
            'param_b' => 'float',
            'root_share' => 'float',
            'has_pruning' => 'boolean',
        ];
    }

    public function trees(): HasMany
    {
        return $this->hasMany(Tree::class);
    }

    public function defaultNotes(): HasMany
    {
        return $this->hasMany(SpeciesDefaultNote::class);
    }

    /**
     * Fallback expected height (cm) for a given week using the asymptotic growth curve:
     * start + gain * (1 - exp(-week / tau))
     */
    public function expectedHeight(int $week): float
    {
        if ($week <= 0) {
            return (float) $this->start;
        }

        return round($this->start + $this->gain * (1 - exp(-$week / $this->tau)), 2);
    }
}
