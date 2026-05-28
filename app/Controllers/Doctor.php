<?php

namespace App\Controllers;

class Doctor extends BaseController
{
    protected $bitauth;

    public function __construct()
    {
        helper(['url', 'form', 'date']);
        $this->bitauth = new \App\Libraries\Bitauth();
    }

    /**
     * Memastikan hanya Dokter (atau Admin) yang bisa mengakses Controller ini
     */
    private function _check_access()
    {
        if (!$this->bitauth->logged_in()) {
            session()->set('redir', current_url());
            return redirect()->to('account/login');
        }
        
        // Memastikan grupnya adalah Dokter (ID = 3) atau Admin (ID = 1)
        if (!$this->bitauth->has_role('doctor') && !$this->bitauth->is_admin()) {
            return redirect()->to('account/no_access');
        }
    }

    // 1. Menu: Lihat Daftar Antrean Pasien
    // 1. Menu: Lihat Daftar Antrean Pasien
    public function queue()
    {
        $this->_check_access();
        $db = \Config\Database::connect();
        $doctorId = session()->get('ba_user_id');

        // Query Join untuk mengambil nama pasien dari tabel userdata
        $queues = $db->table('patient_visits pv')
                    ->select('pv.*, u.first_name, u.last_name')
                    ->join('userdata u', 'u.user_id = pv.patient_id')
                    ->where('pv.doctor_id', $doctorId)
                    ->where('pv.register_time >=', strtotime('today midnight'))
                    ->orderBy('pv.queue_number', 'ASC')
                    ->get()->getResult();

        $data['queues'] = $queues;
        $data['title'] = 'Daftar Antrean Pasien';
        $data['includes'] = ['doctor/queue'];
        return view('header', $data) . view('index', $data) . view('footer', $data);
    }

    // Fungsi Khusus: Memproses Pemeriksaan Pasien di Ruangan
    public function examine($patient_id = 0)
    {
        $this->_check_access();
        $db = \Config\Database::connect();
        $doctorId = session()->get('ba_user_id');

        // 1. Validasi keberadaan pasien
        $patient = $db->table('userdata')->where('user_id', $patient_id)->get()->getRow();
        if (!$patient) {
            return redirect()->to('doctor/queue')->with('error', 'Data pasien tidak ditemukan.');
        }

        // 2. Mencari data antrean aktif hari ini untuk pasien tersebut
        $todayStart = strtotime('today midnight');
        $todayEnd   = strtotime('tomorrow') - 1;
        $visit = $db->table('patient_visits')
                    ->where('patient_id', $patient_id)
                    ->where('doctor_id', $doctorId)
                    ->where('register_time >=', $todayStart)
                    ->where('register_time <=', $todayEnd)
                    ->where('status !=', 'Selesai')
                    ->get()->getRow();

        if (!$visit) {
            return redirect()->to('doctor/queue')->with('error', 'Pasien ini tidak memiliki jadwal antrean aktif yang perlu diperiksa hari ini.');
        }

        // 3. Ubah status otomatis menjadi "Diperiksa" jika pasien baru masuk
        if ($visit->status == 'Menunggu') {
            $db->table('patient_visits')->where('visit_id', $visit->visit_id)->update(['status' => 'Diperiksa']);
            $visit->status = 'Diperiksa';
        }

        // 4. Proses Penyimpanan Rekam Medis Baru
        if (strtolower($this->request->getMethod()) === 'post') {
            $rules = [
                'symptoms'  => 'required',
                'diagnosis' => 'required'
            ];

            if ($this->validate($rules)) {
                $postData = $this->request->getPost();
                
                // Gunakan transaksi database untuk keamanan data ganda
                $db->transStart();
                
                $db->table('medical_records')->insert([
                    'visit_id'       => $visit->visit_id,
                    'patient_id'     => $patient_id,
                    'doctor_id'      => $doctorId,
                    'symptoms'       => $postData['symptoms'],
                    'diagnosis'      => $postData['diagnosis'],
                    'medical_action' => $postData['medical_action'] ?? '',
                    'created_at'     => time()
                ]);

                // Kunci siklus kunjungan: Ubah status menjadi Selesai
                $db->table('patient_visits')->where('visit_id', $visit->visit_id)->update(['status' => 'Selesai']);

                $db->transComplete();

                if ($db->transStatus() === false) {
                    return redirect()->back()->withInput()->with('error', 'Sistem gagal menyimpan rekam medis. Silakan coba lagi.');
                }

                return redirect()->to('doctor/queue')->with('success', 'Pemeriksaan selesai. Rekam medis berhasil dicatat ke dalam sistem.');
            } else {
                $data['error'] = $this->validator->listErrors();
            }
        }

        // 5. Menarik data Riwayat Medis sebelumnya (Referensi Dokter)
        $history = $db->table('medical_records')
                      ->select('medical_records.*, userdata.first_name, userdata.last_name')
                      ->join('userdata', 'userdata.user_id = medical_records.doctor_id', 'left')
                      ->where('medical_records.patient_id', $patient_id)
                      ->orderBy('medical_records.created_at', 'DESC')
                      ->get()->getResult();

        $data['title']   = 'Pemeriksaan: ' . trim($patient->first_name . ' ' . $patient->last_name);
        $data['patient'] = $patient;
        $data['visit']   = $visit;
        $data['history'] = $history;
        $data['navActiveId'] = 'navbarLiDoctor';

        $path = 'doctor/examine';
        if ($this->request->getGet('ajax')) {
            return view($path, $data);
        } else {
            $data['includes'] = [$path];
            return view('header', $data) . view('index', $data) . view('footer', $data);
        }
    }
        // Berkas: app/Controllers/Doctor.php

