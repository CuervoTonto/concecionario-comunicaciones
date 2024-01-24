<?php

use Src\Support\Configuration;

return new Configuration([
    'aliases' => [
        // 'proof' => 'Src\App\Middlewares\ProofMiddleware',
    ],

    'groups' => [
        'basic' => [
            // 'proof:jason'
        ],
    ],

    'priority' => [
        // 'Src\App\Middlewares\ProofMiddleware'
    ]
]);