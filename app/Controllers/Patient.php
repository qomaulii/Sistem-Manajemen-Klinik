<?php

namespace App\Controllers;

class Patient extends BaseController
{
    public function __construct()
    {
        helper(['url', 'form', 'date']);
        $this->bitauth = new \App\Libraries\Bitauth();
    }

    // --- MANAJEMEN DATA PASIEN (ADMIN/RESEPSIONIS) ---
    public function index($limit = 15, $page = 1, $reverse = 1)
    {
        $patientsModel = model('PatientsModel');
        $data['patients'] = $patientsModel->get(0, 0, (int)$reverse);
        $data['title'] = 'Patient List';
        $data['navActiveId'] = 'navbarLiPatient';
        $data['page'] = (int)$page;
        $data['per_page'] = (int)$limit;
        $data['pagination'] = ""; 
        $path = 'patient/list';
        if ($this->request->getGet('ajax')) {
            return view($path, $data);
        } else {
            $data['includes'] = [$path];
            return view('header', $data) . view('index', $data) . view('footer', $data);
        }
    }

    public function patientList($limit = 15, $page = 1, $reverse = 1)
    {
        return $this->index($limit, $page, $reverse);
    }

    public function status($patient_doctor_id = 0)
    {
        $patientDoctorModel = model('PatientDoctorModel');
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'patient_doctor_id' => 'required|numeric',
                'patient_id'        => 'required|numeric',
                'user_id'           => 'required|numeric',
                'status'            => 'required|numeric'
            ];
            if ($this->validate($rules)) {
                $postData = $this->request->getPost();
                $patientDoctorModel->load($postData['patient_doctor_id']);
                if ($patientDoctorModel->patient_id == $postData['patient_id']) {
                    $statusCode = $postData['status'];
                    $patientDoctorModel->user_id = $postData['user_id'];
                    $patientDoctorModel->status = $statusCode;
                    if ($statusCode == 2) {
                        $patientDoctorModel->visit_date = time();
                    }
                    $patientDoctorModel->save();
                    return redirect()->to($this->request->getPost('url'));
                }
            }
        }
    }

    public function waiting()
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
                pv.register_time,
                p.first_name,
                p.last_name,
                b.payment_status
            ", false)
            ->join('userdata p', 'p.user_id = pv.patient_id', 'left')
            ->join(
                'billing b',
                'b.patient_id = pv.patient_id 
                AND b.create_date = (
                    SELECT MAX(b2.create_date) 
                    FROM billing b2 
                    WHERE b2.patient_id = pv.patient_id
                )',
                'left',
                false
            )
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

    public function register()
    {
        $data = [];
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'first_name' => 'required',
                'gender'     => 'required',
                'phone'      => 'required',
                'doctor'     => 'required',
                'age'        => 'required|numeric',
            ];
            if ($this->validate($rules)) {
                $patientsModel = model('PatientsModel');
                $patientDoctorModel = model('PatientDoctorModel');
                $postData = $this->request->getPost();
                $_doctor = $postData['doctor'];
                $birth_date = mktime(0, 0, 0, date('m'), date('d'), date('Y') - $postData['age']); 
                unset($postData['submit'], $postData['doctor'], $postData['age']); 
                $postData['birth_date'] = $birth_date;
                $postData['create_date'] = time();
                foreach ($postData as $key => $value) {
                    $patientsModel->$key = $value;
                }
                $patientsModel->save();
                $newPatientId = $patientsModel->getInsertID(); 
                $patientDoctorModel->patient_id = $newPatientId;
                $patientDoctorModel->user_id = $_doctor;
                $patientDoctorModel->visit_date = time();
                $patientDoctorModel->save();
                
                $db = \Config\Database::connect();
                $db->table('patient_visits')->insert([
                    'patient_id'    => $newPatientId,
                    'doctor_id'     => $_doctor,
                    'queue_number'  => $this->_generate_queue_number(),
                    'status'        => 'Menunggu',
                    'register_time' => time()
                ]);
                return redirect()->to('patient/ticket/' . $newPatientId);
            } else {
                $data['error'] = '<div class="alert alert-danger">' . $this->validator->listErrors() . '</div>';
            }
        }
        $data['title'] = 'Register Patient'; 
        $data['id_type_options'] = $this->_id_type_options();
        $data['doctor_list'] = $this->_doctor_list();
        $path = 'patient/register';
        if ($this->request->getGet('ajax')) {
            return view($path, $data);
        } else {
            $data['includes'] = [$path];
            return view('header', $data) . view('index', $data) . view('footer', $data);
        }
    }

    public function ticket($patient_id = 0)
    {
        $patientsModel = model('PatientsModel');
        $patientDoctorModel = model('PatientDoctorModel');
        $patientsModel->load($patient_id);
        $patientDoctorModel->get_by_fkey('patient_id', $patient_id);
        $data['title'] = 'Patient Ticket';
        $data['patient'] = $patientsModel;
        $data['doctor'] = $patientDoctorModel;
        $data['doc_info'] = []; 
        $data['includes'] = ['patient/ticket'];
        return view('header', $data) . view('index', $data) . view('footer', $data);
    }

    public function panel($patient_id = 0)
    {
        $patientsModel = model('PatientsModel');
        $patientDoctorModel = model('PatientDoctorModel');
        $commentsModel = model('CommentsModel');
        $drugPatientModel = model('DrugPatientModel');
        $xrayPatientModel = model('XrayPatientModel');
        $labPatientModel = model('LabPatientModel');
        $patientsModel->load($patient_id);
        $patientDoctorModel->get_by_fkey('patient_id', $patient_id);
        $comments = $commentsModel->get_by_fkey('patient_doctor_id', $patientDoctorModel->patient_doctor_id, 'desc', 0);
        $data['title'] = 'Patient Panel';
        $data['patient'] = $patientsModel;
        $data['doctor'] = $patientDoctorModel;
        $data['doc_info'] = []; 
        $data['comments'] = $comments;
        $data['drugs'] = $drugPatientModel->get_by_fkey('patient_id', $patientsModel->patient_id, 'asc', 0);
        $data['xrays'] = $xrayPatientModel->get_by_fkey('patient_id', $patientsModel->patient_id, 'asc', 0);
        $data['lab'] = $labPatientModel->get_by_fkey('patient_id', $patientsModel->patient_id, 'asc', 0);
        $path = 'patient/panel';
        if ($this->request->getGet('ajax')) {
            return view($path, $data);
        } else {
            $data['includes'] = [$path];
            return view('header', $data) . view('index', $data) . view('footer', $data);
        }
    }

    public function edit_patient($patient_id = 0)
    {
        $patientsModel = model('PatientsModel');
        $patientsModel->load($patient_id);
        $data = [];
        if ($this->request->getMethod() === 'post') {
            $rules = ['first_name' => 'required', 'gender' => 'required', 'phone' => 'required', 'doctor' => 'required'];
            if ($this->validate($rules)) {
                $session_check = session()->get(current_url());
                session()->remove(current_url());
                if ($session_check && $session_check[0] == $patient_id) {
                    $postData = $this->request->getPost();
                    $doctor = $postData['doctor'];
                    unset($postData['doctor'], $postData['submit']);
                    $picture = $this->request->getFile('picture');
                    if ($picture && $picture->isValid() && !$picture->hasMoved()) {
                        $path = 'uploads/patient/'.$patient_id.'/profile/';
                        $newName = 'p'.$patient_id.'_profile_picture.' . $picture->getExtension();
                        $picture->move($path, $newName);
                        $postData['picture'] = $path . $newName;
                    }
                    foreach ($postData as $key => $value) { $patientsModel->$key = $value; }
                    if(isset($postData['birth_date'])) { $patientsModel->birth_date = strtotime($postData['birth_date']); }
                    $patientsModel->save();
                    
                    $patientDoctorModel = model('PatientDoctorModel');
                    $patientDoctorModel->load($session_check[1]);
                    $patientDoctorModel->patient_id = $patient_id;
                    $patientDoctorModel->user_id = $doctor;
                    if ($patientDoctorModel->status == 1) $patientDoctorModel->status = 0;
                    $patientDoctorModel->save();
                    return redirect()->to('patient');
                }
            } else {
                $data['error'] = $this->validator->listErrors();
            }
        }
        $patientDoctorModel = model('PatientDoctorModel');
        $patientDoctorModel->get_by_fkey('patient_id', $patient_id);
        session()->set(current_url(), [$patient_id, $patientDoctorModel->patient_doctor_id]);
        $data['title'] = 'Edit Patient';
        $data['patient'] = $patientsModel;
        $data['doctor'] = $patientDoctorModel->user_id;
        $data['doctor_list'] = $this->_doctor_list();
        $data['id_type_options'] = $this->_id_type_options();
        $path = 'patient/edit_patient';
        if ($this->request->getGet('ajax')) { return view($path, $data); } else {
            $data['includes'] = [$path];
            return view('header', $data) . view('index', $data) . view('footer', $data);
        }
    }

    // --- MENU PASIEN (BOOKING & HISTORY) ---
    public function booking()
    {
        $patient_id = session()->get('ba_user_id');
        if (!$patient_id) return redirect()->to('account/login');

        $db = \Config\Database::connect();
        $data['my_bookings'] = $db->table('patient_visits')
                                 ->where('patient_id', $patient_id)
                                 ->orderBy('register_time', 'DESC')
                                 ->get()->getResult();
        
        $data['title'] = 'Booking Antrean';
        $data['includes'] = ['patient/booking'];
        return view('header', $data) . view('index', $data) . view('footer', $data);
    }

    public function book_queue()
    {
        $patient_id = session()->get('ba_user_id');

        if (!$patient_id) {
            return redirect()->to('account/login');
        }

        $db = \Config\Database::connect();

        $queue_number = $this->_generate_queue_number();

        $data_insert = [
            'patient_id'    => (int) $patient_id,
            'doctor_id'     => 0,
            'queue_number'  => $queue_number,
            'status'        => 'Menunggu',
            'register_time' => time()
        ];

        $db->table('patient_visits')->insert($data_insert);

        return redirect()->to('patient/booking')
            ->with('message', 'Booking berhasil!! Nomor Anda: ' . $queue_number);
    }

    public function cancel_booking($visit_id)
    {
        $db = \Config\Database::connect();
        $db->table('patient_visits')->where('visit_id', $visit_id)->delete();
        return redirect()->to('patient/booking')->with('message', 'Antrean berhasil dibatalkan.');
    }

    public function history()
    {
        $patient_id = session()->get('ba_user_id') ?: session()->get('user_id');

        if (!$patient_id) {
            return redirect()->to('account/login');
        }

        $db = \Config\Database::connect();

        $visits = $db->table('patient_visits pv')
            ->select("
                pv.*,
                dokter.first_name AS doctor_first_name,
                dokter.last_name AS doctor_last_name,
                b.bill_id,
                b.total_amount,
                b.payment_method,
                b.payment_status AS billing_status,
                b.paid_date
            ")
            ->join('userdata dokter', 'dokter.user_id = pv.doctor_id', 'left')
            ->join('billing b', 'b.visit_id = pv.visit_id', 'left')
            ->where('pv.patient_id', $patient_id)
            ->orderBy('pv.register_time', 'DESC')
            ->get()
            ->getResult();

        foreach ($visits as $v) {
            $v->records = $db->table('medical_records mr')
                ->select("
                    mr.*,
                    dokter.first_name AS doc_first,
                    dokter.last_name AS doc_last
                ")
                ->join('userdata dokter', 'dokter.user_id = mr.doctor_id', 'left')
                ->where('mr.visit_id', $v->visit_id)
                ->orderBy('mr.created_at', 'DESC')
                ->get()
                ->getResult();

            $v->details = $db->table('medical_record_details mrd')
                ->select('mrd.*')
                ->join('medical_records mr', 'mr.record_id = mrd.record_id', 'left')
                ->where('mr.visit_id', $v->visit_id)
                ->orderBy('mrd.created_at', 'ASC')
                ->get()
                ->getResult();

            $v->items = $db->table('visit_items')
                ->where('visit_id', $v->visit_id)
                ->orderBy('item_type', 'ASC')
                ->orderBy('visit_item_id', 'ASC')
                ->get()
                ->getResult();

            $v->medicines = $db->table('visit_items')
                ->where('visit_id', $v->visit_id)
                ->where('item_type', 'OBAT')
                ->orderBy('visit_item_id', 'ASC')
                ->get()
                ->getResult();

            $v->labs = $db->table('lab_requests lr')
                ->select("
                    lr.*,
                    si.item_name AS test_name,
                    dokter.first_name AS doctor_first_name,
                    dokter.last_name AS doctor_last_name
                ")
                ->join('service_items si', 'si.item_id = lr.test_id AND si.item_type = "LAB"', 'left')
                ->join('userdata dokter', 'dokter.user_id = lr.doctor_id', 'left')
                ->where('lr.visit_id', $v->visit_id)
                ->orderBy('lr.created_at', 'ASC')
                ->get()
                ->getResult();

            $v->xrays = $db->table('xray_requests xr')
                ->select("
                    xr.*,
                    si.item_name AS xray_name,
                    dokter.first_name AS doctor_first_name,
                    dokter.last_name AS doctor_last_name
                ")
                ->join('service_items si', 'si.item_id = xr.xray_id AND si.item_type = "XRAY"', 'left')
                ->join('userdata dokter', 'dokter.user_id = xr.doctor_id', 'left')
                ->where('xr.visit_id', $v->visit_id)
                ->orderBy('xr.created_at', 'ASC')
                ->get()
                ->getResult();

            $v->billing = $db->table('billing')
                ->where('visit_id', $v->visit_id)
                ->get()
                ->getRow();
        }

        $data['visits'] = $visits;
        $data['title'] = 'Riwayat & Hasil Pemeriksaan';
        $data['includes'] = ['patient/history'];

        return view('header', $data)
            . view('index', $data)
            . view('footer', $data);
    }

    private function _generate_queue_number()
    {
        $db = \Config\Database::connect();

        // Format: P5-0001
        $prefix = 'P' . date('n') . '-';

        $todayStart = strtotime('today midnight');
        $todayEnd   = strtotime('tomorrow midnight') - 1;

        // Ambil angka terbesar dari semua antrean hari ini,
        // termasuk yang batal, supaya nomor tidak dipakai ulang.
        $lastQueue = $db->table('patient_visits')
            ->select("MAX(CAST(SUBSTRING(queue_number, " . (strlen($prefix) + 1) . ") AS UNSIGNED)) AS last_number", false)
            ->like('queue_number', $prefix, 'after')
            ->where('register_time >=', $todayStart)
            ->where('register_time <=', $todayEnd)
            ->get()
            ->getRow();

        $lastNumber = (int) ($lastQueue->last_number ?? 0);
        $nextNumber = $lastNumber + 1;

        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    public function _doctor_list()
    {
        $db = \Config\Database::connect();
        $doctors = $db->table('userdata ud')
                      ->select('ud.user_id, ud.first_name, ud.last_name')
                      ->join('user_group ug', 'ug.user_id = ud.user_id')
                      ->where('ug.group_id', 3)
                      ->get()->getResultArray();
        $doctor_list = [0 => '-- Pilih Dokter --'];
        foreach ($doctors as $doctor) {
            $doctor_list[$doctor['user_id']] = $doctor['last_name'] . ', ' . $doctor['first_name'];
        }
        return $doctor_list;
    }

    public function _id_type_options() { return ['Tazkara' => 'Tazkara', 'Passport' => 'Passport', 'Driver License' => 'Driver License', 'Bank ID Card' => 'Bank ID Card']; }
}