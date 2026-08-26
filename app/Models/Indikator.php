<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Indikator extends Model
{
    protected $fillable = ['sub_variabel_id', 'kode', 'pernyataan', 'bobot_asli', 'urutan'];

    /**
     * @return BelongsTo<SubVariabel, $this>
     */
    public function subVariabel(): BelongsTo
    {
        return $this->belongsTo(SubVariabel::class);
    }

    /**
     * @return HasMany<SubItem, $this>
     */
    public function subItems(): HasMany
    {
        return $this->hasMany(SubItem::class);
    }

    /**
     * Dimensions this indicator contributes to.
     *
     * @return BelongsToMany<Dimensi, $this>
     */
    public function dimensis(): BelongsToMany
    {
        return $this->belongsToMany(Dimensi::class, 'dimensi_indikator_bobot')
            ->withPivot('bobot')
            ->withTimestamps();
    }
}
