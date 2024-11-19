<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

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
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Gudang::index');
$routes->get('/gudang/lplpo', 'Gudang::lplpo');
$routes->get('/gudang/Perbandingan', 'Gudang::Perbadingan_prediksi');
// $routes->post('/login/proses_login', 'login::proses_login');

// //Admin
// $routes->get('admin', 'Admin::index', ['filter' => 'login:admin']);
// $routes->get('/Admin/logout', 'Admin::logout');

// //Farmasi dalam Admin
// $routes->get('/admin/farmasi', 'Admin::farmasi', ['filter' => 'login:admin']);

// //Gudang dalam Admin
// $routes->get('/admin/gudang', 'Admin::gudang', ['filter' => 'login:admin']);

// //Pendaftaran dalam Admin
// $routes->get('/admin/pendaftaran', 'Admin::pendaftaran', ['filter' => 'login:admin']);
// $routes->get('/admin/pendaftaran/chart', 'Admin::chart', ['filter' => 'login:admin']);



// //Gudang
// $routes->get('gudang', 'Gudang::index', ['filter' => 'login:gudang']);
// $routes->get('/Gudang/logout', 'gudang::logout');

// //Farmasi
// $routes->get('farmasi', 'Farmasi::index', ['filter' => 'login:farmasi']);
// $routes->get('/Farmasi/logout', 'farmasi::logout');

// //Pendaftaran
// $routes->get('pendaftaran', 'Pendaftaran::index', ['filter' => 'login:pendaftaran']);
// $routes->get('pendaftaran/chart', 'Pendaftaran::chart', ['filter' => 'login:pendaftaran']);
// $routes->get('/Pendaftaran/logout', 'pendaftaran::logout');


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
