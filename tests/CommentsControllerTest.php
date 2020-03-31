<?php

namespace Plmrlnsnts\Commentator\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Plmrlnsnts\Commentator\Comment;
use Plmrlnsnts\Commentator\Tests\Fixtures\Commentable;
use Plmrlnsnts\Commentator\Tests\Fixtures\User;

class CommentsControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_view_all_comments()
    {
        $commentable = factory(Commentable::class)->create();

        factory(Comment::class)->create(['commentable_id' => $commentable->id]);

        $this->json('get', '/comments', [
            'commentableKey' => $commentable->commentableKey(),
        ])->assertSuccessful();
    }

    /** @test */
    public function a_user_can_create_a_comment()
    {
        $this->be(factory(User::class)->create());

        $commentable = factory(Commentable::class)->create();

        $this->post('/comments', factory(Comment::class)->raw([
            'commentableKey' => $commentable->commentableKey(),
        ]));

        $this->assertCount(1, $commentable->comments);
    }

    /** @test */
    public function a_user_can_update_a_comment()
    {
        $comment = factory(Comment::class)->create();

        $this->be($comment->author);

        $this->patch("/comments/{$comment->id}", ['body' => 'Changed']);

        $this->assertEquals('Changed', $comment->fresh()->body);
    }

    /** @test */
    public function a_user_can_delete_a_comment()
    {
        $comment = factory(Comment::class)->create();

        $this->be($comment->author);

        $this->delete("/comments/{$comment->id}");

        $this->assertNull($comment->fresh());
    }

    /** @test */
    public function a_comment_can_only_be_managed_by_the_author()
    {
        $this->be(factory(User::class)->create());

        $comment = factory(Comment::class)->create();

        $this->patch("/comments/{$comment->id}")->assertForbidden();

        $this->delete("/comments/{$comment->id}")->assertForbidden();
    }
}
