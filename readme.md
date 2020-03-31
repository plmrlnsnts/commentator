# Commentator 🤭

You're supposed to be using a third-party comment system not this.

But if you really need to, this package lets you add a comment section to your pages.

## Install

``` bash
$ composer require plmrlnsnts/commentator
```

## Prerequisites

Run the following command to publish files we will need later on.

```bash
php artisan vendor:publish --provider="Plmrlnsnts\Commentator\CommentatorServiceProvider"
```

You can change the `User` namespace in `config/commentator.php`.

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

## Models

Users may start adding comments once the `HasComments` trait has been added to an eloquent model.

```php
class Article extends Model
{
    use \Plmrlnsnts\Commentator\HasComments;
}
```

## Livewire

This package has few Livewire components that can display a comment section in any of your blade templates. Here is a template to get you started.

```php
<html>
<head>
    ...
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
    @livewireStyles
</head>

<body>
    <main>
        ...
        @livewire('commentator::comments', ['commentable' => $article])
    </main>

    ...
    @livewireScripts
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine-ie11.js" defer></script>
</body>
</html>
```

## Customizations

Unsatistfied with the default appearance? Modify the component views in `resources/views/vendor/commentator` directory to match your preference.

## API Controllers

There is also an artisan command to scaffold the comment controller and routes for you.

```bash
php artisan commentator:make
```
