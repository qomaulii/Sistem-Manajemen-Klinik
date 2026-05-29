<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

// --- RUTE UTAMA ---
$routes->get('/', 'Home::index');

// --- RUTE RESEPSIONIS (Gunakan ini agar konsisten) ---

// 1. Daftar Pasien-Dokter
$routes->get('receptionist/patient_doctor_list', 'Receptionist::patient_doctor_list');

// 2. Pendaftaran Pasien
$routes->get('patient/register', 'Receptionist::register_patient');
$routes->post('receptionist/save_registration', 'Receptionist::save_registration');

$routes->get('doctor/prescription', 'Doctor::prescription');
$routes->post('doctor/save_prescription', 'Doctor::save_prescription');

$routes->get('doctor/lab_schedule', 'Doctor::lab_schedule');
$routes->get('doctor/lab_request', 'Doctor::lab_request');
$routes->post('doctor/save_lab_request', 'Doctor::save_lab_request');

$routes->get('doctor/xray_schedule', 'Doctor::xray_schedule');
$routes->get('doctor/xray_request', 'Doctor::xray_request');
$routes->post('doctor/save_xray_request', 'Doctor::save_xray_request');

// 3. Antrean & Status
$routes->get('patient/waiting', 'Receptionist::queue_list');
$routes->post('receptionist/update_status/(:num)', 'Receptionist::update_status/$1');
$routes->post('receptionist/update_payment_status/(:num)', 'Receptionist::update_payment_status/$1');

$routes->get('drug/queue', 'Drug::queue');
$routes->post('drug/update_prescription_status/(:num)', 'Drug::update_prescription_status/$1');

$routes->get('drug/transactions', 'Drug::transactions');

$routes->get('drug/stock', 'Drug::stock');
$routes->post('drug/delete_stock/(:num)', 'Drug::delete_stock/$1');

$routes->match(['get', 'post'], 'drug/add_stock', 'Drug::add_stock');

$routes->get('test/queue', 'Test::queue');
$routes->get('test/results', 'Test::results');
$routes->match(['get', 'post'], 'test/input_result/(:num)', 'Test::input_result/$1');
$routes->match(['get', 'post'], 'test/edit_result/(:num)', 'Test::edit_result/$1');
$routes->post('test/delete_result/(:num)', 'Test::delete_result/$1');

$routes->get('xray/queue', 'Xray::queue');
$routes->get('xray/results', 'Xray::results');
$routes->match(['get', 'post'], 'xray/input_result/(:num)', 'Xray::input_result/$1');
$routes->match(['get', 'post'], 'xray/edit_result/(:num)', 'Xray::edit_result/$1');
$routes->post('xray/delete_result/(:num)', 'Xray::delete_result/$1');

// --- RUTE LAIN (Penting: Pastikan tidak bentrok) ---
// Jika ada rute lama di bawah ini, pastikan tidak mengarah ke URL yang sama
$routes->match(['get', 'post'], 'drug/new_drug', 'Drug::add_drug');
$routes->match(['get', 'post'], 'drug/return_drug', 'Drug::return_stock');
$routes->match(['get', 'post'], 'doctor/add_medical_note/(:num)', 'Doctor::add_medical_note/$1');