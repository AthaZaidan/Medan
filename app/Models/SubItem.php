<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubItem extends Model
{
    protected $fillable = ['indikator_id', 'kode', 'teks', 'urutan'];

    /**
     * @return BelongsTo<Indikator, $this>
     */
    public function indikator(): BelongsTo
    {
        return $this->belongsTo(Indikator::class);
    }

    /**
     * @return HasMany<Jawaban, $this>
     */
    public function jawabans(): HasMany
    {
        return $this->hasMany(Jawaban::class);
    }
}
