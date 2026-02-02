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
| Basic Routes
|--------------------------------------------------------------------------
*/
$routes->get('/', 'Home::index');

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
$routes->group('auth', function ($routes) {

    $routes->post('login', 'AuthController::login');
    $routes->post('register', 'AuthController::register');

});

/*
|--------------------------------------------------------------------------
| VENDOR ROUTES
|--------------------------------------------------------------------------
*/
$routes->group('vendors', function ($routes) {

    $routes->get('/', 'VendorController::index');          // List vendors
    $routes->post('create', 'VendorController::create');  // Create vendor
    $routes->post('update/(:num)', 'VendorController::update/$1');
    $routes->get('delete/(:num)', 'VendorController::delete/$1');

});

/*
|--------------------------------------------------------------------------
| BILL ROUTES
|--------------------------------------------------------------------------
*/
$routes->group('bills', function ($routes) {

    $routes->post('create', 'BillController::create');    // Create bill
    // $routes->get('/', 'BillController::index');        // (Optional) list bills

});

/*
|--------------------------------------------------------------------------
| EXPENSE ROUTES
|--------------------------------------------------------------------------
*/
$routes->group('expenses', function ($routes) {

    $routes->post('create', 'ExpenseController::create'); // Add expense
    // $routes->get('/', 'ExpenseController::index');     // (Optional) list expenses

});

/*
|--------------------------------------------------------------------------
| PAYMENT ROUTES
|--------------------------------------------------------------------------
*/
$routes->group('payments', function ($routes) {

    $routes->post('create', 'PaymentController::create'); // Add payment
    // $routes->get('/', 'PaymentController::index');     // (Optional) list payments

});

/*
|--------------------------------------------------------------------------
| REPORT ROUTES
|--------------------------------------------------------------------------
*/
$routes->group('reports', function ($routes) {

    // ✅ Vendor Outstanding Report
    $routes->get('vendor-outstanding', 'ReportController::vendorOutstanding');

    // Other reports
    $routes->get('monthly-expense', 'ReportController::monthlyExpense');
    $routes->get('income-expense', 'ReportController::incomeExpense');

});
