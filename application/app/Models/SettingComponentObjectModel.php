<?php

namespace App\Models;

class SettingComponentObjectModel
{
    // Settings 테이블 조회
    public static function get($key, $default = null)
    {
        if (!$row = Setting::where('key', $key)->first()) {
            return $default;
        }
        return json_decode($row->value, true);
    }

    // Settings 테이블 삽입
    public static function set($key, $value = null)
    {
        if (is_array($key) && $value === null) {
            $rows = $key;
            foreach ($rows as $key => $value) {
                self::set($key, $value);
            }
        } else {
            $row = Setting::where('key', $key)->first() ?: new Setting;
            $row->key = $key;
            $row->value = json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            $row->save();
        }
    }
}
