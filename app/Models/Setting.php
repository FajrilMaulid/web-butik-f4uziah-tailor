<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    // Dapatkan nilai setting berdasarkan key
    public static function get(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    // Simpan/update nilai setting
    public static function set(string $key, ?string $value)
    {
        return self::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
