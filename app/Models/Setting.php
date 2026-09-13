<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasUlids; // Assuming you have a trait for ULID support

    protected $fillable = ['key', 'value', 'group'];

    /**
     * Helper to easily grab a configuration value anywhere in your app
     */
    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }
}
