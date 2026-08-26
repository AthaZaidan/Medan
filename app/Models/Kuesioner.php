<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Kuesioner extends Model
{
    protected $fillable = ['kode', 'nama'];

    /**
     * @return HasMany<SubVariabel, $this>
     */
    public function subVariabels(): HasMany
    {
        return $this->hasMany(SubVariabel::class);
    }

    /**
     * Get all indikators through sub_variabels.
     */
    public function indikators(): HasManyThrough
    {
        return $this->hasManyThrough(Indikator::class, SubVariabel::class);
    }
}
