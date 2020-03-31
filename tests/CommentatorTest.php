<?php

namespace Plmrlnsnts\Commentator\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Plmrlnsnts\Commentator\Facades\Commentator;
use Plmrlnsnts\Commentator\Tests\Fixtures\Commentable;

class CommentatorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_retrieve_the_commentable_model()
    {
        $commentable = factory(Commentable::class)->create();

        $result = Commentator::getCommentable($commentable->commentableKey());

        $this->assertTrue($result->is($commentable));
    }

    /** @test */
    public function it_can_register_routes()
    {
        Commentator::routes();

        Route::getRoutes()->refreshNameLookups();

        $this->assertTrue(Route::has('comments.index'));
        $this->assertTrue(Route::has('comments.store'));
        $this->assertTrue(Route::has('comments.update'));
        $this->assertTrue(Route::has('comments.destroy'));
        $this->assertTrue(Route::has('comments.replies.index'));
    }
}
