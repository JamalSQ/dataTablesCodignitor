<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/getAllProducts', 'Home::getAllProducts');
$routes->get('/getsingleproduct', 'Home::getsingleproduct');
$routes->get('/updateProducts', 'Home::updateProducts');
$routes->get('/deleteSingleProduct', 'Home::deletesingleProduct');
$routes->get('/insertProducts', 'Home::insertProducts');
$routes->get('/login', 'Home::');