<?php

namespace Plmrlnsnts\Commentator\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Plmrlnsnts\Commentator\Tests\Fixtures\Commentable;
use Plmrlnsnts\Commentator\Tests\Fixtures\User;

class HasCommentsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_be_commented_by_an_authenticated_user()
    {
        $this->be(factory(User::class)->create());

        $commentable = factory(Commentable::class)->create();

        $commentable->addComment(['body' => 'Yo!']);

        $this->assertCount(1, $commentable->comments);
    }

    /** @test */
    public function it_has_a_commentable_key()
    {
        $commentable = factory(Commentable::class)->create();

        $expected = base64_encode($commentable->getMorphClass() . '::' . $commentable->id);

        $this->assertEquals($expected, $commentable->commentableKey());
    }

    /** @test */
    public function it_appends_the_commentable_key_to_the_model()
    {
        $commentable = factory(Commentable::class)->create();

        $this->assertTrue(array_key_exists('commentableKey', $commentable->toArray()));
    }
}
