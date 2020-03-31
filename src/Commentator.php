<?php

namespace Plmrlnsnts\Commentator;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Route;
use Plmrlnsnts\Commentator\Http\Controllers\CommentsController;

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
        Route::group($options, function ($router) {
            $router->get('/comments', [CommentsController::class, 'index'])->name('comments.index');
            $router->post('/comments', [CommentsController::class, 'store'])->name('comments.store');
            $router->patch('/comments/{comment}', [CommentsController::class, 'update'])->name('comments.update');
            $router->delete('/comments/{comment}', [CommentsController::class, 'destroy'])->name('comments.destroy');
        });
    }
}
