<?php

namespace Plmrlnsnts\Commentator\Tests;

use Illuminate\Support\Facades\Route;
use Plmrlnsnts\Commentator\Comment;
use Plmrlnsnts\Commentator\Tests\Fixtures\CommentsController;
use Plmrlnsnts\Commentator\Tests\Fixtures\Commentable;
use Plmrlnsnts\Commentator\Tests\Fixtures\User;

class SendsCommentsTest extends TestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware('bindings')->group(function() {
            Route::get('/{commentable}/comments', [CommentsController::class, 'index']);
            Route::post('/{commentable}/comments', [CommentsController::class, 'store']);
            Route::patch('/comments/{comment}', [CommentsController::class, 'update']);
            Route::delete('/comments/{comment}', [CommentsController::class, 'destroy']);
        });
    }

    /** @test */
    public function a_user_can_view_all_comments()
    {
        $commentable = factory(Commentable::class)->create();

        factory(Comment::class, 30)->create([
            'commentable_id' => $commentable->id
        ]);

        $this->json('get', $commentable->commentableUrl(), ['perPage' => 15])
            ->assertJsonCount(15, 'data')
            ->assertSuccessful();
    }

    /** @test */
    public function a_user_can_create_a_comment()
    {
        $this->be(factory(User::class)->create());

        $commentable = factory(Commentable::class)->create();

        $this->post($commentable->commentableUrl(), factory(Comment::class)->raw());

        $this->assertCount(1, $commentable->comments);
    }

    /** @test */
    public function a_user_can_update_a_comment()
    {
        $comment = factory(Comment::class)->create();

        $this->be($comment->author);

        $this->patch($comment->url(), ['body' => 'Changed']);

        $this->assertEquals('Changed', $comment->fresh()->body);
    }

    /** @test */
    public function a_user_can_delete_a_comment()
    {
        $comment = factory(Comment::class)->create();

        $this->be($comment->author);

        $this->delete($comment->url());

        $this->assertNull($comment->fresh());
    }

    /** @test */
    public function a_comment_can_only_be_managed_by_the_author()
    {
        $this->be(factory(User::class)->create());

        $comment = factory(Comment::class)->create();

        $this->patch($comment->url())->assertForbidden();

        $this->delete($comment->url())->assertForbidden();
    }
}
