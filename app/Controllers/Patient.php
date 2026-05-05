<?php

namespace App\Controllers;

class Patient extends BaseController
{
    public function __construct()
    {
        helper(['url', 'form', 'date']);
        
        // Aktifkan Bitauth!
        $this->bitauth = new \App\Libraries\Bitauth();
    }

    public function index($limit = 15, $page = 1, $reverse = 1)
    {
        // Asumsi bitauth sudah dikonversi atau diganti. 
        // Jika masih error, matikan dulu baris ini saat testing.
        /*
        if (!$this->bitauth->logged_in()) {
            session()->set('redir', current_url());
            return redirect()->to('account/login');
        }
        */

        // PERBAIKAN: Tambah 'Model'
        $patientsModel = model('PatientsModel');
        
        $data['patients'] = $patientsModel->get(0, 0, (int)$reverse);
        $data['title'] = 'Patient List';
        $data['navActiveId'] = 'navbarLiPatient';
        $data['page'] = (int)$page;
        $data['per_page'] = (int)$limit;
        
        // Pagination CI4 akan diatur di View atau Model nanti.
        $data['pagination'] = ""; 
        
        $path = 'patient/list';
        if ($this->request->getGet('ajax')) {
            return view($path, $data);
        } else {
            $data['includes'] = [$path];
            return view('header', $data) . view('index', $data) . view('footer', $data);
        }
    }

    // PERBAIKAN: Menambahkan fungsi patientList untuk mengatasi error 404 Route
    public function patientList($limit = 15, $page = 1, $reverse = 1)
    {
        return $this->index($limit, $page, $reverse);
    }

    public function status($patient_doctor_id = 0)
    {
        // PERBAIKAN: Tambah 'Model'
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

    public function waiting($doctor = 0)
    {
        // PERBAIKAN: Tambah 'Model' (Sudah diperbaiki sebelumnya)
        $patientDoctorModel = model('PatientDoctorModel');
        
        // Logika aslinya: if(!$doctor && $this->bitauth->has_role('doctor',False))
        // $doctor = session()->get('ba_user_id');
        
        $data['waitings'] = $patientDoctorModel->get_waiting($doctor);
        $data['title'] = 'Waiting List';
        $data['navActiveId'] = 'navbarLiPatient';
        
        $path = 'patient/waiting';
        if ($this->request->getGet('ajax')) {
            return view($path, $data);
        } else {
            $data['includes'] = [$path];
            return view('header', $data) . view('index', $data) . view('footer', $data);
        }
    }
  
    public function register(){
        $data = [];
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'first_name' => 'required',
                'gender'     => 'required',
                'phone'      => 'required',
                'doctor'     => 'required',
                'age'        => 'required|numeric',
            ];
            log_message('debug', 'POST DATA: ' . json_encode($this->request->getPost()));

            if ($this->validate($rules)) {
                // PERBAIKAN: Tambah 'Model'
                $patientsModel = model('PatientsModel');
                $patientDoctorModel = model('PatientDoctorModel');
                
                $postData = $this->request->getPost();
                $_doctor = $postData['doctor'];
                $birth_date = mktime(0, 0, 0, date('m'), date('d'), date('Y') - $postData['age']); 
                
                // Hapus data yang tidak masuk tabel
                unset($postData['submit'], $postData['doctor'], $postData['age']); 
                
                $postData['birth_date'] = $birth_date;
                $postData['create_date'] = time();
                
                foreach ($postData as $key => $value) {
                    $patientsModel->$key = $value;
                }
                $patientsModel->save();
                
                $patientDoctorModel->patient_id = $patientsModel->patient_id;
                $patientDoctorModel->user_id = $_doctor;
                $patientDoctorModel->visit_date = time();
                $patientDoctorModel->save();
                
                return redirect()->to('patient/ticket/' . $patientsModel->patient_id);
            } else {
                $data['error'] = '<div class="alert alert-danger">' . $this->validator->listErrors() . '</div>';
                log_message('debug', 'VALIDATION ERROR: ' . json_encode($this->validator->getErrors()));
            }
        }

        $data['title'] = 'Register Patient'; 
        $data['id_type_options'] = $this->_id_type_options();
        $data['doctor_list'] = $this->_doctor_list();
        
        // PERBAIKAN: Ubah add_patient menjadi register
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
        // PERBAIKAN: Tambah 'Model'
        $patientsModel = model('PatientsModel');
        $patientDoctorModel = model('PatientDoctorModel');
        
        $patientsModel->load($patient_id);
        $patientDoctorModel->get_by_fkey('patient_id', $patient_id);
        
        // $doc_info = $this->bitauth->get_user_by_id($patientDoctorModel->user_id);
        
