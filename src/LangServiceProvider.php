<?php

namespace SmartCms\Lang;

use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route as FacadesRoute;
use SmartCms\Lang\Middlewares\Lang;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LangServiceProvider extends PackageServiceProvider
{
    public static string $name = 'lang';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('smart-cms/lang');
            })
            ->hasConfigFile()
            ->hasMigration('create_languages_table');
    }

    public function packageBooted()
    {
        $this->registerMiddleware();
        $this->app->singleton(Languages::class, function ($app) {
            return new Languages;
        });
        $this->app->alias(Languages::class, 'lang');
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../database/seeders/LanguageSeeder.php' => database_path('seeders/LanguageSeeder.php'),
            ], 'language-seeder');
        }
    }

    public function registerMiddleware()
    {
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('lang', Lang::class);
        Route::macro('multilingual', function () {
            /** @var \Illuminate\Routing\Route $this */
            $uri = $this->uri();
            $cleanUri = ltrim($uri, '/');
            FacadesRoute::addRoute(
                $this->methods(),
                '{lang}/'.$cleanUri,
                $this->getAction()
            )->where('lang', '[a-z]{2}')->name('.lang')->middleware('lang');

            return $this;
        });
    }
}
