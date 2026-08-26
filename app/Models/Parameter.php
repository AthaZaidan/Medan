<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{
    protected $fillable = ['key', 'label', 'group', 'value'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
        ];
    }

    /**
     * Get a parameter value by key.
     */
    public static function getValue(string $key, float $default = 0): float
    {
        $param = static::where('key', $key)->first();

        return $param ? (float) $param->value : $default;
    }

    /**
     * Get all parameters in a group as key => value array.
     *
     * @return array<string, float>
     */
    public static function getGroup(string $group): array
    {
        return static::where('group', $group)
            ->pluck('value', 'key')
            ->map(fn ($v) => (float) $v)
            ->toArray();
    }
}