    // Fungsi untuk menampilkan daftar pasien untuk dipilih (Menu Utama)
    public function medical_history()
    {
        $this->_check_access();
        $db = \Config\Database::connect();
        
        // Ambil data pasien (Urut A-Z)
        $data['patients'] = $db->table('userdata')
                            ->where('position', 'Pasien')
                            ->orderBy('first_name', 'ASC')
                            ->get()->getResult();
                            
        $data['title'] = 'Daftar Pasien';
        $data['includes'] = ['doctor/medical_history']; 
        return view('header', $data) . view('index', $data) . view('footer', $data);
    }

    public function medical_history_detail($patient_id)
    {
        $this->_check_access();
        $db = \Config\Database::connect();
        
        // Ambil data pasien
        $data['patient'] = $db->table('userdata')->where('user_id', $patient_id)->get()->getRow();
        
        // Ambil riwayat
        $data['history'] = $db->table('medical_records mr')
                            ->select('mr.*, ud.first_name as doc_first, ud.last_name as doc_last')
                            ->join('userdata ud', 'mr.doctor_id = ud.user_id', 'left')
                            ->where('mr.patient_id', $patient_id)
                            ->orderBy('mr.created_at', 'DESC')
                            ->get()->getResult();
        
        $data['title'] = 'Detail Riwayat Medis';
        $data['includes'] = ['doctor/medical_history_detail'];
        return view('header', $data) . view('index', $data) . view('footer', $data);
    }
    // 3. Menu: Membuat Resep Obat
    public function prescription()
    {
        $this->_check_access();
        $db = \Config\Database::connect();
        
        // Ambil daftar pasien untuk dropdown
        $data['patients'] = $db->table('userdata')
                            ->where('position', 'Pasien')
                            ->get()->getResult();
        
        $data['title'] = 'Buat Resep Obat';
        $data['includes'] = ['doctor/prescription']; // Pastikan nama folder/file sesuai
        return view('header', $data) . view('index', $data) . view('footer', $data);
    }

    public function save_prescription() 
    {
        $db = \Config\Database::connect();
        $data = [
            'patient_id' => $this->request->getPost('patient_id'),
            'doctor_id'  => session()->get('ba_user_id'),
            'status'     => 'Pending',
            'created_at' => time()
        ];
        
        // Simpan ke database
        $db->table('prescriptions')->insert($data);
        
        // Mengarahkan kembali ke halaman prescription dan mengirim notifikasi sukses
        return redirect()->to('doctor/prescription')
                        ->with('message', 'Resep berhasil dikirim ke Apotek!');
    }

