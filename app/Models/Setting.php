<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type'];

    /**
     * Get a setting value.
     */
    public static function get(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        if (!$setting) {
            return $default;
        }
        return self::castValue($setting->value, $setting->type);
    }

    /**
     * Set a setting value.
     */
    public static function set(string $key, $value, ?string $type = null)
    {
        if (is_null($type)) {
            if (is_bool($value)) {
                $type = 'boolean';
            } elseif (is_int($value)) {
                $type = 'integer';
            } elseif (is_array($value)) {
                $type = 'json';
            } else {
                $type = 'string';
            }
        }

        $serializedValue = $type === 'json' ? json_encode($value) : (string)$value;

        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $serializedValue, 'type' => $type]
        );
    }

    /**
     * Cast the value to its correct type.
     */
    private static function castValue($value, string $type)
    {
        return match ($type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int)$value,
            'json' => json_decode($value, true),
            default => $value,
        };
    }
}
