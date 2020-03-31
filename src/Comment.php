<?php

namespace Plmrlnsnts\Commentator;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Plmrlnsnts\Commentator\NewComment;
use Stevebauman\Purify\Facades\Purify;

class Comment extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

     /**
     * The "booting" method of the model.
     *
     * @return void
     */
     public static function boot()
     {
         parent::boot();

         static::created(function ($model) {
            NewComment::dispatch($model);
         });
     }

     /**
      * The author of this comment.
      */
     public function author()
     {
         return $this->belongsTo(config('commentator.models.user'), 'user_id');
     }

     /**
      * The replies for this comment.
      */
      public function replies()
      {
          return $this->hasMany(Comment::class, 'parent_id');
      }

     /**
      * Get the commentable model.
      */
     public function commentable()
     {
         return $this->morphTo();
     }

     /**
      * Determine if this comment is written by the specified user.
      *
      * @param \Illuminate\Foundation\Auth\User $user
      * @return boolean
      */
     public function isWrittenBy($user)
     {
         return $user->is($this->author);
     }

     /**
      * Determine if this comment has been edited.
      *
      * @return boolean
      */
     public function isEdited()
     {
         return $this->created_at != $this->updated_at;
     }

     /**
      * Get the mentioned names from the body of this comment.
      *
      * @return array
      */
     public function mentionedNames()
     {
        preg_match_all(
            config('commentator.mentions.regex'),
            $this->body,
            $matches
        );

        return $matches[1];
     }

     /**
      * The html representation of the body attribute.
      *
      * @return string
      */
     public function asHtml()
     {
        $html = preg_replace(
            config('commentator.mentions.regex'),
            config('commentator.mentions.replace'),
            $this->attributes['body']
        );

        return Purify::clean($html, ['HTML.Allowed' => 'a[href]']);
     }

     /**
      * Reply to this comment.
      *
      * @param array $values
      * @return \Plmrlnsnts\Commentator\Comment
      */
     public function addReply($values)
     {
         $values['user_id'] ??= auth()->id();

         $values['commentable_id'] = $this->commentable_id;
         $values['commentable_type'] = $this->commentable_type;

         return $this->replies()->create($values);
     }

     /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return array_merge(parent::toArray(), [
            'html' => $this->asHtml(),
            'isEdited' => $this->isEdited(),
            'mentionedNames' => $this->mentionedNames(),
            'can' => [
                'update' => Gate::check('update', $this),
                'delete' => Gate::check('delete', $this),
            ]
        ]);
    }
}
