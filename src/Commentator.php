<?php

namespace Plmrlnsnts\Commentator;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Route;

class Commentator
{
    /**
     * Get the commentable model based from a key.
     *
     * @param string $key
     * @return mixed
     */
    public function getCommentable($key)
    {
        [$alias, $id] = explode('::', base64_decode($key));

        $class = Relation::getMorphedModel($alias) ?? $alias;

        return $class::findOrFail($id);
    }

    /**
     * Binds commentator routes to controller.
     *
     * @return void
     */
    public static function routes($options = [])
    {
        $options = array_merge([
            'namespace' => 'Plmrlnsnts\Commentator\Http\Controllers'
        ], $options);

        Route::group($options, function ($router) {
            $router->apiResource('comments', 'CommentsController');
            $router->apiResource('comments.replies', 'RepliesController');
        });
    }
}
