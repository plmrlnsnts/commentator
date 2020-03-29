<?php

namespace Plmrlnsnts\Commentator;

use Illuminate\Database\Eloquent\Relations\Relation;

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
}
