$post = Post::find(1);

$post->addComment('Hey!');

$post->addComment($attributes);

$post->comments()->get();

$comment->update(['body' => 'This is a test.']);

$comment->delete();

$comment->replies()->get();

$comment->addReply('Lavapalooza!');

$comment->addReply($attributes);

$comment->mentionedNames(); // ['Frodo'];

$comment->markBestAnswer();

$comment->unmarkBestAnswer();

[get] /comments
    commentableKey=SOMESTRING
    sort=newest
    cursor=10

[post] /comments
    commentableKey=SOMESTRING
    body=SOMESTRING
    media=http://lorempixel.com/100x100

[patch] /{comment}/comments
    body=NEWSTRING
    media=http://lorempixel.com/100x100

[delete] /{comment}/comments

[post] /{comment}/comments/best-reply

[delete] /{comment}/comments/best-reply

[get] /{comment}/comments/replies
    sort=newest
    cursor=10

[store] /{comment}/comments/replies
    body=SOMESTRING
    media=http://lorempixel.com/100x100
