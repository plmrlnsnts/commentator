# Commentator 🤭

You're supposed to be using a third-party comment system not this.

But if you really need to, this package lets you add comments to your models.

## Install

``` bash
$ composer require plmrlnsnts/commentator
```

Run the following command to publish config and migration files.

```bash
php artisan vendor:publish --provider="Plmrlnsnts\Commentator\CommentatorServiceProvider"
```

If you are using a different namespace for the `User` model, change them in `config/commentator.php`.

```php
return [
    'models' => [
        'user' => \App\Models\User::class
    ]
];
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

Add the `HasComments` trait to your eloquent models. You also need to append the `commentableKey` attribute.

```php
class Article extends Model
{
    use \Plmrlnsnts\Commentator\HasComments;
    
    protected $appends = [
        'commentableKey'
    ];
}
```

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

## Mentions

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
> The `asHtml` strips any html element except `anchor` tags to prevent xss attacks.

The regular expression used to detect mentions and the transformed link can be modified from the `config` file.

```php
return [
   'mentions' => [
        'regex' => '/@([\w\-]+)/',
        'replace' => '<a href="/profile/$1">@$1</a>'
    ]
];
```

## Replies

If you want to support nested comments, use the `addReply` method.

```php
$comment->addReply(['body' => 'I am Heisenberg.']);
```
