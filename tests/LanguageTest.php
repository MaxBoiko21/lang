<?php

// namespace SmartCms\Lang\Tests;

// use SmartCms\Lang\Database\Factories\LanguageFactory;
// use SmartCms\Lang\Models\Language;

// class LanguageTest extends TestCase
// {
//     /** @test */
//     public function only_one_language_can_be_default()
//     {
//         // Create first default language
//         $defaultLanguage1 = LanguageFactory::new()->create([
//             'name' => 'English',
//             'slug' => 'en',
//             'locale' => 'en_US',
//             'is_default' => true,
//             'is_admin_active' => true,
//             'is_frontend_active' => true,
//         ]);

//         // Create second language and set it as default
//         $defaultLanguage2 = LanguageFactory::new()->create([
//             'name' => 'Spanish',
//             'slug' => 'es',
//             'locale' => 'es_ES',
//             'is_default' => false,
//             'is_admin_active' => true,
//             'is_frontend_active' => true,
//         ]);

//         // Set the second language as default
//         $defaultLanguage2->update(['is_default' => true]);

//         // Refresh both models from database
//         $defaultLanguage1->refresh();
//         $defaultLanguage2->refresh();

//         // Assert that only the second language is now default
//         $this->assertFalse($defaultLanguage1->is_default);
//         $this->assertTrue($defaultLanguage2->is_default);

//         // Verify only one language is default in the database
//         $this->assertEquals(1, Language::where('is_default', true)->count());
//     }

//     /** @test */
//     public function setting_language_as_default_removes_default_from_others()
//     {
//         // Create multiple languages with one being default
//         $defaultLanguage = LanguageFactory::new()->create([
//             'name' => 'English',
//             'slug' => 'en',
//             'locale' => 'en_US',
//             'is_default' => true,
//             'is_admin_active' => true,
//             'is_frontend_active' => true,
//         ]);

//         $language2 = LanguageFactory::new()->create([
//             'name' => 'Spanish',
//             'slug' => 'es',
//             'locale' => 'es_ES',
//             'is_default' => false,
//             'is_admin_active' => true,
//             'is_frontend_active' => true,
//         ]);

//         $language3 = LanguageFactory::new()->create([
//             'name' => 'French',
//             'slug' => 'fr',
//             'locale' => 'fr_FR',
//             'is_default' => false,
//             'is_admin_active' => true,
//             'is_frontend_active' => true,
//         ]);

//         // Set language3 as default
//         $language3->update(['is_default' => true]);

//         // Refresh all models
//         $defaultLanguage->refresh();
//         $language2->refresh();
//         $language3->refresh();

//         // Assert that only language3 is default
//         $this->assertFalse($defaultLanguage->is_default);
//         $this->assertFalse($language2->is_default);
//         $this->assertTrue($language3->is_default);
//     }

//     /** @test */
//     public function if_language_is_frontend_active_it_should_be_admin_active()
//     {
//         // Create a language that is frontend active but admin inactive
//         $language = LanguageFactory::new()->create([
//             'name' => 'English',
//             'slug' => 'en',
//             'locale' => 'en_US',
//             'is_default' => false,
//             'is_admin_active' => false,
//             'is_frontend_active' => true,
//         ]);

//         // The model's booted method should automatically set admin_active to true
//         $language->refresh();

//         $this->assertTrue($language->is_admin_active);
//         $this->assertTrue($language->is_frontend_active);
//     }

//     /** @test */
//     public function setting_frontend_active_enables_admin_active_automatically()
//     {
//         // Create a language that is initially inactive
//         $language = LanguageFactory::new()->create([
//             'name' => 'English',
//             'slug' => 'en',
//             'locale' => 'en_US',
//             'is_default' => false,
//             'is_admin_active' => false,
//             'is_frontend_active' => false,
//         ]);

//         // Set frontend active
//         $language->update(['is_frontend_active' => true]);

//         // Refresh the model
//         $language->refresh();

//         // Assert that admin_active is now true
//         $this->assertTrue($language->is_admin_active);
//         $this->assertTrue($language->is_frontend_active);
//     }

//     /** @test */
//     public function admin_active_can_be_false_when_frontend_is_inactive()
//     {
//         // Create a language that is admin active but frontend inactive
//         $language = LanguageFactory::new()->create([
//             'name' => 'English',
//             'slug' => 'en',
//             'locale' => 'en_US',
//             'is_default' => false,
//             'is_admin_active' => true,
//             'is_frontend_active' => false,
//         ]);

//         // Set admin_active to false
//         $language->update(['is_admin_active' => false]);

//         // Refresh the model
//         $language->refresh();

//         // Assert that admin_active can be false when frontend is inactive
//         $this->assertFalse($language->is_admin_active);
//         $this->assertFalse($language->is_frontend_active);
//     }

//     /** @test */
//     public function multiple_languages_can_be_admin_active_when_frontend_inactive()
//     {
//         // Create multiple languages that are admin active but frontend inactive
//         $language1 = LanguageFactory::new()->create([
//             'name' => 'English',
//             'slug' => 'en',
//             'locale' => 'en_US',
//             'is_default' => false,
//             'is_admin_active' => true,
//             'is_frontend_active' => false,
//         ]);

//         $language2 = LanguageFactory::new()->create([
//             'name' => 'Spanish',
//             'slug' => 'es',
//             'locale' => 'es_ES',
//             'is_default' => false,
//             'is_admin_active' => true,
//             'is_frontend_active' => false,
//         ]);

//         // Both should remain admin active
//         $this->assertTrue($language1->is_admin_active);
//         $this->assertTrue($language2->is_admin_active);
//         $this->assertFalse($language1->is_frontend_active);
//         $this->assertFalse($language2->is_frontend_active);
//     }

//     /** @test */
//     public function creating_language_with_frontend_active_sets_admin_active()
//     {
//         // Create a language directly with frontend active but admin inactive
//         $language = new Language([
//             'name' => 'English',
//             'slug' => 'en',
//             'locale' => 'en_US',
//             'is_default' => false,
//             'is_admin_active' => false,
//             'is_frontend_active' => true,
//         ]);

//         $language->save();

//         // Refresh from database
//         $language->refresh();

//         // Assert that admin_active is automatically set to true
//         $this->assertTrue($language->is_admin_active);
//         $this->assertTrue($language->is_frontend_active);
//     }
// }
