<?php
require __DIR__ . '/../src/config.php';

use Binemmanuel\ServeMyPhp\{
    Database,
    Request,
    Response,
    Router,
};
use Controller\{
    Home,
    Product
};

use RebaseData\Converter\Converter;
use RebaseData\InputFile\InputFile;


$database = (new Database($_ENV))->mysqli();

$router = new Router($database);

// $router->get('/', [Home::class, 'index']);

$router->get('/products/get/all', [Product::class, 'getAll']);
$router->get('/products/get', [Product::class, 'get']);
$router->post( '/products/add', [Product::class, 'add']);
$router->post('/products/upload/cover', [Product::class, 'addProductImage']);

// addProductImage

$router->get('/',  function (Request $request, Response $response) {
    return $response::sendJson(['message' => 'Dev Test API'])
});

$router->run();
