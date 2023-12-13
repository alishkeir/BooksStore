<?php

namespace App\Helpers;
use Alomgyar\Settings\Settings;
use Illuminate\Support\Facades\Cache;

class SettingsHelper
{
    const SETTING_SECTIONS = [
        'altalanos' => 'Általános',
        'alomgyar' => 'Álomgyár',
        'olcsokonyvek' => 'Olcsókönyvek',
        'nagyker' => 'Nagyker',
        'affiliate' => 'Affiliate',
    ];
    public static function getSettingsByKeys($keysArray){
        $settings = [];
        foreach ($keysArray as $key) {
            $settings[$key] = self::getSettingByKey($key);
        }
        return $settings;
    }
    public static function getSettingByKey($key){
        return Cache::rememberForever($key, function () use ($key) {
            if ($setting = Settings::SearchKey($key)->first()) {
                return $setting->primary;
            }
            return '';
        });
    }
}
