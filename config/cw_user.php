<?php

return [
    'layout' => env('CW_LAYOUT', 'layouts.app'),
    'views' => env('CW_VIEWS', 'user::'),

    'database' => [
        'cpf_cnpj' => [
            'after' => 'email',
            'unique' => true,
            'nullable' => true,
            'default' => NULL
        ]
    ]
];
