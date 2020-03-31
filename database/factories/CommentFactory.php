<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Plmrlnsnts\Commentator\Comment;
use Plmrlnsnts\Commentator\Tests\Fixtures\Commentable;
use Plmrlnsnts\Commentator\Tests\Fixtures\User;

$factory->define(Comment::class, function (Faker $faker, array $attributes = []) {
    return [
        'user_id' => factory(User::class),
        'commentable_id' => $attributes['commentable_id'] ?? factory(Commentable::class),
        'commentable_type' => Commentable::class,
        'body' => $faker->sentence,
        'media' => $faker->imageUrl(),
    ];
});
