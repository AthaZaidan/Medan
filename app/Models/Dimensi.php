<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Dimensi extends Model
{
    protected $fillable = ['kode', 'nama', 'urutan'];

    /**
     * Indikators linked to this dimension with their weights.
     *
     * @return BelongsToMany<Indikator, $this>
     */
    public function indikators(): BelongsToMany
    {
        return $this->belongsToMany(Indikator::class, 'dimensi_indikator_bobot')
            ->withPivot('bobot')
            ->withTimestamps();
    }
}
