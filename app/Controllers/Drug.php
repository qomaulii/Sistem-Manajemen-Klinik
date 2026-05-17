<?php

namespace App\Controllers;

class Drug extends BaseController
{
    public function __construct()
    {
        helper(['url', 'form', 'date']);
    }

    public function index($limit = 15, $page = 1)
    {
        // Perbaikan: Pastikan nama model konsisten dengan file yang ada
        $drugsModel = model('DrugsModel');
        
        // Perbaikan: Gunakan findAll() jika get() tidak didefinisikan di Model
        $data['drugs'] = $drugsModel->findAll(); 
        $data['title'] = 'Drug List';
        $data['navActiveId'] = 'navbarLiDrug';
        $data['page'] = (int)$page;
        $data['per_page'] = (int)$limit;
        $data['pagination'] = ""; 
        
        $path = 'drug/list';
        if ($this->request->getGet('ajax')) {
            return view($path, $data);
        } else {
            $data['includes'] = [$path];
            return view('header', $data) . view('index', $data) . view('footer', $data);
        }
    }

    // Perbaikan: Mengganti nama dari new_drug menjadi add_drug agar link "Purchase Stock" tidak 404
    public function add_drug()
    {
        $data = [];
        // Perbaikan: Gunakan is('post') untuk validasi method yang lebih akurat di CI4
        if ($this->request->is('post')) {
            $rules = [
                'drug_name_en' => 'required',
                'drug_name_fa' => 'required',
                'price'        => 'required|numeric'
            ];
            
            if ($this->validate($rules)) {
                $drugsModel = model('DrugsModel');
                $postData = $this->request->getPost();
                unset($postData['submit']);
                
                // Perbaikan: Langsung simpan menggunakan array postData
                if ($drugsModel->save($postData)) {
                    $data['script'] = '<script>alert("'. esc($postData['drug_name_en']). ' has been registered successfully.");</script>';
                }
            } else {
                $data['error'] = $this->validator->listErrors();
            }
        }
        
        $data['title'] = 'Purchase Stock';
        $path = 'drug/new';
        if ($this->request->getGet('ajax')) {
            return view($path, $data);
        } else {
            $data['includes'] = [$path];
            return view('header', $data) . view('index', $data) . view('footer', $data);
        }
    }

    // Tambahkan method ini agar menu "Return Stock" tidak 404
    public function return_stock()
    {
        $data['title'] = 'Return Stock';
        
        $data['drugs'] = []; // Kirim array kosong agar tidak error "Undefined variable $drugs"
        $data['pagination'] = ""; // Kirim string kosong agar tidak error di baris 5

        // Logika return stock bisa ditambahkan di sini nanti
        $path = 'drug/list'; // Sementara diarahkan ke list
        
        if ($this->request->getGet('ajax')) {
            return view($path, $data);
        } else {
            $data['includes'] = [$path];
            return view('header', $data) . view('index', $data) . view('footer', $data);
        }
    }

    public function edit($drug_id = 0)
    {
        $drugsModel = model('DrugsModel');
        $drug = $drugsModel->find($drug_id);
        
        if (!$drug) return redirect()->to('drug');

        $data = [];
        if ($this->request->is('post')) {
            $rules = [
                'drug_name_en' => 'required',
                'drug_name_fa' => 'required',
                'price'        => 'required|numeric'
            ];
            
            if ($this->validate($rules)) {
                $postData = $this->request->getPost();
                $postData['drug_id'] = $drug_id; // Pastikan ID ada untuk update
                
                if ($drugsModel->save($postData)) {
                    return redirect()->to('drug')->with('success', 'Drug updated');
                }
            } else {
                $data['error'] = $this->validator->listErrors();
            }
        }
        
        $data['title'] = 'Edit Drug';
        $data['drug'] = $drug;
        
        $path = 'drug/edit';
        if ($this->request->getGet('ajax')) {
            return view($path, $data);
        } else {
            $data['includes'] = [$path];
            return view('header', $data) . view('index', $data) . view('footer', $data);
        }
    }

    // Helper untuk list obat (Mengaktifkan logika yang tadinya mati)
    public function _drugs_list()
    {
        $drugsModel = model('DrugsModel');
        $drugs = $drugsModel->findAll();
        $drugs_list = ['' => '-- Select Drug --'];
        
        foreach ($drugs as $drug) {
            // Urutan: Nama Inggris dulu agar mudah dibaca Liya
            $drugs_list[$drug['drug_id']] = esc($drug['drug_name_en'] . ' (Rp ' . number_format($drug['price'], 0, ',', '.') . ')');
        }
        
        return $drugs_list;
    }

    // Perbaikan fungsi delete agar menggunakan standar model CI4
    public function delete($drug_id = 0)
    {
        $drugsModel = model('DrugsModel');
        if ($this->request->is('post')) {
            if ($drugsModel->delete($drug_id)) {
                echo 'ok';
            } else {
                echo 'nok';
            }
            return;
        }
        
        $data['drug'] = $drugsModel->find($drug_id);
        return view('drug/confirm_delete', $data);
    }

    public function _no_access()
    {
        $data['title'] = 'Unauthorized Access';
        $path = 'account/no_access';
        if ($this->request->getGet('ajax')) {
            return view($path, $data);
        } else {
            $data['includes'] = [$path];
            return view('header', $data) . view('index', $data) . view('footer', $data);
        }
    }
}