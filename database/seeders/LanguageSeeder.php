<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use SmartCms\Lang\Database\Factories\LanguageFactory;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        LanguageFactory::new()->create([
            'name' => 'English',
            'slug' => 'en',
            'locale' => 'en_US',
            'is_default' => true,
            'is_admin_active' => true,
            'is_frontend_active' => true,
        ]);
    }
}
