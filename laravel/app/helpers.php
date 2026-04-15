<?php

use App\Models\Setting;

if (!function_exists('setting')) {
    function setting(string $key, $default = null): ?string
    {
        return Setting::get($key, $default);
    }
}
