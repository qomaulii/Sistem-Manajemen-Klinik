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

        $patient = $db->table('userdata')
            ->where('user_id', $patient_id)
            ->get()
            ->getRow();

        if (!$patient) {
            return redirect()->to('doctor/queue')
                ->with('error', 'Data pasien tidak ditemukan.');
        }

        $todayStart = strtotime('today midnight');
        $todayEnd   = strtotime('tomorrow midnight') - 1;

        $visit = $db->table('patient_visits')
            ->where('patient_id', $patient_id)
            ->where('doctor_id', $doctorId)
            ->where('register_time >=', $todayStart)
            ->where('register_time <=', $todayEnd)
            ->where('status !=', 'Batal')
            ->where('status !=', 'Selesai')
            ->get()
            ->getRow();

        if (!$visit) {
            return redirect()->to('doctor/queue')
                ->with('error', 'Pasien ini tidak memiliki antrean aktif hari ini.');
        }

        // Ambil semua master biaya/tindakan dari service_items
        $items = $db->table('service_items')
            ->where('is_active', 1)
            ->orderBy('item_type', 'ASC')
            ->orderBy('item_name', 'ASC')
            ->get()
            ->getResult();

        $groupedItems = [
            'PEMERIKSAAN' => [],
            'OBAT'        => [],
            'LAB'         => [],
            'XRAY'        => [],
        ];

        foreach ($items as $item) {
            $groupedItems[$item->item_type][] = $item;
        }

        if (strtolower($this->request->getMethod()) === 'post') {
            $rules = [
                'symptoms'  => 'required',
                'diagnosis' => 'required',
            ];

            if (!$this->validate($rules)) {
                $data['error'] = $this->validator->listErrors();
            } else {
                $postData = $this->request->getPost();
                $selectedItems = $this->request->getPost('item_ids') ?? [];

                $db->transStart();

                // Simpan rekam medis utama
                $db->table('medical_records')->insert([
                    'visit_id'       => $visit->visit_id,
                    'patient_id'     => $patient_id,
                    'doctor_id'      => $doctorId,
                    'symptoms'       => $postData['symptoms'],
                    'diagnosis'      => $postData['diagnosis'],
                    'medical_action' => $postData['medical_action'] ?? '',
                    'created_at'     => time()
                ]);

                $recordId = $db->insertID();

                // Simpan item pemeriksaan/obat/lab/xray yang dipilih dokter
                if (!empty($selectedItems)) {
                    foreach ($selectedItems as $itemId) {
                        $item = $db->table('service_items')
                            ->where('item_id', $itemId)
                            ->get()
                            ->getRow();

                        if (!$item) {
                            continue;
                        }

                        $qtyInputName = 'qty_' . $itemId;
                        $qty = (int) ($postData[$qtyInputName] ?? 1);

                        if ($qty <= 0) {
                            $qty = 1;
                        }

                        $subtotal = $item->price * $qty;

                        // Ini untuk tagihan pembayaran
                        $db->table('visit_items')->insert([
                            'visit_id'   => $visit->visit_id,
                            'patient_id' => $patient_id,
                            'doctor_id'  => $doctorId,
                            'item_id'    => $item->item_id,
                            'item_type'  => $item->item_type,
                            'item_name'  => $item->item_name,
                            'price'      => $item->price,
                            'qty'        => $qty,
                            'subtotal'   => $subtotal,
                            'note'       => null,
                            'status'     => 'Diajukan',
                            'created_at' => time()
                        ]);

                        $visitItemId = $db->insertID();

                        // Ini untuk rekam medis, tanpa harga
                        if ($db->tableExists('medical_record_details')) {
                            $db->table('medical_record_details')->insert([
                                'record_id'     => $recordId,
                                'visit_item_id' => $visitItemId,
                                'item_type'     => $item->item_type,
                                'item_name'     => $item->item_name,
                                'result_note'   => null,
                                'created_at'    => time()
                            ]);
                        }
                    }
                }

                // Jangan ubah jadi Selesai di sini.
                // Selesai nanti setelah pembayaran sudah Sudah Bayar.
                $db->table('patient_visits')
                    ->where('visit_id', $visit->visit_id)
                    ->update([
                        'status' => 'Telah Diurus'
                    ]);

                $db->transComplete();

                if ($db->transStatus() === false) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Gagal menyimpan pemeriksaan.');
                }

                return redirect()->to('doctor/queue')
                    ->with('success', 'Pemeriksaan berhasil disimpan. Data siap dibuatkan tagihan oleh resepsionis.');
            }
        }

        $history = $db->table('medical_records mr')
            ->select('mr.*, ud.first_name, ud.last_name')
            ->join('userdata ud', 'ud.user_id = mr.doctor_id', 'left')
            ->where('mr.patient_id', $patient_id)
            ->orderBy('mr.created_at', 'DESC')
            ->get()
            ->getResult();

        $data['title'] = 'Pemeriksaan: ' . trim($patient->first_name . ' ' . $patient->last_name);
        $data['patient'] = $patient;
        $data['visit'] = $visit;
        $data['history'] = $history;
        $data['groupedItems'] = $groupedItems;
        $data['includes'] = ['doctor/examine'];

        return view('header', $data)
            . view('index', $data)
            . view('footer', $data);
    }
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

    private function _getDoctorActiveVisits()
    {
        $db = \Config\Database::connect();
        $doctorId = session()->get('ba_user_id');

        $todayStart = strtotime('today midnight');
        $todayEnd   = strtotime('tomorrow midnight') - 1;

        return $db->table('patient_visits pv')
            ->select('
                pv.visit_id,
                pv.patient_id,
                pv.queue_number,
                pv.status,
                pv.payment_status,
                u.first_name,
                u.last_name
            ')
            ->join('userdata u', 'u.user_id = pv.patient_id', 'left')
            ->where('pv.doctor_id', $doctorId)
            ->where('pv.register_time >=', $todayStart)
            ->where('pv.register_time <=', $todayEnd)
            ->where('pv.status !=', 'Batal')
            ->where('pv.status !=', 'Selesai')
            ->orderBy('pv.visit_id', 'ASC')
            ->get()
            ->getResult();
    }

    private function _getServiceItems($type)
    {
        $db = \Config\Database::connect();

        return $db->table('service_items')
            ->where('item_type', $type)
            ->where('is_active', 1)
            ->orderBy('item_name', 'ASC')
            ->get()
            ->getResult();
    }

    private function _createMedicalRecord($visitId, $patientId, $doctorId, $catatan)
    {
        $db = \Config\Database::connect();

        $db->table('medical_records')->insert([
            'visit_id'          => $visitId,
            'patient_id'        => $patientId,
            'doctor_id'         => $doctorId,
            'keluhan'           => '',
            'diagnosis'         => '',
            'hasil_pemeriksaan' => '',
            'catatan_tindakan'  => $catatan,
            'created_at'        => time()
        ]);

        return $db->insertID();
    }

    private function _saveVisitItem($recordId, $visitId, $patientId, $doctorId, $item, $qty, $note)
    {
        $db = \Config\Database::connect();

        $subtotal = $item->price * $qty;

        $db->table('visit_items')->insert([
            'visit_id'   => $visitId,
            'patient_id' => $patientId,
            'doctor_id'  => $doctorId,
            'item_id'    => $item->item_id,
            'item_type'  => $item->item_type,
            'item_name'  => $item->item_name,
            'price'      => $item->price,
            'qty'        => $qty,
            'subtotal'   => $subtotal,
            'note'       => $note,
            'status'     => 'Diajukan',
            'created_at' => time()
        ]);

        $visitItemId = $db->insertID();

        $db->table('medical_record_details')->insert([
            'record_id'     => $recordId,
            'visit_item_id' => $visitItemId,
            'item_type'     => $item->item_type,
            'item_name'     => $item->item_name,
            'result_note'   => $note,
            'created_at'    => time()
        ]);
    }
    // 3. Menu: Membuat Resep Obat
    public function prescription()
    {
        $this->_check_access();

        $data['patients'] = $this->_getDoctorActiveVisits();
        $data['drugs'] = $this->_getServiceItems('OBAT');

        $data['title'] = 'Membuat Resep Obat';
        $data['includes'] = ['doctor/prescription'];

        return view('header', $data)
            . view('index', $data)
            . view('footer', $data);
    }

    public function save_prescription()
    {
        $this->_check_access();

        $db = \Config\Database::connect();
        $doctorId = session()->get('ba_user_id');

        $visitId   = (int) $this->request->getPost('visit_id');
        $patientId = (int) $this->request->getPost('patient_id');
        $itemIds   = $this->request->getPost('item_ids') ?? [];
        $qtys      = $this->request->getPost('qty') ?? [];
        $notes     = $this->request->getPost('note') ?? [];

        if ($visitId <= 0 || $patientId <= 0 || empty($itemIds)) {
            return redirect()->to('doctor/prescription')
                ->with('error', 'Pilih pasien dan minimal satu obat.');
        }

        $visit = $db->table('patient_visits')
            ->where('visit_id', $visitId)
            ->where('patient_id', $patientId)
            ->where('doctor_id', $doctorId)
            ->get()
            ->getRow();

        if (!$visit) {
            return redirect()->to('doctor/prescription')
                ->with('error', 'Data kunjungan pasien tidak valid.');
        }

        $db->transStart();

        $recordId = $this->_createMedicalRecord(
            $visitId,
            $patientId,
            $doctorId,
            'Resep obat diberikan oleh dokter.'
        );

        foreach ($itemIds as $index => $itemId) {
            $item = $db->table('service_items')
                ->where('item_id', (int) $itemId)
                ->where('item_type', 'OBAT')
                ->where('is_active', 1)
                ->get()
                ->getRow();

            if (!$item) {
                continue;
            }

            $qty = isset($qtys[$index]) ? (int) $qtys[$index] : 1;
            if ($qty <= 0) {
                $qty = 1;
            }

            $note = $notes[$index] ?? '';

            $db->table('prescriptions')->insert([
                'visit_id'             => $visitId,
                'patient_id'           => $patientId,
                'doctor_id'            => $doctorId,
                'drug_id'              => $item->item_id,
                'dosage_instructions'  => $note,
                'status'               => 'Pending',
                'created_at'           => time()
            ]);

            $this->_saveVisitItem($recordId, $visitId, $patientId, $doctorId, $item, $qty, $note);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('doctor/prescription')
                ->with('error', 'Gagal menyimpan resep obat.');
        }

        return redirect()->to('doctor/prescription')
            ->with('message', 'Resep obat berhasil dikirim ke apotek.');
    }

    public function lab_schedule()
    {
        $this->_check_access();

        $data['patients'] = $this->_getDoctorActiveVisits();
        $data['labItems'] = $this->_getServiceItems('LAB');

        $data['title'] = 'Menjadwalkan Tes Lab';
        $data['includes'] = ['doctor/lab_schedule'];

        return view('header', $data)
            . view('index', $data)
            . view('footer', $data);
    }

    public function lab_request()
    {
        return $this->lab_schedule();
    }

    public function save_lab_request()
    {
        $this->_check_access();

        $db = \Config\Database::connect();
        $doctorId = session()->get('ba_user_id');

        $visitId   = (int) $this->request->getPost('visit_id');
        $patientId = (int) $this->request->getPost('patient_id');
        $itemIds   = $this->request->getPost('item_ids') ?? [];
        $qtys      = $this->request->getPost('qty') ?? [];
        $notes     = $this->request->getPost('note') ?? [];

        if ($visitId <= 0 || $patientId <= 0 || empty($itemIds)) {
            return redirect()->to('doctor/lab_schedule')
                ->with('error', 'Pilih pasien dan minimal satu tes laboratorium.');
        }

        $visit = $db->table('patient_visits')
            ->where('visit_id', $visitId)
            ->where('patient_id', $patientId)
            ->where('doctor_id', $doctorId)
            ->get()
            ->getRow();

        if (!$visit) {
            return redirect()->to('doctor/lab_schedule')
                ->with('error', 'Data kunjungan pasien tidak valid.');
        }

        $db->transStart();

        $recordId = $this->_createMedicalRecord(
            $visitId,
            $patientId,
            $doctorId,
            'Permintaan tes laboratorium dibuat oleh dokter.'
        );

        foreach ($itemIds as $index => $itemId) {
            $item = $db->table('service_items')
                ->where('item_id', (int) $itemId)
                ->where('item_type', 'LAB')
                ->where('is_active', 1)
                ->get()
                ->getRow();

            if (!$item) {
                continue;
            }

            $qty = isset($qtys[$index]) ? (int) $qtys[$index] : 1;
            if ($qty <= 0) {
                $qty = 1;
            }

            $note = $notes[$index] ?? '';

            $db->table('lab_requests')->insert([
                'visit_id'     => $visitId,
                'patient_id'   => $patientId,
                'doctor_id'    => $doctorId,
                'test_id'      => $item->item_id,
                'doctor_notes' => $note,
                'status'       => 'Pending',
                'created_at'   => time()
            ]);

            $this->_saveVisitItem($recordId, $visitId, $patientId, $doctorId, $item, $qty, $note);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('doctor/lab_schedule')
                ->with('error', 'Gagal menyimpan permintaan lab.');
        }

        return redirect()->to('doctor/lab_schedule')
            ->with('message', 'Permintaan lab berhasil dikirim ke laboratorium.');
    }


    public function xray_schedule()
    {
        $this->_check_access();

        $data['patients'] = $this->_getDoctorActiveVisits();
        $data['xrayItems'] = $this->_getServiceItems('XRAY');

        $data['title'] = 'Menjadwalkan X-Ray';
        $data['includes'] = ['doctor/xray_schedule'];

        return view('header', $data)
            . view('index', $data)
            . view('footer', $data);
    }

    public function xray_request()
    {
        return $this->xray_schedule();
    }

    public function save_xray_request()
    {
        $this->_check_access();

        $db = \Config\Database::connect();
        $doctorId = session()->get('ba_user_id');

        $visitId   = (int) $this->request->getPost('visit_id');
        $patientId = (int) $this->request->getPost('patient_id');
        $itemIds   = $this->request->getPost('item_ids') ?? [];
        $qtys      = $this->request->getPost('qty') ?? [];
        $notes     = $this->request->getPost('note') ?? [];

        if ($visitId <= 0 || $patientId <= 0 || empty($itemIds)) {
            return redirect()->to('doctor/xray_schedule')
                ->with('error', 'Pilih pasien dan minimal satu pemeriksaan x-ray/radiologi.');
        }

        $visit = $db->table('patient_visits')
            ->where('visit_id', $visitId)
            ->where('patient_id', $patientId)
            ->where('doctor_id', $doctorId)
            ->get()
            ->getRow();

        if (!$visit) {
            return redirect()->to('doctor/xray_schedule')
                ->with('error', 'Data kunjungan pasien tidak valid.');
        }

        $db->transStart();

        $recordId = $this->_createMedicalRecord(
            $visitId,
            $patientId,
            $doctorId,
            'Permintaan x-ray/radiologi dibuat oleh dokter.'
        );

        foreach ($itemIds as $index => $itemId) {
            $item = $db->table('service_items')
                ->where('item_id', (int) $itemId)
                ->where('item_type', 'XRAY')
                ->where('is_active', 1)
                ->get()
                ->getRow();

            if (!$item) {
                continue;
            }

            $qty = isset($qtys[$index]) ? (int) $qtys[$index] : 1;
            if ($qty <= 0) {
                $qty = 1;
            }

            $note = $notes[$index] ?? '';

            $db->table('xray_requests')->insert([
                'visit_id'     => $visitId,
                'patient_id'   => $patientId,
                'doctor_id'    => $doctorId,
                'xray_id'      => $item->item_id,
                'doctor_notes' => $note,
                'status'       => 'Pending',
                'created_at'   => time()
            ]);

            $this->_saveVisitItem($recordId, $visitId, $patientId, $doctorId, $item, $qty, $note);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('doctor/xray_schedule')
                ->with('error', 'Gagal menyimpan permintaan x-ray.');
        }

        return redirect()->to('doctor/xray_schedule')
            ->with('message', 'Permintaan x-ray berhasil dikirim ke radiologi.');
    }

    public function add_medical_note($patient_id = 0)
    {
        $this->_check_access();

        $db = \Config\Database::connect();
        $doctorId = session()->get('ba_user_id');

        $patient = $db->table('userdata')
            ->where('user_id', $patient_id)
            ->get()
            ->getRow();

        if (!$patient) {
            return redirect()->to('doctor/medical_history')
                ->with('error', 'Data pasien tidak ditemukan.');
        }

        $todayStart = strtotime('today midnight');
        $todayEnd   = strtotime('tomorrow midnight') - 1;

        $visit = $db->table('patient_visits')
            ->where('patient_id', $patient_id)
            ->where('doctor_id', $doctorId)
            ->where('register_time >=', $todayStart)
            ->where('register_time <=', $todayEnd)
            ->where('status !=', 'Batal')
            ->orderBy('visit_id', 'DESC')
            ->get()
            ->getRow();

        if (!$visit) {
            $visit = $db->table('patient_visits')
                ->where('patient_id', $patient_id)
                ->orderBy('visit_id', 'DESC')
                ->get()
                ->getRow();
        }

        if (!$visit) {
            return redirect()->to('doctor/medical_history_detail/' . $patient_id)
                ->with('error', 'Pasien ini belum memiliki data kunjungan.');
        }

        // Di halaman tambah catatan medis, ambil PEMERIKSAAN saja.
        // Obat, Lab, dan X-Ray tetap di menu masing-masing.
        $pemeriksaanItems = $db->table('service_items')
            ->where('item_type', 'PEMERIKSAAN')
            ->where('is_active', 1)
            ->orderBy('item_name', 'ASC')
            ->get()
            ->getResult();

        if (strtolower($this->request->getMethod()) === 'post') {
            $deskripsiUmum = $this->request->getPost('deskripsi_umum');
            $itemIds       = $this->request->getPost('item_ids') ?? [];
            $qtys          = $this->request->getPost('qty') ?? [];
            $resultNotes   = $this->request->getPost('result_note') ?? [];

            if (empty($deskripsiUmum) && empty($itemIds)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Isi deskripsi atau tambahkan minimal satu pemeriksaan/tindakan.');
            }

            $db->transStart();

            $db->table('medical_records')->insert([
                'visit_id'           => $visit->visit_id,
                'patient_id'         => $patient_id,
                'doctor_id'          => $doctorId,
                'keluhan'            => '',
                'diagnosis'          => '',
                'hasil_pemeriksaan'  => '',
                'catatan_tindakan'   => $deskripsiUmum,
                'created_at'         => time()
            ]);

            $recordId = $db->insertID();

            foreach ($itemIds as $index => $itemId) {
                $itemId = (int) $itemId;

                $item = $db->table('service_items')
                    ->where('item_id', $itemId)
                    ->where('item_type', 'PEMERIKSAAN')
                    ->where('is_active', 1)
                    ->get()
                    ->getRow();

                if (!$item) {
                    continue;
                }

                $qty = isset($qtys[$index]) ? (int) $qtys[$index] : 1;

                if ($qty <= 0) {
                    $qty = 1;
                }

                $resultNote = $resultNotes[$index] ?? '';
                $subtotal = $item->price * $qty;

                // Untuk tagihan pembayaran
                $db->table('visit_items')->insert([
                    'visit_id'   => $visit->visit_id,
                    'patient_id' => $patient_id,
                    'doctor_id'  => $doctorId,
                    'item_id'    => $item->item_id,
                    'item_type'  => $item->item_type,
                    'item_name'  => $item->item_name,
                    'price'      => $item->price,
                    'qty'        => $qty,
                    'subtotal'   => $subtotal,
                    'note'       => $resultNote,
                    'status'     => 'Diajukan',
                    'created_at' => time()
                ]);

                $visitItemId = $db->insertID();

                // Untuk detail rekam medis, tanpa harga
                $db->table('medical_record_details')->insert([
                    'record_id'     => $recordId,
                    'visit_item_id' => $visitItemId,
                    'item_type'     => 'PEMERIKSAAN',
                    'item_name'     => $item->item_name,
                    'result_note'   => $resultNote,
                    'created_at'    => time()
                ]);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Gagal menyimpan catatan medis.');
            }

            return redirect()->to('doctor/medical_history_detail/' . $patient_id)
                ->with('success', 'Catatan medis berhasil ditambahkan.');
        }

        $data['title'] = 'Tambah Catatan Medis';
        $data['patient'] = $patient;
        $data['visit'] = $visit;
        $data['pemeriksaanItems'] = $pemeriksaanItems;
        $data['includes'] = ['doctor/add_medical_note'];

        return view('header', $data)
            . view('index', $data)
            . view('footer', $data);
    }
}