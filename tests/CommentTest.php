<?php

namespace Plmrlnsnts\Commentator\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Plmrlnsnts\Commentator\Comment;
use Plmrlnsnts\Commentator\NewComment;
use Plmrlnsnts\Commentator\Tests\Fixtures\User;
use Plmrlnsnts\Commentator\Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_dispatches_a_new_comment_event_when_it_is_created()
    {
        Event::fake([NewComment::class]);

        factory(Comment::class)->create();

        Event::assertDispatched(NewComment::class);
    }

    /** @test */
    public function it_can_determine_if_the_user_is_the_author_of_a_comment()
    {
        $comment = factory(Comment::class)->create();

        $definitelyNotTheAuthor = factory(User::class)->create();

        $this->assertTrue($comment->isWrittenBy($comment->author));

        $this->assertFalse($comment->isWrittenBy($definitelyNotTheAuthor));
    }

    /** @test */
    public function it_can_determine_mentioned_names()
    {
        $comment = new Comment(['body' => 'Hey @John!']);

        $this->assertContains('John', $comment->mentionedNames());
    }

    /** @test */
    public function it_can_determine_if_it_has_been_edited()
    {
        $comment = factory(Comment::class)->create();

        $this->assertFalse($comment->isEdited());

        \Carbon\Carbon::setTestNow(now()->addHour());

        $comment->update(['body' => 'Changed']);

        $this->assertTrue($comment->isEdited());
    }

    /** @test */
    public function it_transforms_mentions_to_links()
    {
        $comment = new Comment(['body' => 'Hi @john']);

        $this->assertEquals('Hi <a href="/profile/john">@john</a>', $comment->asHtml());
    }

    /** @test */
    public function it_can_add_replies()
    {
        $this->be(factory(User::class)->create());

        $comment = factory(Comment::class)->create();

        $comment->addReply(['body' => 'Yo!']);

        $this->assertCount(1, $comment->replies);
    }
}
