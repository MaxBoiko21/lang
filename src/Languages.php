<?php

namespace SmartCms\Lang;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use SmartCms\Lang\Models\Language;

class Languages
{
    public Collection $languages;

    public ?Language $currentLanguage;

    private $currentLanguageInitialized = false;

    public function __construct()
    {
        $this->languages = Language::query()->get();
    }

    public function current(): Language
    {
        // return $this->currentLanguage ?? $this->default();
        if (! $this->currentLanguageInitialized || ! $this->currentLanguage) {
            return $this->default();
        }

        return $this->currentLanguage;
    }

    public function default(): Language
    {
        return $this->languages->where('is_default', true)->first() ?? $this->languages->first();
    }

    public function get(int $id): Language
    {
        return $this->languages->where('id', $id)->first();
    }

    public function getMulti(array $ids): Collection
    {
        return $this->languages->whereIn('id', $ids)->sort(function ($a, $b) {
            $main_lang = main_lang_id();
            if ($a->id === $main_lang && $b->id !== $main_lang) {
                return -1;
            }
            if ($b->id === $main_lang && $a->id !== $main_lang) {
                return 1;
            }

            return $a->id <=> $b->id;
        })->values();
    }

    public function setCurrent(string $slug)
    {
        $this->currentLanguage = $this->languages->where('slug', $slug)->first() ?? $this->default();
        $this->currentLanguageInitialized = true;

        return $this;
    }

    public function isFrontendAvailable(string $slug): bool
    {
        return $this->languages->where('is_frontend_active', true)->where('slug', $slug)->count() > 0;
    }

    public function isAdminAvailable(string $slug): bool
    {
        return $this->languages->where('is_admin_active', true)->where('slug', $slug)->count() > 0;
    }

    public function all(): Collection
    {
        return $this->languages;
    }

    public function getBySlug(string $slug): ?Language
    {
        return $this->languages->where('slug', $slug)->first();
    }

    public function frontLanguages(): Collection
    {
        return $this->languages->where('is_frontend_active', true);
    }

    public function adminLanguages(): Collection
    {
        return $this->languages->where('is_admin_active', true);
    }

    public function getUrlForAllLanguages(): array
    {
        $currentRoute = Route::current();
        $params = Route::current()->parameters();
        $routeName = $currentRoute->getName();

        return $this->frontLanguages()->mapWithKeys(function ($locale) use ($routeName, $params) {
            $routeName = str_replace('.lang', '', $routeName);

            return [$locale->slug => route($routeName . '.lang', array_merge($params, ['lang' => $locale->slug]))];
        })->toArray();
    }
}
