<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => [
        'https://absensi-frontend-yun5.vercel.app',
        'https://absensi-frontend-final.vercel.app',
        'http://localhost:5173',
        'http://localhost:3000',
    ],
    'allowed_origins_patterns' => ['https://*.vercel.app'],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
