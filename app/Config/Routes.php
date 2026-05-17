<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Pengaturan default
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

// Buka otomatis semua routing ala CI3 (sangat membantu untuk migrasi)
$routes->setAutoRoute(true);

// Rute halaman utama
$routes->get('/', 'Home::index');

// --- TAMBAHKAN KODE INI DI BAWAHNYA ---
$routes->get('patient', 'Patient::index');
$routes->get('patient/register', 'Patient::register');
$routes->get('patient/list', 'Patient::index');
$routes->get('patient/waiting', 'Patient::waiting');

// Catatan: Jika form di halaman register melakukan "submit" data, 
// pastikan kamu juga menambahkan rute POST-nya agar tidak error saat tombol diklik:
$routes->post('patient/register', 'Patient::register');
$routes->match(['get', 'post'], 'drug/new_drug', 'Drug::add_drug');
$routes->match(['get', 'post'], 'drug/return_drug', 'Drug::return_stock');