<?php

function setting()
{
    $arguments = func_get_args();

    if (isset($arguments[0]) && is_string($arguments[0])) {
        // Return key
        return \App\Models\Tools\Setting::setting($arguments[0], $arguments[1] ?? null);
    } elseif (isset($arguments[0]) && is_array($arguments[0])) {
        // Create
        return (new \App\Models\Tools\Setting)->fill($arguments[0])->save();
    }

    return false;
}

function settingCategory()
{
    $arguments = func_get_args();

    if (isset($arguments[0]) && is_string($arguments[0])) {
        // Return by key
        return \App\Models\Tools\SettingCategory::category($arguments[0], $arguments[1] ?? null);
    } elseif (isset($arguments[0]) && is_array($arguments[0])) {
        // Create
        return (new \App\Models\Tools\SettingCategory)->fill($arguments[0])->save();
    }

    return false;
}

/**
 * Convert a string representing an email list
 * separated by a new line or comma
 * verify each is an actual email address
 * @param  string $list
 * @return array
 */
function emails($list)
{
    $list = collect(explode("\n", str_replace([',', "\r\n"], "\n", $list)));

    return $list->reject(function ($value, $key) {
        return !filter_var($value, FILTER_VALIDATE_EMAIL);
    })->all();
}