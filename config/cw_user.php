<?php

return [
    'layout' => env('CW_LAYOUT', 'layouts.app'),
    'views' => env('CW_VIEWS', 'user::'),

    'datatable' => [
        'id' => 'datatable_tasks',
        'items' => [
            ['data' => 'name', 'name' => 'name', 'title' => 'Nome'],
            ['data' => 'email', 'name' => 'email', 'title' => 'E-mail'],
            ['data' => 'roles', 'render' => '[, ].display_name', 'name' => 'roles', 'title' => 'Perfis'],
            ['data' => 'status.name', 'name' => 'status.name', 'title' => 'Status']
        ],
        'url' => 'users',
        'slug' => 'users',
        'btns' => [
            'show' => 'Ver',
            'edit' => 'Editar',
            'destroy' => 'Deletar',
        ]
    ]
];
