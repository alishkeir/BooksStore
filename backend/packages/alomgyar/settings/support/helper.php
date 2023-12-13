<?php

use Alomgyar\Settings\Settings;
use Illuminate\Support\Facades\Cache;

if (! function_exists('option')) {
    /**
     * option from admin
     *
     * @param  mixed  $parameter <p>A <code>paraméter</code>, ami alapján felvették a beállításokban az értéket</p>
     * @param  mixed  $safeback (opcionális) <p>Ha a paraméter nem található, akkor milyen értékkel térjen vissza a függvény (default <code>false</code>)</p>
     * @param  bool  $cache (opcionális) <p>Amennyiben nem a cachelt értéket akarjuk megkapni, a <code>false</code> értékkel friss db query-t indíthatunk</p>
     */
    function option($parameter, $safeback = false, $cache = true)
    {
        if (! $cache) {
            $settings = Settings::all();
        } else {
            $settings = Cache::remember('settings_options', 1200, function () {
                return Settings::all();
            });
        }

        foreach ($settings as $setting) {
            if ($setting->key === $parameter) {
                return $setting->primary;
            }
        }

        if (! $safeback) {
            return false;
        } else {
            return $safeback;
        }
    }

    function options($safeback = false, $cache = true)
    {
        $settings = Settings::all();

        return $settings;

        if (! $safeback) {
            return false;
        } else {
            return $safeback;
        }
    }
}
