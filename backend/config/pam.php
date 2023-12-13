<?php
/*
Author: Hódi
Date: 2021. 05. 18. 14:10
Project: alomgyar-webshop-be
*/

return [
    'titles' => [
        'book' => 'Könyvek',
        'ebook' => 'E-könyvek',
        'promotions' => 'Akciók',
        'posts' => 'Magazin',
        'shops' => 'Álomgyár könyvesboltok',
    ],
    'api_prefix' => '/api/v1/',
    'sender' => [
        'api_token' => env('SENDER_API_TOKEN', null),
        'base_url' => env('SENDER_API_BASE_URL', 'https://api.sender.net/v2/'),
        'alomgyar_group' => env('SENDER_ALOMGYAR_GROUP', 'aKGG8a'),
        'olcsokonyvek_group' => env('SENDER_OLCSOKONYVEK_GROUP', 'dGPgre'),
    ],
    'stores' => [
        'alomgyar' => 0,
        'olcsokonyvek' => 1,
        'nagyker' => 2,
    ],
    'store_urls' => [
        0 => env('ALOM_URL', 'https://alomgyar.hu'),
        1 => env('OLCSO_URL', 'https://olcsokonyvek.hu'),
        2 => env('NAGYKER_URL', 'https://nagyker.alomgyar.hu'),
    ],
    'backend_url' => env('BACKEND_URL', 'https://pam.hu'),
    'recaptcha_key' => env('RECAPTCHA_KEY'),
    'google_api_key' => env('GOOGLE_API_KEY'),
    'google_project_id' => env('GOOGLE_PROJECT_ID'),

    'logo_url' => 'https://weborigo.hu/assets/img/logos/weborigo-logo-orange-white.svg',
];
