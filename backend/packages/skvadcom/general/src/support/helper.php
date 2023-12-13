<?php

use Illuminate\Support\Facades\Cache;
use Skvadcom\General\General;

if (! function_exists('settings')) {
    /**
     * settings from admin
     *
     * @param  mixed  $parameter <p>A <code>paraméter</code>, ami alapján felvették a beállításokban az értéket</p>
     * @param  mixed  $safeback (opcionális) <p>Ha a paraméter nem található, akkor milyen értékkel térjen vissza a függvény (default <code>false</code>)</p>
     * @param  bool  $cache (opcionális) <p>Amennyiben nem a cachelt értéket akarjuk megkapni, a <code>false</code> értékkel friss db query-t indíthatunk</p>
     */
    function settings($parameter, $safeback = false, $cache = true)
    {
        if (! $cache) {
            $settings = General::all();
        } else {
            $settings = Cache::rememberForever('general_settings', function () {
                return General::all();
            });
        }

        foreach ($settings as $setting) {
            if ($setting->key === $parameter) {
                return $setting->value;
            }
        }

        if (! $safeback) {
            return false;
        } else {
            return $safeback;
        }
    }
}
