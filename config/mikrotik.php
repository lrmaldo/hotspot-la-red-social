<?php

return [
    'user'     => env('MIKROTIK_API_USER', 'admin'),
    'password' => env('MIKROTIK_API_PASSWORD', ''),
    'port'     => (int) env('MIKROTIK_API_PORT', 8728),
    'timeout'  => (int) env('MIKROTIK_API_TIMEOUT', 5),
];
