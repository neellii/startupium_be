<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class Hex implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return hex2bin($value);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return bin2hex($value);
    }
}
