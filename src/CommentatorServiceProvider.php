<?php

namespace Plmrlnsnts\Commentator;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class CommentatorServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        Gate::policy(Comment::class, CommentPolicy::class);

        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'commentator');

        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'plmrlnsnts');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'plmrlnsnts');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/commentator.php', 'commentator');

        // Register the service the package provides.
        $this->app->singleton('commentator', function ($app) {
            return new Commentator;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['commentator'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/commentator.php' => config_path('commentator.php'),
        ], 'commentator.config');

        // Publishing the migrations.
        $this->publishes([
            __DIR__.'/../database/migrations/create_comments_table.php' =>
                database_path('migrations/' . date('Y_m_d_His') . '_create_comments_table.php')
        ], 'migrations');

        // Publishing the views.
        // $this->publishes([
        //     __DIR__.'/../resources/views' => resource_path('views/vendor/commentator'),
        // ]);

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/plmrlnsnts'),
        ], 'commentator.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/plmrlnsnts'),
        ], 'commentator.views');*/

        // Registering package commands.
        $this->commands([CommentatorCommand::class]);
    }
}
