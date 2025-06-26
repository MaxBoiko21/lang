<?php

namespace SmartCms\Lang\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use SmartCms\Lang\Models\Language;

class LanguageFactory extends Factory
{
    protected $model = Language::class;

    public function definition()
    {
        return [
            'name' => str()->random(10),
            'slug' => str()->random(2),
            'locale' => str()->random(5),
            'is_default' => random_int(0, 1),
            'is_admin_active' => random_int(0, 1),
            'is_frontend_active' => random_int(0, 1),
        ];
    }
}
