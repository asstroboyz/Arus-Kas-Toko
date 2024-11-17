<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
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
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

$routes->get('/', 'pemilik::index', ['filter' => 'role:pemilik']);
$routes->get('/reset', 'Auth::reset');
$routes->get('/auth/reset_password', 'Auth::reset_password');
$routes->get('/pemilik', 'pemilik::index', ['filter' => 'role:pemilik']);
$routes->post('inventaris/save', 'Inventaris::save', ['filter' => 'role:admin']);
$routes->post('Admin/save', 'admin::save', ['filter' => 'role:admin']);
$routes->get('/Admin', 'Admin::index', ['filter' => 'role:admin']);
$routes->get('/Admin/(:num)', 'Admin::detail/$1', ['filter' => 'role:admin']);
$routes->put('/Admin/detail/(:num)', 'Admin::detailinv/$1', ['filter' => 'role:admin']);
$routes->get('Admin/detail/(:num)', 'Admin::detail/$1', ['filter' => 'role:admin']);

$routes->put('/inventaris/ubah/(:num)', 'Inventaris::ubah/$1', ['filter' => 'role:admin']);
$routes->put('/inventaris/ubah/update/(:num)', 'Inventaris::update/$1', ['filter' => 'role:admin']);

$routes->get('/Admin/formTambahStok/(:num)', 'Admin::formTambahStok/$1', ['filter' => 'role:admin']);
$routes->post('/Admin/formTambahStok/tambahStok/(:num)', 'Admin::tambahStok/$1', ['filter' => 'role:admin']);
$routes->get('/Admin/formKurangStok/(:num)', 'Admin::formKurangStok/$1', ['filter' => 'role:admin']);
$routes->post('/Admin/formKurangStok/kurangiStok/(:num)', 'Admin::kurangiStok/$1', ['filter' => 'role:admin']);
$routes->get('Admin/softDelete/(:segment)', 'Admin::softDelete/$1');

$routes->put('/Admin/detail_inv/(:num)', 'Admin::detail_inv/$1', ['filter' => 'role:admin']);
$routes->put('/pemilik/detail_inv/(:num)', 'pemilik::detail_inv/$1', ['filter' => 'role:pemilik']);



// app/Config/Routes.php


$routes->delete('/pemilik/(:num)', 'pemilik::delete/$1', ['filter' => 'role:pemilik']);
// routes.php
$routes->put('/pemilik/ubah/(:num)', 'pemilik::ubah/$1', ['filter' => 'role:pemilik']);
$routes->post('/pemilik/ubah/update/(:num)', 'pemilik::updatePermin/$1', ['filter' => 'role:pemilik']);
$routes->get('/pemilik/update/(:num)', 'pemilik::ubah/$1', ['filter' => 'role:pemilik']);


$routes->put('/pemilik/profile/(:num)', 'pemilik::profile/$1', ['filter' => 'role:pemilik']);

$routes->put('/pemilik/ubah/simpanProfile/(:num)', 'pemilik::simpanProfile/$1', ['filter' => 'role:pemilik']);

$routes->get('cetak', 'Admin::index', ['filter' => 'role:admin']);
$routes->post('cetak/cetakData', 'Admin::cetakData', ['filter' => 'role:admin']);
$routes->get('admin/cetakDataPdf', 'Admin::cetakDataPdf', ['filter' => 'role:admin']);
$routes->get('admin/cetakDataInventaris', 'Admin::cetakDataInventaris', ['filter' => 'role:admin']);
$routes->get('admin/cetakDataATK', 'Admin::cetakDataATK', ['filter' => 'role:admin']);
$routes->get('admin/cetakDataMasuk', 'Admin::cetakDataMasuk', ['filter' => 'role:admin']);
$routes->get('admin/cetakDataBarang', 'Admin::cetakDataBarang', ['filter' => 'role:admin']);


$routes->get('/administrator', 'administrator::index', ['filter' => 'role:administrator']);


// Perbaiki

// $routes->put('/pemilik/detail/(:num)', 'pemilik::detail/$1', ['filter' => 'role:pemilik']);
// $routes->put('/user/profile/(:num)', 'user::profile/$1', ['filter' => 'role:user']);
// $routes->put('/user/tentang/(:num)', 'user::tentang/$1', ['filter' => 'role:user']);
// $routes->put('/user/ubah/simpanProfile/(:num)', 'user::simpanProfile/$1', ['filter' => 'role:user']);
// $routes->put('/user/cetakdata/(:num)', 'user::cetakdata/$1', ['filter' => 'role:user']);


// $routes->get('/user/pengaduan', 'User::pengaduan', ['filter' => 'role:user']);
// $routes->get('/user/simpanPengaduan', 'User::simpanPengaduan', ['filter' => 'role:user']);
// $routes->put('/user/ubah/(:num)', 'user::ubah/$1', ['filter' => 'role:user']);
// $routes->put('/user/ubah/ubahPengaduan/(:num)', 'user::ubahPengaduan/$1', ['filter' => 'role:user']);
// $routes->get('/admin', 'Admin::index', ['filter' => 'role:admin']);
// $routes->get('/admin/pengaduan', 'Admin::pengaduan', ['filter' => 'role:admin']);
// $routes->put('/admin/detail/(:num)', 'admin::detail/$1', ['filter' => 'role:admin']);
// $routes->put('/admin/simpanBalasan/(:num)', 'admin::simpanBalasan/$1', ['filter' => 'role:admin']);
// $routes->put('/admin/prosesPengaduan/(:num)', 'admin::prosesPengaduan/$1', ['filter' => 'role:admin']);
// $routes->put('/admin/terimaPengaduan/(:num)', 'admin::terimaPengaduan/$1', ['filter' => 'role:admin']);
// $routes->put('/admin/ubah/simpanProfile/(:num)', 'admin::simpanProfile/$1', ['filter' => 'role:admin']);
// $routes->put('/admin/ubah/updatePassword/(:num)', 'admin::updatePassword/$1', ['filter' => 'role:admin']);
// $routes->put('/user/updatePassword/(:num)', 'user::updatePassword/$1', ['filter' => 'role:user']);

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
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
