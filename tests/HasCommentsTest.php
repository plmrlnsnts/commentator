<?php

namespace Plmrlnsnts\Commentator\Tests;

use Illuminate\Database\Eloquent\Relations\Relation;
use Plmrlnsnts\Commentator\Tests\Fixtures\Commentable;
use Plmrlnsnts\Commentator\Tests\Fixtures\User;

class HasCommentsTest extends TestCase
{
    /** @test */
    public function it_can_be_commented_by_an_authenticated_user()
    {
        $this->be(factory(User::class)->create());

        $commentable = factory(Commentable::class)->create();

        $commentable->addComment(['body' => 'Yo!']);

        $this->assertCount(1, $commentable->comments);
    }

    /** @test */
    public function it_has_a_commentable_url()
    {
        $commentable = factory(Commentable::class)->create();

        $this->assertEquals(
            url($commentable->commentableKey() . '/comments'),
            $commentable->commentableUrl()
        );
    }

    /** @test */
    public function it_has_a_commentable_key()
    {
        $commentable = factory(Commentable::class)->create();

        $expected = base64_encode($commentable->getMorphClass() . '::' . $commentable->id);

        $this->assertEquals($expected, $commentable->commentableKey());
    }

    /** @test */
    public function it_respects_the_morph_map_definitions()
    {
        Relation::morphMap(['commentables' => Commentable::class]);

        $commentable = factory(Commentable::class)->create();

        $this->assertEquals(base64_encode('commentables::' . $commentable->id), $commentable->commentableKey());
    }
}
