<?php

namespace App\Controllers;

class Home extends BaseController
{
    /**
     * Tampilan utama dashboard klinik
     */
    public function index()
    {
        $data['title'] = 'Clinic Management System';
        $data['navActiveId'] = 'navbarLiHome';
        
        $data['includes'] = ['home/cp'];
        
        // Di CI4 tidak ada lagi $this->load->view
        // Kita gabungkan view header, isi (index), dan footer
        return view('header', $data)
             . view('index', $data)
             . view('footer', $data);
    }
}