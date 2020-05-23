<?php

use App\Controller\AuthController;
use App\Controller\StatisticsController;

return [
    '/statistics' => [
        'controller' => StatisticsController::class,
        'method' => 'getStatistics',
    ],
    '/register' => [
        'controller' => AuthController::class,
        'method' => 'register'
    ],
];
