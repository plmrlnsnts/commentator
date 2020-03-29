<?php

namespace Plmrlnsnts\Commentator\Tests;

use Juampi92\CursorPagination\CursorPaginationServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Plmrlnsnts\Commentator\CommentatorServiceProvider;
use Plmrlnsnts\Commentator\Tests\Fixtures\User;

abstract class TestCase extends BaseTestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations();

        require_once __DIR__.'/../database/migrations/create_comments_table.php';
        require_once __DIR__.'/../database/migrations/create_commentables_test_table.php';

        (new \CreateCommentsTable)->up();
        (new \CreateCommentablesTestTable)->up();

        $this->withFactories(__DIR__.'/../database/factories');
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('commentator.models.user', User::class);
    }

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            CommentatorServiceProvider::class,
            CursorPaginationServiceProvider::class,
        ];
    }
}
