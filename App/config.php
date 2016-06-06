<?php

return [
    'framework' => [
        'title'  => 'Sephy',
        'assets' => 'http://framework.com.br/assets',
    ],
    'app' => [
        'path_views'       => ROOT_APP.'Views',
        'path_views_cache' => ROOT_APP.'Cache',
    ],
    'smtp' => [
        'server'   => 'smtp.umbler.com',
        'port'     => 587,
        'username' => 'developer@adrielov.com.br',
        'password' => 'adriel007',
    ],
    'secret' => [
        'code' => 'PrjdfJNgscBbOZni60yIEmPLRULygZ7u4TV8pme3qv/A81RNYOSyo5fWKJbijXhI',
    ],
    'database' => [
        'providers' => [
            'pdo' => [
                'driver'    => 'mysql',
                'host'      => 'localhost',
                'database'  => 'sephy',
                'username'  => 'root',
                'password'  => '',
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => 'spy_',
            ],
        ],
    ],
    'middleware' => [
        'auth' => [
            '\App\Middlewares\AuthMiddleware',
        ],
        'admin' => [
            '\App\Middlewares\AdminMiddleware',
        ],
        'web' => [
            '\App\Middlewares\WebMiddleware',
        ],
    ],
];
