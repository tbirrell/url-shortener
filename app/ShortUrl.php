<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShortUrl extends Model
{
    protected $fillable = ['destination'];

    public static function ofCode(string $code): ?self
    {
        $id = base64_decode(str_pad(strtr($code, '-_', '+/'), strlen($code) % 4, '=', STR_PAD_RIGHT));

        return self::where('id', $id)->first();
    }

    public function getCodeAttribute(): string
    {
        return rtrim(strtr(base64_encode($this->id), '+/', '-_'), '=');
    }
}
