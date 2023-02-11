<?php
require __DIR__ . '/../src/config.php';

use Binemmanuel\ServeMyPhp\{
    Database,
    Router,
};
use Controller\{
    Home,
    Product
};


$database = (new Database($_ENV))->mysqli();

$router = new Router($database);

$router->get('/', [Home::class, 'index']);

$router->get('/products/get/all', [Product::class, 'getAll']);
$router->get('/products/get', [Product::class, 'get']);

$router->run();
