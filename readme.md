# Commentator 🤭

You're supposed to be using a third-party comment system not this.

But if you really need to, this package lets you add comments to your Eloquent models.

## Demo

Play around with this [demo project](https://commentator-demo.herokuapp.com/)

## Installation

``` bash
$ composer require plmrlnsnts/commentator
```

Run the following command to publish config and migration files.

```bash
php artisan vendor:publish --provider="Plmrlnsnts\Commentator\CommentatorServiceProvider"
```

Run the migrations.

```bash
php artisan migrate
```

Next, register the routes to manage comments in the `boot` method of your `AppServiceProvider`.

```php
use Plmrlnsnts\Commentator\Commentator;

public function boot()
{
    Commentator::routes();
}
```

## Usage

Add the `HasComments` trait to your eloquent models.

```php
use Plmrlnsnts\Commentator\HasComments;

class Article extends Model
{
    use HasComments;
}
```

### Comments

To add a comment to your model, use the `addComment` method.

```php
$article = Article::first();

$article->addComment(['body' => 'Better call Saul!']);
```

If you also support media files, pass an additional `media` attribute.

```php
$article->addComment([
    'body' => 'Yo Mr. White! Check this out.',
    'media' => 'https://unsplash.com/photos/yplNhhXxBtM',
]);
```

### Mentions

A user can be mentioned using `@` followed by a combination of alphanumeric characters, underscores and hypens.

```php
$comment = $article->addComment(['body' => '@Pinkman']);
```

Call the `mentionedNames` method to retrieve an array of mentions.

```php
$comment->mentionedNames();

// ['Pinkman']
```

Mentions are transformed to anchor tags by calling `asHtml` to a comment instance.

```php
$comment->asHtml();

// <a href="/profile/Pinkman">@Pinkman</a>
```
> The `asHtml` strips any html element except anchor tags to prevent xss attacks.

### Replies

If you want to support nested comments, use the `addReply` method.

```php
$comment->addReply(['body' => 'I am Heisenberg.']);
```

## JSON API

Commentator provides a JSON API that you may use to manage comments. This saves you the trouble of having to manually code controllers for creating, updating, and deleting comments.

#### `GET /comments`

This route returns a paginated list of comments for a given model. You need to pass the `commentableKey`, and an optional `sort` parameter to order the result from `latest`.

```javascript
const params = {
    commentableKey: 'SOMESTRING',
    sort: 'latest',
    page: 1,
    perPage: 10,
}

axios.get('/comments', { params })
    .then(response => {
        console.log(response.data)
    })
```

#### `POST /comments`

This route is used to create new comments. It accepts two pieces of data: a body and/or a media.

```javascript
const data = {
    body: 'Yo, Mr. White! Check this out.',
    media: 'https://unsplash.com/photos/yplNhhXxBtM',
    commentableKey: 'SOMESTRING',
}

axios.post('/comments', data)
    .then(response => {
        console.log(response.data)
    })
```

#### `PATCH /comments/{comment}`

This route is used to update comments. It accepts two pieces of data: a body and/or a media.

```javascript
const data = {
    body: 'Changed',
}

axios.patch(`/comments/${commment.id}`, data)
    .then(response => {
        console.log(response.data)
    })
```

> Only comments that are *owned* by the authenticated user can be updated.

#### `DELETE /comments/{comment}`

This route is used to delete comments.

```javascript
axios.delete(`/comments/${commment.id}`)
    .then(response => {
        //
    })
```

> Only comments that are *owned* by the authenticated user can be deleted.

#### `GET /comments/{comment}/replies`

This route returns a paginated list of replies for a comment. You may pass an optional `sort` parameter to order the result from `latest`.

```javascript
const params = {
    sort: 'latest',
    page: 1,
    perPage: 10,
}

axios.get(`/comments/${comment.id}/replies`, { params })
    .then(response => {
        console.log(response.data)
    })
```

#### `POST /comments/{comment}/replies`

This route is used to reply to a comment. It accepts two pieces of data: a body and/or a media.

```javascript
const data = {
    body: 'Yo, Mr. White! Check this out.',
    media: 'https://unsplash.com/photos/yplNhhXxBtM',
}

axios.post(`/comments/${comment.id}/replies`, data)
    .then(response => {
        console.log(response.data)
    })
```

> Replies are **comments too!** So you can re-use the same routes for updating and deleting replies.

## Configuration

### User Model

Commentator references the current `User` model as the author when adding comments. If you are using a different namespace, change them in `config/commentator.php`.

```php
return [
    'models' => [
        'user' => \App\Models\User::class
    ]
];
```

### Mentions

The regular expression used to identify mentions, and the parsed link can be modified from the `config` file.

```php
return [
   'mentions' => [
        'regex' => '/@([\w\-]+)/',
        'replace' => '<a href="/profile/$1">@$1</a>'
    ]
];
```
