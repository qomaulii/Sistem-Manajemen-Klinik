<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

/*
|--------------------------------------------------------------------------
| Konfigurasi Dasar Routes
|--------------------------------------------------------------------------
*/
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);


/*
|--------------------------------------------------------------------------
| Route Utama
|--------------------------------------------------------------------------
*/
$routes->get('/', 'Home::index');


/*
|--------------------------------------------------------------------------
| Route Pasien
|--------------------------------------------------------------------------
*/
$routes->get('patient/register', 'Receptionist::register_patient');
$routes->get('patient/history', 'Patient::history');


/*
|--------------------------------------------------------------------------
| Route Resepsionis
|--------------------------------------------------------------------------
| Menu:
| - Daftar Pasien-Dokter
| - Pendaftaran Pasien
| - Antrean & Status
| - Tagihan Pembayaran
|--------------------------------------------------------------------------
*/

// Daftar Pasien-Dokter
$routes->get('receptionist/patient_doctor_list', 'Receptionist::patient_doctor_list');

// Pendaftaran Pasien
$routes->post('receptionist/save_registration', 'Receptionist::save_registration');

// Antrean & Status
$routes->get('patient/waiting', 'Receptionist::queue_list');
$routes->post('receptionist/update_status/(:num)', 'Receptionist::update_status/$1');
$routes->post('receptionist/update_payment_status/(:num)', 'Receptionist::update_payment_status/$1');

// Tagihan Pembayaran
$routes->get('billing', 'Billing::index');
$routes->get('billing/create', 'Billing::create');
$routes->post('billing/save', 'Billing::save');
$routes->get('billing/receipt/(:num)', 'Billing::receipt/$1');
$routes->get('billing/print_receipt/(:num)', 'Billing::print_receipt/$1');


/*
|--------------------------------------------------------------------------
| Route Dokter
|--------------------------------------------------------------------------
| Menu:
| - Membuat Resep Obat
| - Menjadwalkan Tes Lab
| - Menjadwalkan X-Ray
| - Riwayat Medis Pasien
| - Tambah Catatan Medis
|--------------------------------------------------------------------------
*/

// Resep Obat
$routes->get('doctor/prescription', 'Doctor::prescription');
$routes->post('doctor/save_prescription', 'Doctor::save_prescription');

// Tes Laboratorium
$routes->get('doctor/lab_schedule', 'Doctor::lab_schedule');
$routes->get('doctor/lab_request', 'Doctor::lab_request');
$routes->post('doctor/save_lab_request', 'Doctor::save_lab_request');

// X-Ray / Radiologi
$routes->get('doctor/xray_schedule', 'Doctor::xray_schedule');
$routes->get('doctor/xray_request', 'Doctor::xray_request');
$routes->post('doctor/save_xray_request', 'Doctor::save_xray_request');

// Riwayat Medis dan Catatan Medis
$routes->get('doctor/medical_history_detail/(:num)', 'Doctor::medical_history_detail/$1');
$routes->match(['get', 'post'], 'doctor/add_medical_note/(:num)', 'Doctor::add_medical_note/$1');


/*
|--------------------------------------------------------------------------
| Route Apoteker
|--------------------------------------------------------------------------
| Menu:
| - Daftar Antrean Pasien
| - Transaksi Pembelian Obat
| - Melihat Stok Obat
| - Menambah Obat Baru
|--------------------------------------------------------------------------
*/

// Daftar Antrean Pasien Resep Obat
$routes->get('drug/queue', 'Drug::queue');
$routes->post('drug/update_prescription_status/(:num)', 'Drug::update_prescription_status/$1');

// Transaksi Pembelian Obat
$routes->get('drug/transactions', 'Drug::transactions');

// Stok Obat
$routes->get('drug/stock', 'Drug::stock');
$routes->post('drug/delete_stock/(:num)', 'Drug::delete_stock/$1');

// Tambah Obat / Tambah Stok
$routes->match(['get', 'post'], 'drug/add_stock', 'Drug::add_stock');

// Route lama yang masih dipakai
$routes->match(['get', 'post'], 'drug/new_drug', 'Drug::add_drug');
$routes->match(['get', 'post'], 'drug/return_drug', 'Drug::return_stock');


/*
|--------------------------------------------------------------------------
| Route Analis Laboratorium
|--------------------------------------------------------------------------
*/

$routes->get('test/queue', 'Test::queue');
$routes->get('test/results', 'Test::results');
$routes->get('test/input_result', 'Test::input_result');
$routes->match(['get', 'post'], 'test/input_result/(:num)', 'Test::input_result/$1');
$routes->match(['get', 'post'], 'test/edit_result/(:num)', 'Test::edit_result/$1');
$routes->post('test/delete_result/(:num)', 'Test::delete_result/$1');


/*
|--------------------------------------------------------------------------
| Route Radiografer / X-Ray
|--------------------------------------------------------------------------
*/

$routes->get('xray/queue', 'Xray::queue');
$routes->get('xray/results', 'Xray::results');
$routes->get('xray/input_result', 'Xray::input_result');
$routes->match(['get', 'post'], 'xray/input_result/(:num)', 'Xray::input_result/$1');
$routes->match(['get', 'post'], 'xray/edit_result/(:num)', 'Xray::edit_result/$1');
$routes->post('xray/delete_result/(:num)', 'Xray::delete_result/$1');