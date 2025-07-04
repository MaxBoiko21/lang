<?php

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Context;
use SmartCms\Lang\Languages;

if (! function_exists('tRoute')) {
    function tRoute(string $name, array $params = []): string
    {
        if (current_lang() == main_lang()) {
            return route($name, $params);
        }
        $params = array_merge($params, ['lang' => current_lang()]);
        if (! str_contains($name, 'lang')) {
            $name = $name.'.lang';
        }

        return route($name, $params);
    }
}

if (! function_exists('main_lang')) {
    function main_lang(): string
    {
        return app('lang')->default()->slug;
    }
}
if (! function_exists('current_lang')) {
    function current_lang(): string
    {
        return Context::get('current_lang') ?? main_lang();
    }
}
if (! function_exists('current_lang_id')) {
    function current_lang_id(): string
    {
        return app('lang')->current()->id;
    }
}
if (! function_exists('main_lang_id')) {
    function main_lang_id(): int
    {
        return app('lang')->default()->id;
    }
}
if (! function_exists('get_active_languages')) {
    function get_active_languages(): Collection
    {
        return app('lang')->adminLanguages();
    }
}
if (! function_exists('lang')) {
    function lang(): Languages
    {
        return app('lang');
    }
}
