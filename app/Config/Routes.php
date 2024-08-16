<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/getAllProducts', 'Home::getAllProducts');
$routes->post('/insertProducts', 'Home::insertData');
$routes->post('/getsingleproduct', 'Home::getsingleproduct');
$routes->post('/updateProducts', 'Home::updateProducts');
$routes->post('/deleteSingleProduct', 'Home::deletesingleProduct');
$routes->get('/login', 'Home::');

