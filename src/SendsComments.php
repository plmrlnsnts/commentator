<?php

namespace Plmrlnsnts\Commentator;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;
use Plmrlnsnts\Commentator\Comment;
use Plmrlnsnts\Commentator\Facades\Commentator;

trait SendsComments
{
    /**
     * Get all comments.
     *
     * @param string $key
     * @return \Illuminate\Http\Response
     */
    public function index($key)
    {
        $commentable = Commentator::getCommentable($key);

        return JsonResource::collection(
            $commentable->comments()
                ->with('author')
                ->withCount('comments')
                ->orderByDesc('id')
                ->cursorPaginate()
        );
    }

    /**
     * Store a new comment.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $key
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $key)
    {
        $attributes = $this->validateRequest($request);

        $commentable = Commentator::getCommentable($key);

        $comment = $commentable->addComment($attributes);

        return $this->created($request, $comment) ?: new JsonResource($comment);
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

        return $this->updated($request, $comment) ?: new JsonResource($comment);
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

        return $this->deleted($comment) ?: $comment->delete();
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
            'body' => ['required', 'string'],
            'media' => ['nullable', 'url'],
        ]);
    }

    /**
     * The comment has been created.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed $comment
     * @return mixed
     */
    protected function created(Request $request, $comment)
    {
        //
    }

    /**
     * The comment has been updated.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed $comment
     * @return mixed
     */
    protected function updated(Request $request, $comment)
    {
        //
    }

    /**
     * The comment has been deleted.
     *
     * @param mixed $comment
     * @return mixed
     */
    protected function deleted($comment)
    {
        //
    }
}
