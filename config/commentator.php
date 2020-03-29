<?php

return [
    'models' => [
        'user' => \App\User::class
    ],
    'mentions' => [
        'regex' => '/@([\w\-]+)/',
        'replace' => '<a href="/profile/$1">@$1</a>'
    ]
];
