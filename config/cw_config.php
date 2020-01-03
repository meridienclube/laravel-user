<?php

return [
    'database' => [
        'cpf_cnpj' => [
            'after' => 'email',
            'unique' => true,
            'nullable' => true,
            'default' => NULL
        ]
    ]
];
