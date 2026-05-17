<?php

namespace App\Controllers;

class Billing extends BaseController
{
    protected $bitauth;

    public function __construct()
    {
        // Panggil helper
        helper(['url', 'form', 'date']);
        
        // Panggil library auth
        $this->bitauth = new \App\Libraries\Bitauth();
    }

    public function index()
    {
        // Proteksi halaman: Cek apakah user sudah login
        if (!$this->bitauth->logged_in()) {
            session()->set('redir', current_url());
            return redirect()->to('account/login');
        }

        $data = [];
        $data['title'] = 'Pembayaran & Tagihan';
        
        // Path ke file View yang akan dibuat nanti
        $path = 'billing/list';

        // Logika render view sesuai standar template-mu
        if ($this->request->getGet('ajax')) {
            return view($path, $data);
        } else {
            $data['includes'] = [$path];
            return view('header', $data) . view('index', $data) . view('footer', $data);
        }
    }
    public function create()
    {
        // Proteksi halaman
        if (!$this->bitauth->logged_in()) {
            session()->set('redir', current_url());
            return redirect()->to('account/login');
        }

        $data = [];
        $data['title'] = 'Buat Tagihan Baru';
        
        // Mengarah ke file form baru
        $path = 'billing/new';

        if ($this->request->getGet('ajax')) {
            return view($path, $data);
        } else {
            $data['includes'] = [$path];
            return view('header', $data) . view('index', $data) . view('footer', $data);
        }
    }

    public function save()
    {
        // Pastikan hanya user login yang bisa simpan data
        if (!$this->bitauth->logged_in()) {
            return redirect()->to('account/login');
        }

        // Panggil model
        $billingModel = new \App\Models\BillingModel();

        // Ambil data dari inputan form
        $data = [
            'patient_id'      => $this->request->getPost('patient_id'),
            // Ambil ID user yang sedang login dari session Bitauth
            'user_id'         => session()->get('ba_user_id'), 
            'service_details' => $this->request->getPost('service'),
            'total_amount'    => $this->request->getPost('amount'),
            'payment_method'  => $this->request->getPost('payment_method'),
            'payment_status'  => 'Unpaid', // Default status tagihan baru
            'create_date'     => time()    // Format waktu Unix Timestamp
        ];

        // Simpan ke database
        if ($billingModel->insert($data)) {
            // Jika berhasil, kembali ke halaman daftar tagihan dengan pesan sukses
            return redirect()->to('billing')->with('success', 'Tagihan baru berhasil dibuat!');
        } else {
            // Jika gagal, kembali dengan pesan error
            return redirect()->back()->with('error', 'Gagal menyimpan tagihan.');
        }
    }
}