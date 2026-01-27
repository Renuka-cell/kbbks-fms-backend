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

<<<<<<< HEAD
/*
|--------------------------------------------------------------------------
| AUTH ROUTES (Login / Registration)
|--------------------------------------------------------------------------
*/
$routes->post('auth/login', 'AuthController::login');
$routes->post('auth/register', 'AuthController::register');

/*
|--------------------------------------------------------------------------
| VENDOR ROUTES (CRUD)
|--------------------------------------------------------------------------
*/
=======
// Vendor routes (CRUD)
>>>>>>> 98699a7f9426dba0803b665f6c55731e429988d3
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
<<<<<<< HEAD

/*
|--------------------------------------------------------------------------
| REPORT ROUTES (Reporting APIs)
|--------------------------------------------------------------------------
*/
$routes->group('reports', function ($routes) {

    // Monthly Expense Report
    $routes->get('monthly-expense', 'ReportController::monthlyExpense');

    // Vendor Outstanding Report
    $routes->get('vendor-outstanding', 'ReportController::vendorOutstanding');

    // Income vs Expense Summary
    $routes->get('income-expense', 'ReportController::incomeExpense');

});
=======
>>>>>>> 98699a7f9426dba0803b665f6c55731e429988d3
