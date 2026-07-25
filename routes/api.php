<?php
use App\Controllers\Api\ProductApiController;

/** @var \App\Core\Router $router */

$router->group('/api', [], function($router) {
    $router->get('/products', [ProductApiController::class, 'index']);
    $router->get('/products/{id}', [ProductApiController::class, 'show']);
});
