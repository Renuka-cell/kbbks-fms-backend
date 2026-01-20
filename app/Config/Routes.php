<?php

use CodeIgniter\Router\RouteCollection;
use Config\Services;

/**
 * @var RouteCollection $routes
 */

$routes = Services::routes();

/*
|--------------------------------------------------------------------------
| Router Setup
|--------------------------------------------------------------------------
*/
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

/*
|--------------------------------------------------------------------------
| Route Definitions
|--------------------------------------------------------------------------
*/

// Home route (test route)
$routes->get('/', 'Home::index');

// Vendor routes (CRUD)
$routes->group('vendors', function ($routes) {

    // READ: Fetch all vendors
    $routes->get('/', 'VendorController::index');

    // CREATE: Add new vendor
    $routes->post('create', 'VendorController::create');

    // UPDATE: Update vendor by ID
    $routes->post('update/(:num)', 'VendorController::update/$1');

    // DELETE: Delete vendor by ID
    $routes->get('delete/(:num)', 'VendorController::delete/$1');

});
