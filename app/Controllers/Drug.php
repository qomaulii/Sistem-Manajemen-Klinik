<?php

namespace App\Controllers;

use App\Models\DrugsModel;

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
    private function _check_access()
    {
        $bitauth = new \App\Libraries\Bitauth();

        if (!$bitauth->logged_in()) {
            session()->set('redir', current_url());
            return redirect()->to('account/login');
        }

        return true;
    }

    public function queue()
    {
        $check = $this->_check_access();
        if ($check !== true) return $check;

        $db = \Config\Database::connect();

        $data['queues'] = $db->table('prescriptions p')
            ->select("
                p.visit_id,
                pv.queue_number,
                u.first_name AS patient_first_name,
                u.last_name AS patient_last_name,
                GROUP_CONCAT(
                    CONCAT(si.item_name, ' (', COALESCE(vi.qty, 1), ')')
                    SEPARATOR ', '
                ) AS medicine_list,
                SUM(CASE WHEN p.status = 'Pending' THEN 1 ELSE 0 END) AS pending_total
            ")
            ->join('patient_visits pv', 'pv.visit_id = p.visit_id', 'left')
            ->join('userdata u', 'u.user_id = p.patient_id', 'left')
            ->join('service_items si', 'si.item_id = p.drug_id', 'left')
            ->join('visit_items vi', 'vi.visit_id = p.visit_id AND vi.item_id = p.drug_id AND vi.item_type = "OBAT"', 'left')
            ->where('p.visit_id >', 0)
            ->groupBy('p.visit_id')
            ->orderBy('p.visit_id', 'DESC')
            ->get()
            ->getResult();

        $data['title'] = 'Daftar Antrean Pasien';
        $data['includes'] = ['drug/queue'];

        return view('header', $data)
            . view('index', $data)
            . view('footer', $data);
    }

    public function update_prescription_status($visit_id)
    {
        $check = $this->_check_access();
        if ($check !== true) return $check;

        $statusInput = $this->request->getPost('status');

        if ($statusInput === 'Menunggu') {
            $dbStatus = 'Pending';
        } elseif ($statusInput === 'Selesai') {
            $dbStatus = 'Diserahkan';
        } else {
            return redirect()->to('drug/queue')->with('error', 'Status tidak valid.');
        }

        $db = \Config\Database::connect();

        $db->table('prescriptions')
            ->where('visit_id', $visit_id)
            ->update([
                'status' => $dbStatus
            ]);

        return redirect()->to('drug/queue')->with('message', 'Status obat pasien berhasil diubah.');
    }

    public function transactions()
    {
        $check = $this->_check_access();
        if ($check !== true) return $check;

        $db = \Config\Database::connect();

        $data['transactions'] = $db->table('prescriptions p')
            ->select("
                p.visit_id,
                pv.queue_number,
                pasien.first_name AS patient_first_name,
                pasien.last_name AS patient_last_name,
                dokter.first_name AS doctor_first_name,
                dokter.last_name AS doctor_last_name,
                GROUP_CONCAT(
                    CONCAT(si.item_name, ' x', COALESCE(vi.qty, 1))
                    SEPARATOR ', '
                ) AS medicine_list,
                SUM(COALESCE(vi.subtotal, si.price * COALESCE(vi.qty, 1))) AS total_obat,
                MAX(p.created_at) AS tanggal
            ")
            ->join('patient_visits pv', 'pv.visit_id = p.visit_id', 'left')
            ->join('userdata pasien', 'pasien.user_id = p.patient_id', 'left')
            ->join('userdata dokter', 'dokter.user_id = p.doctor_id', 'left')
            ->join('service_items si', 'si.item_id = p.drug_id', 'left')
            ->join('visit_items vi', 'vi.visit_id = p.visit_id AND vi.item_id = p.drug_id AND vi.item_type = "OBAT"', 'left')
            ->where('p.visit_id >', 0)
            ->groupBy('p.visit_id')
            ->orderBy('p.visit_id', 'DESC')
            ->get()
            ->getResult();

        $data['title'] = 'Transaksi Pembelian Obat';
        $data['includes'] = ['drug/transactions'];

        return view('header', $data)
            . view('index', $data)
            . view('footer', $data);
    }

    public function stock()
    {
        $check = $this->_check_access();
        if ($check !== true) return $check;

        $db = \Config\Database::connect();

        $data['drugs'] = $db->table('service_items')
            ->where('item_type', 'OBAT')
            ->where('is_active', 1)
            ->orderBy('item_name', 'ASC')
            ->get()
            ->getResult();

        $data['title'] = 'Melihat Stok Obat';
        $data['includes'] = ['drug/stock'];

        return view('header', $data)
            . view('index', $data)
            . view('footer', $data);
    }

    public function delete_stock($item_id)
    {
        $check = $this->_check_access();
        if ($check !== true) return $check;

        $db = \Config\Database::connect();

        $db->table('service_items')
            ->where('item_id', $item_id)
            ->where('item_type', 'OBAT')
            ->update([
                'is_active' => 0
            ]);

        return redirect()->to('drug/stock')->with('message', 'Obat berhasil dihapus dari daftar stok.');
    }

    public function add_stock()
    {
        $check = $this->_check_access();
        if ($check !== true) return $check;

        $db = \Config\Database::connect();

        if ($this->request->is('post')) {
            $namaObat = trim($this->request->getPost('item_name'));
            $harga    = (int) $this->request->getPost('price');
            $stok     = (int) $this->request->getPost('stock');

            if ($namaObat === '' || $harga <= 0 || $stok <= 0) {
                return redirect()->to('drug/add_stock')
                    ->with('error', 'Nama obat, harga, dan jumlah stok wajib diisi dengan benar.');
            }

            $existing = $db->table('service_items')
                ->where('item_type', 'OBAT')
                ->where('LOWER(item_name)', strtolower($namaObat))
                ->get()
                ->getRow();

            if ($existing) {
                $db->table('service_items')
                    ->where('item_id', $existing->item_id)
                    ->update([
                        'price'     => $harga,
                        'stock'     => ((int) $existing->stock) + $stok,
                        'is_active' => 1
                    ]);

                return redirect()->to('drug/stock')
                    ->with('message', 'Nama obat sudah ada. Stok obat berhasil ditambahkan.');
            }

            $db->table('service_items')->insert([
                'item_type'  => 'OBAT',
                'item_name'  => $namaObat,
                'price'      => $harga,
                'stock'      => $stok,
                'is_active'  => 1,
                'created_at' => time()
            ]);

            return redirect()->to('drug/stock')
                ->with('message', 'Obat baru berhasil ditambahkan.');
        }

        $data['drugOptions'] = $db->table('service_items')
            ->where('item_type', 'OBAT')
            ->where('is_active', 1)
            ->orderBy('item_name', 'ASC')
            ->get()
            ->getResult();

        $data['title'] = 'Menambah Obat Baru';
        $data['includes'] = ['drug/add_stock'];

        return view('header', $data)
            . view('index', $data)
            . view('footer', $data);
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