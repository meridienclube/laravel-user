<?php

return [
    'layout' => env('CW_LAYOUT', 'layouts.app'),
    'views' => env('CW_VIEWS', 'user::'),

    'datatable' => [
        'id' => 'datatable_tasks',
        'items' => [
            ['data' => 'name', 'name' => 'name', 'title' => 'Nome'],
            ['data' => 'email', 'name' => 'email', 'title' => 'E-mail'],
            ['data' => 'roles', 'render' => 'implode.display_name', 'name' => 'roles', 'title' => 'Perfis'],
            ['data' => 'status.name', 'render' => 'object', 'name' => 'status.name', 'title' => 'Status']
        ],
        'url' => 'users',
        'slug' => 'users',
        'btns' => [
            'show' => 'Ver',
            'edit' => 'Editar',
            'destroy' => 'Deletar',
        ]
    ],
    'request' => [
        'messages' => [
            'name.required' => 'O nome é necessário para criar um novo registro',
            'status_id.required' => 'O status é necessário para criar um novo registro',
            'password.required' => 'A senha é necessário para criar um novo registro',
            'password.confirmed' => 'A senha tem que ser igual a confirmação',
            'password.min' => 'A senha tem que conter pelo menos 6 caracteres',
            'email.required' => 'O e-mail é necessário para criar um novo registro',
            'email.email' => 'O e-mail precisa ser valido',
            'sync.roles.required' => 'O perfil é necessário para criar um novo registro',
            'status_id.required' => 'O status é necessário para criar um novo registro',
        ]
    ]
];
