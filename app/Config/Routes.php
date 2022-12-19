<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');

//register
$routes->post('register', 'Register::index');

//login
$routes->post('login', 'Auth::login');

//logout
$routes->get('logout', 'Auth::logout');

$routes->group('api', ['filter' => 'cors', 'tokenfilter'], static function ($routes) {
    $routes->get('/', 'Home::index');

    //user
    $routes->get('user', 'User::index');
    $routes->get('user/(:segment)', 'User::show/$1');
    
    //user update
    $routes->get('user/edit/(:segment)', 'User::edit/$1', ['filter' => 'adminfilter']);
    $routes->post('user/update/(:segment)', 'User::update/$1', ['filter' => 'adminfilter']);

    //user delete
    $routes->get('user/delete/(:segment)', 'User::delete/$1', ['filter' => 'adminfilter']);

    //excercise
    $routes->get('excercise', 'Excercise::index');
    $routes->get('excercise/(:segment)', 'Excercise::show/$1');

    //excercise show by
    $routes->get('excercise/sort/user/(:num)', 'Excercise::show_by_user/$1');
    $routes->get('excercise/sort/type/(:segment)', 'Excercise::show_by_type/$1');
    $routes->get('excercise/sort/done/(:num)', 'Excercise::show_by_done/$1');
    $routes->get('excercise/sort/do/(:num)', 'Excercise::show_by_not_done/$1');
    $routes->get('excercise/sort/date/(:segment)', 'Excercise::show_by_date/$1');

    //excercise create
    $routes->post('excercise/create', 'Excercise::create');

    //excercise update
    $routes->post('excercise/update/(:segment)', 'Excercise::update/$1');

    //update excerise is done
    $routes->get('excercise/done/(:segment)', 'Excercise::update_is_done/$1');

    //excercise delete
    $routes->get('excercise/delete/(:segment)', 'Excercise::delete/$1');
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
