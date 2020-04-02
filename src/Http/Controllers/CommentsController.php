<?php

namespace Plmrlnsnts\Commentator\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Plmrlnsnts\Commentator\Comment;
use Plmrlnsnts\Commentator\Facades\Commentator;

class CommentsController
{
    /**
     * Get all comments.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Commentator::getCommentable(request('commentableKey'))
            ->comments()
            ->with('author')
            ->withCount('replies')
            ->when(request('sort') === 'latest', function ($query) {
                $query->orderByDesc('created_at');
            })
            ->paginate(request('perPage'));
    }

    /**
     * Store a new comment.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attributes = $this->validateRequest($request);

        $commentable = Commentator::getCommentable(request('commentableKey'));

        return tap($commentable->addComment($attributes), function ($comment) {
            $comment->load('author');
            $comment->loadCount('replies');
        });
    }

    /**
     * Update a comment.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Plmrlnsnts\Commentator\Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        Gate::authorize('update', $comment);

        $attributes = $this->validateRequest($request);

        $comment->update($attributes);

        $comment->load('author');
        $comment->loadCount('replies');

        return $comment;
    }

    /**
     * Delete a comment.
     *
     * @param \Plmrlnsnts\Commentator\Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        Gate::authorize('delete', $comment);

        $comment->delete();

        return response()->noContent();
    }

    /**
     * Validate the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    protected function validateRequest(Request $request)
    {
        return $request->validate([
            'body' => ['required_without:media', 'nullable', 'string'],
            'media' => ['nullable', 'url'],
        ]);
    }
}
