<?php

namespace Plmrlnsnts\Commentator;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Plmrlnsnts\Commentator\Policies\CommentPolicy;

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
    }
}
