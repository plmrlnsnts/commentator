# Commentator 🤭

You're supposed to be using a third-party comment system not this.

But if you really need to, this package lets you add a comments to your models.

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

Add the `HasComments` trait to any of your eloquent models.

```php
class Article extends Model
{
    use \Plmrlnsnts\Commentator\HasComments;
    
    protected $appends = [
        'commentableKey'
    ];
}
```

To add a comment to your model,

```php
$article = Article::first();

$article->addComment(['body' => 'Say my name.']);
```

If you also support media uploads, pass an additional `media` attribute.

```php
$article->addComment([
    'body' => 'Yo, check this out.',
    'image' => 'https://unsplash.com/photos/yplNhhXxBtM',
]);
```