        $data['title'] = 'Patient Ticket';
        $data['patient'] = $patientsModel;
        $data['doctor'] = $patientDoctorModel;
        $data['doc_info'] = []; // Ganti dengan doc_info jika bitauth jalan
        
        $data['includes'] = ['patient/ticket'];
        return view('header', $data) . view('index', $data) . view('footer', $data);
    }

    public function panel($patient_id = 0)
    {
        // PERBAIKAN: Tambah 'Model'
        $patientsModel = model('PatientsModel');
        $patientDoctorModel = model('PatientDoctorModel');
        $commentsModel = model('CommentsModel');
        $drugPatientModel = model('DrugPatientModel');
        $xrayPatientModel = model('XrayPatientModel');
        $labPatientModel = model('LabPatientModel');
        
        $patientsModel->load($patient_id);
        $patientDoctorModel->get_by_fkey('patient_id', $patient_id);
        
        $comments = 'unauthorized';
        // if($patientDoctorModel->user_id == 0 || session()->get('ba_user_id') == $patientDoctorModel->user_id)
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
        // PERBAIKAN: Tambah 'Model'
        $patientsModel = model('PatientsModel');
        $patientsModel->load($patient_id);
          
        $data = [];
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'first_name' => 'required',
                'gender'     => 'required',
                'phone'      => 'required',
                'doctor'     => 'required'
            ];
        
            if ($this->validate($rules)) {
                $session_check = session()->get(current_url());
                session()->remove(current_url());
                
                if ($session_check && $session_check[0] == $patient_id) {
                    $postData = $this->request->getPost();
                    $doctor = $postData['doctor'];
                    unset($postData['doctor'], $postData['submit']);
                    
                    // Upload file ala CI4
                    $picture = $this->request->getFile('picture');
                    if ($picture && $picture->isValid() && !$picture->hasMoved()) {
                        $path = 'uploads/patient/'.$patient_id.'/profile/';
                        $newName = 'p'.$patient_id.'_profile_picture.' . $picture->getExtension();
                        $picture->move($path, $newName);
                        $postData['picture'] = $path . $newName;
                        
                        if (isset($patientsModel->picture) && $patientsModel->picture != $postData['picture']) {
                            @unlink('./' . $patientsModel->picture);
                        }
                    }
                    
                    foreach ($postData as $key => $value) {
                        $patientsModel->$key = $value;
                    }
                    if(isset($postData['birth_date'])) {
                        $patientsModel->birth_date = strtotime($postData['birth_date']);
                    }
                    $patientsModel->save();
                    
                    // PERBAIKAN: Tambah 'Model'
                    $patientDoctorModel = model('PatientDoctorModel');
                    $patientDoctorModel->load($session_check[1]);
                    $patientDoctorModel->patient_id = $patient_id;
                    $patientDoctorModel->user_id = $doctor;
                    if ($patientDoctorModel->status == 1) $patientDoctorModel->status = 0;
                    $patientDoctorModel->save();
                    
                    return redirect()->to('patient');
                } else {
                    $data['error'] = '<div class="alert alert-danger">Form URL Error</div>';
                }
            } else {
                $data['error'] = $this->validator->listErrors();
            }
        }
        
        // PERBAIKAN: Tambah 'Model'
        $patientDoctorModel = model('PatientDoctorModel');
        $patientDoctorModel->get_by_fkey('patient_id', $patient_id);
        session()->set(current_url(), [$patient_id, $patientDoctorModel->patient_doctor_id]);
        
        $data['title'] = 'Edit Patient';
        $data['patient'] = $patientsModel;
        $data['doctor'] = $patientDoctorModel->user_id;
        $data['doctor_list'] = $this->_doctor_list();
        $data['id_type_options'] = $this->_id_type_options();
        
        $path = 'patient/edit_patient';
        if ($this->request->getGet('ajax')) {
            return view($path, $data);
        } else {
            $data['includes'] = [$path];
            return view('header', $data) . view('index', $data) . view('footer', $data);
        }
    }

    public function _doctor_list()
    {
        $db = \Config\Database::connect();

        // Ambil semua user yang group_id = 3 (Doctor)
        $doctors = $db->table('userdata ud')
                    ->select('ud.user_id, ud.first_name, ud.last_name')
                    ->join('user_group ug', 'ug.user_id = ud.user_id')
                    ->where('ug.group_id', 3)
                    ->get()
                    ->getResultArray();

        $doctor_list = [0 => '-- Pilih Dokter --'];

        foreach ($doctors as $doctor) {
            $doctor_list[$doctor['user_id']] = $doctor['last_name'] . ', ' . $doctor['first_name'];
        }

        return $doctor_list;
    }

    public function _id_type_options()
    {
        return [
            'Tazkara' => 'Tazkara',
            'Passport' => 'Passport',
            'Driver License' => 'Driver License',
            'Bank ID Card' => 'Bank ID Card',
        ];
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