    // Simpan Lab (Terbaca oleh Analis Lab)
    public function save_lab_request()
    {
        $db = \Config\Database::connect();
        $data = [
            'patient_id'   => $this->request->getPost('patient_id'),
            'doctor_id'    => session()->get('ba_user_id'),
            // Kita simpan detail tes di notes jika tidak ada kolom khusus nama tes
            'doctor_notes' => 'Tes: ' . $this->request->getPost('test_name') . ' | Catatan: ' . $this->request->getPost('doctor_notes'),
            'status'       => 'Pending', // Analis Lab memfilter status 'Pending'
            'created_at'   => time()
        ];
        
        $db->table('lab_requests')->insert($data);
        
        return redirect()->to('doctor/lab_request')
                        ->with('message', 'Permintaan Lab berhasil dijadwalkan dan dikirim ke Analis Lab!');
    }

    // 4. Menu: Menjadwalkan Tes Lab
    public function lab_schedule()
    {
        $this->_check_access();
        $db = \Config\Database::connect();
        
        // Ambil daftar pasien untuk dropdown
        $data['patients'] = $db->table('userdata')
                            ->where('position', 'Pasien')
                            ->get()->getResult();
        
        $data['title'] = 'Jadwalkan Tes Lab';
        $data['includes'] = ['doctor/lab_schedule']; // Harus sama dengan nama file
        return view('header', $data) . view('index', $data) . view('footer', $data);
    }
    
    public function lab_request()
    {
        $this->_check_access();
        $db = \Config\Database::connect();
        
        // Ambil daftar pasien untuk dropdown
        $data['patients'] = $db->table('userdata')
                            ->where('position', 'Pasien')
                            ->get()->getResult();
        
        $data['title'] = 'Jadwalkan Tes Lab';
        
        // Pastikan ini menunjuk ke file view yang sudah Anda buat
        $data['includes'] = ['doctor/lab_schedule']; 
        
        return view('header', $data) . view('index', $data) . view('footer', $data);
    }

    // 5. Menu: Menjadwalkan X-Ray
    public function xray_schedule()
    {
        $this->_check_access();
        $db = \Config\Database::connect();
        
        // Ambil data pasien
        $data['patients'] = $db->table('userdata')
                            ->where('position', 'Pasien')
                            ->get()->getResult();
        
        $data['title'] = 'Jadwalkan X-Ray';
        $data['includes'] = ['doctor/xray_schedule'];
        
        return view('header', $data) . view('index', $data) . view('footer', $data);
    }
    
    public function xray_request()
    {
        $this->_check_access();
        $db = \Config\Database::connect();
        
        $data['patients'] = $db->table('userdata')->where('position', 'Pasien')->get()->getResult();
        $data['title'] = 'Jadwalkan X-Ray';
        $data['includes'] = ['doctor/xray_schedule'];
        
        return view('header', $data) . view('index', $data) . view('footer', $data);
    }

    public function save_xray_request()
    {
        $db = \Config\Database::connect();
        $patient_id = $this->request->getPost('patient_id');

        // 1. Cari visit_id terakhir pasien ini agar tidak NULL
        $last_visit = $db->table('patient_visits')
                        ->where('patient_id', $patient_id)
                        ->orderBy('visit_id', 'DESC')
                        ->get()->getRow();

        // Jika tidak ada kunjungan, gunakan 0 atau beri pesan error
        $visit_id = $last_visit ? $last_visit->visit_id : 0;

        // 2. Simpan dengan visit_id yang sudah didapat
        $data = [
            'visit_id'     => $visit_id, 
            'patient_id'   => $patient_id,
            'doctor_id'    => session()->get('ba_user_id'),
            'xray_id'      => 1, // Pastikan ID 1 ada di tabel xrays
            'doctor_notes' => 'Bagian: ' . $this->request->getPost('body_part') . ' | Catatan: ' . $this->request->getPost('doctor_notes'),
            'status'       => 'Pending',
            'created_at'   => time()
        ];

        if ($db->table('xray_requests')->insert($data)) {
            return redirect()->to('doctor/xray_request')
                            ->with('message', 'Permintaan X-Ray berhasil dikirim!');
        } else {
            return redirect()->to('doctor/xray_request')
                            ->with('message', 'Gagal menyimpan ke database.');
        }
}
}