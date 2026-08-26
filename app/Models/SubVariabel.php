<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubVariabel extends Model
{
    protected $fillable = ['kuesioner_id', 'nama', 'dimensi_kode', 'bobot_subtotal', 'urutan'];

    /**
     * @return BelongsTo<Kuesioner, $this>
     */
    public function kuesioner(): BelongsTo
    {
        return $this->belongsTo(Kuesioner::class);
    }

    /**
     * @return HasMany<Indikator, $this>
     */
    public function indikators(): HasMany
    {
        return $this->hasMany(Indikator::class);
    }
}
