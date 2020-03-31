<?php

namespace Plmrlnsnts\Commentator\Policies;

use Illuminate\Foundation\Auth\User;
use Plmrlnsnts\Commentator\Comment;

class CommentPolicy
{
    /**
     * Determine if the given comment can be updated by the user.
     *
     * @param  \Illuminate\Foundation\Auth\User  $user
     * @param  \Plmrlnsnts\Commentator\Comment  $comment
     * @return \Illuminate\Auth\Access\Response
     */
    public function update(User $user, Comment $comment)
    {
        return $user->is($comment->author);
    }

    /**
     * Determine if the given comment can be deleted by the user.
     *
     * @param  \Illuminate\Foundation\Auth\User  $user
     * @param  \Plmrlnsnts\Commentator\Comment  $comment
     * @return \Illuminate\Auth\Access\Response
     */
    public function delete(User $user, Comment $comment)
    {
        return $user->is($comment->author);
    }
}
