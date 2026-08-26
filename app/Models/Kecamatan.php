<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kecamatan extends Model
{
    protected $fillable = ['nama', 'urutan'];

    /**
     * @return HasMany<Jawaban, $this>
     */
    public function jawabans(): HasMany
    {
        return $this->hasMany(Jawaban::class);
    }
}
