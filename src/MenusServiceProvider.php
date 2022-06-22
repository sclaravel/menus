<?php

namespace TysonLaravel\Menus;

use Illuminate\Support\ServiceProvider;
use TysonLaravel\Menus\Menu;

class MenusServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected bool $defer = true;

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $this->registerNamespaces();
        $this->registerMenusFile();
    }

    /**
     * Register package's namespaces.
     */
    protected function registerNamespaces()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/menus.php', 'menus');
        $this->loadViewsFrom(__DIR__ . '/../views', 'menus');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/menus.php' => config_path('menus.php'),
            ], 'config');

            $this->publishes([
                __DIR__ . '/src/views' => $this->app->resourcePath('views/vendor/menus'),
            ], 'views');
        }
    }

    /**
     * Require the menus file if that file is exists.
     */
    public function registerMenusFile()
    {
        if (file_exists($file = config_path('menu_list.php'))) {
            require $file;
        }
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->singleton('menus', function ($app) {
            return new Menu($app['view'], $app['config']);
        });

        $this->app->alias('menus', Menu::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['menus', Menu::class];
    }
}
