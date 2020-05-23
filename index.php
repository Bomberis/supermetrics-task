<?php

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$response = (new App\Core\Core)();
http_response_code($response->getStatusCode());
echo $response->getBody()->getContents();
