<?php

namespace App\Controllers;

class Receptionist extends BaseController
{
    public function __construct()
    {
        helper(['url', 'form']);
    }

    // 1. Daftar Pasien-Dokter
    public function patient_doctor_list()
    {
        $db = \Config\Database::connect();

        // Ambil dokter berdasarkan group_id dokter, bukan berdasarkan teks position
        $data['doctors'] = $db->table('userdata ud')
            ->select('ud.user_id, ud.first_name, ud.last_name, ud.position')
            ->join('user_group ug', 'ug.user_id = ud.user_id')
            ->where('ug.group_id', 3) // group_id 3 = Dokter
            ->orderBy('ud.first_name', 'ASC')
            ->get()
            ->getResult();

        $data['patientsByDoctor'] = [];

        foreach ($data['doctors'] as $doctor) {
            $data['patientsByDoctor'][$doctor->user_id] = $db->table('patient_visits pv')
                ->select('
                    pv.visit_id,
                    pv.patient_id,
                    pv.doctor_id,
                    pv.queue_number,
                    pv.status,
                    pv.register_time,
                    p.first_name AS patient_first_name,
                    p.last_name AS patient_last_name,
                    b.payment_status
                ')
                ->join('userdata p', 'p.user_id = pv.patient_id', 'left')
                ->join('billing b', 'b.patient_id = pv.patient_id', 'left')
                ->where('pv.doctor_id', $doctor->user_id)
                ->groupBy('pv.visit_id')
                ->orderBy('pv.register_time', 'DESC')
                ->get()
                ->getResult();
        }

        $data['title'] = 'Daftar Pasien-Dokter';
        $data['includes'] = ['receptionist/patient_doctor_list'];

        return view('header', $data)
            . view('index', $data)
            . view('footer', $data);
    }

    // 2. Antrean & Status
    public function queue_list()
    {
        $db = \Config\Database::connect();

        $todayStart = strtotime('today midnight');
        $todayEnd   = strtotime('tomorrow midnight') - 1;

        $data['queues'] = $db->table('patient_visits pv')
            ->select("
                pv.visit_id,
                pv.patient_id,
                pv.doctor_id,
                pv.queue_number,
                pv.status,
                pv.payment_status,
                pv.register_time,
                p.first_name,
                p.last_name
            ", false)
            ->join('userdata p', 'p.user_id = pv.patient_id', 'left')
            ->where('pv.register_time >=', $todayStart)
            ->where('pv.register_time <=', $todayEnd)
            ->where('pv.status !=', 'Batal')
            ->orderBy('pv.visit_id', 'ASC')
            ->get()
            ->getResult();

        $data['title'] = 'Antrean & Status';
        $data['includes'] = ['receptionist/queue_list'];

        return view('header', $data)
            . view('index', $data)
            . view('footer', $data);
    }

    // 3. Update Status
    public function update_status($visit_id)
    {
        $db = \Config\Database::connect();

        $new_status = $this->request->getPost('status');

        $allowed_status = ['Menunggu', 'Telah Diurus', 'Selesai'];

        if (!in_array($new_status, $allowed_status, true)) {
            return redirect()->to('patient/waiting')
                ->with('error', 'Status pelayanan tidak valid.');
        }

        $db->table('patient_visits')
            ->where('visit_id', $visit_id)
            ->update([
                'status' => $new_status
            ]);

        return redirect()->to('patient/waiting')
            ->with('message', 'Status pelayanan berhasil diperbarui.');
    }

    // 4. Pendaftaran Pasien
    public function register_patient()
    {
        $db = \Config\Database::connect();

        $todayStart = strtotime('today midnight');
        $todayEnd   = strtotime('tomorrow midnight') - 1;

        // Ambil pasien dari antrean hari ini, bukan dari semua pasien
        $data['queues'] = $db->table('patient_visits pv')
            ->select("
                pv.visit_id,
                pv.patient_id,
                pv.queue_number,
                pv.status,
                p.first_name,
                p.last_name
            ", false)
            ->join('userdata p', 'p.user_id = pv.patient_id', 'left')
            ->where('pv.register_time >=', $todayStart)
            ->where('pv.register_time <=', $todayEnd)
            ->where('pv.status !=', 'Batal')
            ->groupStart()
                ->where('pv.doctor_id', 0)
                ->orWhere('pv.doctor_id IS NULL', null, false)
            ->groupEnd()
            ->orderBy('pv.visit_id', 'ASC')
            ->get()
            ->getResult();

        // Ambil daftar dokter
        $data['doctors'] = $db->table('userdata ud')
            ->select('ud.user_id, ud.first_name, ud.last_name, ud.position')
            ->join('user_group ug', 'ug.user_id = ud.user_id')
            ->where('ug.group_id', 3)
            ->orderBy('ud.first_name', 'ASC')
            ->get()
            ->getResult();

        $data['title'] = 'Pendaftaran Pasien';
        $data['includes'] = ['receptionist/registration'];

        return view('header', $data)
            . view('index', $data)
            . view('footer', $data);
    }

    public function save_registration()
    {
        $db = \Config\Database::connect();

        $visit_id  = (int) $this->request->getPost('visit_id');
        $doctor_id = (int) $this->request->getPost('doctor_id');

        if ($visit_id <= 0 || $doctor_id <= 0) {
            return redirect()->to('patient/register')
                ->with('error', 'Pasien dari antrean dan dokter wajib dipilih.');
        }

        $queue = $db->table('patient_visits')
            ->where('visit_id', $visit_id)
            ->where('status !=', 'Batal')
            ->get()
            ->getRow();

        if (!$queue) {
            return redirect()->to('patient/register')
                ->with('error', 'Data antrean tidak ditemukan.');
        }

        // Update antrean lama, bukan insert antrean baru
        $db->table('patient_visits')
            ->where('visit_id', $visit_id)
            ->update([
                'doctor_id' => $doctor_id,
                'status'    => 'Telah Diurus'
            ]);

        return redirect()->to('patient/waiting')
            ->with('message', 'Pasien berhasil didaftarkan ke dokter.');
    }

    private function _generate_queue_number()
    {
        $db = \Config\Database::connect();

        // Format: P5-0001
        $prefix = 'P' . date('n') . '-';

        $todayStart = strtotime('today midnight');
        $todayEnd   = strtotime('tomorrow midnight') - 1;

        $startPos = strlen($prefix) + 1;

        $lastQueue = $db->table('patient_visits')
            ->select("MAX(CAST(SUBSTRING(queue_number, {$startPos}) AS UNSIGNED)) AS last_number", false)
            ->like('queue_number', $prefix, 'after')
            ->where('register_time >=', $todayStart)
            ->where('register_time <=', $todayEnd)
            ->get()
            ->getRow();

        $lastNumber = (int) ($lastQueue->last_number ?? 0);
        $nextNumber = $lastNumber + 1;

        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    public function assign_doctor($visit_id)
    {
        $db = \Config\Database::connect();
        $doctor_id = $this->request->getPost('doctor_id');
        
        $db->table('patient_visits')
        ->where('visit_id', $visit_id)
        ->update(['doctor_id' => $doctor_id]);
        
        return redirect()->to('patient/waiting')->with('message', 'Dokter berhasil ditugaskan.');
    }

    public function update_payment_status($visit_id)
    {
        $db = \Config\Database::connect();

        $payment_status = $this->request->getPost('payment_status');

        $allowed_payment = ['Belum Bayar', 'Sudah Bayar'];

        if (!in_array($payment_status, $allowed_payment, true)) {
            return redirect()->to('patient/waiting')
                ->with('error', 'Status pembayaran tidak valid.');
        }

        $updateData = [
            'payment_status' => $payment_status
        ];

        // Kalau pembayaran sudah selesai, pelayanan ikut dianggap selesai
        if ($payment_status === 'Sudah Bayar') {
            $updateData['status'] = 'Selesai';
        }

        $db->table('patient_visits')
            ->where('visit_id', $visit_id)
            ->update($updateData);

        return redirect()->to('patient/waiting')
            ->with('message', 'Status pembayaran berhasil diperbarui.');
    }
}