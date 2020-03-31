<?php

namespace Plmrlnsnts\Commentator\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Plmrlnsnts\Commentator\Comment;
use Plmrlnsnts\Commentator\Tests\Fixtures\User;

class RepliesControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_view_all_replies()
    {
        $this->withoutExceptionHandling();

        $this->be(factory(User::class)->create());

        $comment = factory(Comment::class)->create();

        $comment->addReply(['body' => 'Hey!']);

        $this->get(route('comments.replies.index', $comment))->assertSuccessful();
    }

    /** @test */
    public function a_user_can_reply_to_a_comment()
    {
        $this->withoutExceptionHandling();

        $this->be(factory(User::class)->create());

        $comment = factory(Comment::class)->create();

        $this->post(route('comments.replies.store', $comment), ['body' => 'Yo']);

        $this->assertCount(1, $comment->replies);
    }
}
