<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $data['title'] = 'Clinic Management System';
        $data['navActiveId'] = 'navbarLiHome';

        // Dikosongkan supaya dashboard kotak-kotak lama tidak muncul lagi
        // karena semua menu sudah ada di sidebar masing-masing role.
        $data['includes'] = [];

        return view('header', $data)
             . view('index', $data)
             . view('footer', $data);
    }
}