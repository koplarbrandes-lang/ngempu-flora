<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpeciesDefaultNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'species_id',
        'week_number',
        'note',
    ];

    public function species(): BelongsTo
    {
        return $this->belongsTo(Species::class);
    }
}
