<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Produtos REST
$routes->get('produtos', 'Produtos::listAll');
$routes->get('produtos/{id}', 'Produtos::listById');
$routes->post('produtos/create', 'Produtos::createProduct');
$routes->put('produtos/update/{id}', 'Produtos::updateById');
$routes->delete('produtos/remove/{id}', 'Produtos::deleteProduct');