<?php

namespace App\Controllers;

class Test extends BaseController
{
    public function __construct()
    {
        helper(['url', 'form', 'date']);
    }

    public function index($limit = 15, $page = 1)
    {
        // Panggil model
        $labModel = new \App\Models\LabModel();
        $data['tests'] = $labModel->findAll();

        $data['title'] = 'Tests List';
        $data['navActiveId'] = 'navbarLiLab';
        $data['page'] = (int)$page;
        $data['per_page'] = (int)$limit;
        $data['pagination'] = ""; 
        
        $path = 'lab/list';

        // PERBAIKAN: Load view beserta header dan footernya agar UI muncul
        if ($this->request->getGet('ajax')) {
            return view($path, $data);
        } else {
            // Ini yang bikin UI-nya balik lagi!
            $data['includes'] = [$path];
            return view('header', $data) . view('index', $data) . view('footer', $data);
        }
    }
  
    public function search()
    {
        if ($this->request->getMethod() === 'post') {
            $labModel = model('Lab');
            $q = $this->request->getPost('q');
            
            $data['lab'] = $labModel->search(['lab_name_en' => $q, 'lab_name_fa' => $q]);
            return view('lab/result', $data);
        }
        $data['title'] = 'Test Search';
        return view('lab/search', $data);
    }
  
    public function edit($test_id = 0)
    {
        $labModel = model('Lab');
        $labModel->load($test_id);
        
        $data = [];
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'test_name_en' => 'required',
                'test_name_fa' => 'required',
                'price'        => 'required'
            ];
            
            if ($this->validate($rules)) {
                $session_check = session()->get(current_url());
                session()->remove(current_url());
                
                if ($session_check && $session_check[0] == $test_id) {
                    $postData = $this->request->getPost();
                    unset($postData['submit']);
                    
                    foreach ($postData as $key => $value) {
                        $labModel->$key = $value;
                    }
                    $labModel->save();
                    
                    $data['script'] = '<script>alert("'. esc($labModel->test_name_en). ' has been updated successfuly.");</script>';
                    return redirect()->to('test');
                } else {
                    $data['error'] = '<div class="alert alert-danger">Form URL Error</div>';
                }
            } else {
                $data['error'] = $this->validator->listErrors();
            }
        }
        
        session()->set(current_url(), [$test_id]);
        $data['title'] = 'Edit Test';
        $data['test'] = $labModel;
        
        $path = 'lab/edit';
        if ($this->request->getGet('ajax')) {
            return view($path, $data);
        } else {
            $data['includes'] = [$path];
            return view('header', $data) . view('index', $data) . view('footer', $data);
        }
    }

    private function _uploadLabProof($oldFile = null)
    {
        $file = $this->request->getFile('proof_file');

        if (!$file || $file->getError() === 4) {
            return $oldFile;
        }

        if (!$file->isValid()) {
            throw new \RuntimeException('File bukti hasil lab tidak valid.');
        }

        $allowedExt = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'];
        $ext = strtolower($file->getClientExtension());

        if (!in_array($ext, $allowedExt)) {
            throw new \RuntimeException('Format file harus jpg, jpeg, png, pdf, doc, atau docx.');
        }

        if ($file->getSize() > 5 * 1024 * 1024) {
            throw new \RuntimeException('Ukuran file maksimal 5 MB.');
        }

        $uploadPath = FCPATH . 'uploads/lab_results/';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $newName = $file->getRandomName();
        $file->move($uploadPath, $newName);

        if (!empty($oldFile) && file_exists(FCPATH . $oldFile)) {
            unlink(FCPATH . $oldFile);
        }

        return 'uploads/lab_results/' . $newName;
    }

    public function delete($test_id = 0)
    {
        $labModel = model('Lab');
        $labModel->load($test_id);
        
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'test_id' => 'required',
                'del'     => 'required'
            ];
            
            if ($this->validate($rules) && $this->request->getPost('test_id') == $test_id) {
                $session_check = session()->get(current_url());
                session()->remove(current_url());
                
                if ($session_check && $session_check[0] == $test_id) {
                    $labPatientModel = model('LabPatient');
                    $labPatientModel->get_by_fkey('test_id', $test_id);
                    
                    if (!$labPatientModel->lab_patient_id) {
                        $labModel->delete();
                        echo 'ok';
                        return;
                    } else {
                        echo 'nok';
                        return;
                    }
                } else {
                    echo 'mismatch';
                    return;
                }
            } else {
                echo 'invalid';
                return;
            }
        }
        
        session()->set(current_url(), [$test_id]);
        $data['test'] = $labModel;
        return view('lab/confirm_delete', $data);
    }
  
    public function new_test()
    {
        $data = [];
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'test_name_en' => 'required',
                'test_name_fa' => 'required',
                'price'        => 'required'
            ];
            
            if ($this->validate($rules)) {
                $labModel = model('Lab');
                $postData = $this->request->getPost();
                unset($postData['submit']);
                
                foreach ($postData as $key => $value) {
                    $labModel->$key = $value;
                }
                $labModel->save();
                $data['script'] = '<script>alert("'. esc($labModel->test_name_en). ' has been registered successfuly.");</script>';
            } else {
                $data['error'] = $this->validator->listErrors();
            }
        }
        
        $data['title'] = 'New Test';
        $path = 'lab/new';
        if ($this->request->getGet('ajax')) {
            return view($path, $data);
        } else {
            $data['includes'] = [$path];
            return view('header', $data) . view('index', $data) . view('footer', $data);
        }
    }

    public function assign()
    {
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'test_id'    => 'required|numeric',
                'patient_id' => 'required|numeric',
                'no_of_item' => 'required|numeric',
                'total_cost' => 'required|numeric'
            ];
            
            if ($this->validate($rules)) {
                $labPatientModel = model('LabPatient');
                $postData = $this->request->getPost();
                unset($postData['submit']);
                
                foreach ($postData as $key => $value) {
                    $labPatientModel->$key = $value;
                }
                $labPatientModel->user_id_assign = session()->get('ba_user_id');
                $labPatientModel->assign_date = time();
                $labPatientModel->save();
                
                $labModel = model('Lab');
                $labModel->load($labPatientModel->test_id);
                
                echo '<tr id="dpi'.$labPatientModel->lab_patient_id.'"><td class="id"></td>'.
                    '<td>'.$labModel->test_name_en.'</td>'.
                    '<td>'.$labModel->test_name_fa.'</td>'.
                    '<td>'.$labModel->price.'</td>'.
                    '<td>'.$labPatientModel->no_of_item.'</td>'.
                    '<td>'.$labPatientModel->total_cost.'</td>'.
                    '<td class="actions"><a href="#">Delete</a> <a href="#">Pay</a></td></tr>';
                return;
            }
        }
    }
  
    public function payment($lab_patient_id)
    {
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'lab_patient_id' => 'required|numeric',
                'test_id'        => 'required|numeric',
                'patient_id'     => 'required|numeric'
            ];
            
            $postData = $this->request->getPost();
            if ($this->validate($rules) && $postData['lab_patient_id'] == $lab_patient_id) {
                $labPatientModel = model('LabPatient');
                $labPatientModel->load($postData['lab_patient_id']);
                
                if ($labPatientModel->test_id == $postData['test_id'] &&
                    $labPatientModel->patient_id == $postData['patient_id'] &&
                    $labPatientModel->user_id_discharge == NULL &&
                    $labPatientModel->discharge_date == NULL) 
                {
                    $labPatientModel->user_id_discharge = session()->get('ba_user_id');
                    $labPatientModel->discharge_date = time();
                    $labPatientModel->save();
                    echo 'ok';
                } else {
                    echo 'mismatch';
                }
            } else {
                echo 'invalid';
            }
        }
    }

    public function deletedpi($lab_patient_id)
    {
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'lab_patient_id' => 'required|numeric',
                'test_id'        => 'required|numeric',
                'patient_id'     => 'required|numeric'
            ];
            
            $postData = $this->request->getPost();
            if ($this->validate($rules) && $postData['lab_patient_id'] == $lab_patient_id) {
                $labPatientModel = model('LabPatient');
                $labPatientModel->load($postData['lab_patient_id']);
                
                if ($labPatientModel->test_id == $postData['test_id'] &&
                    $labPatientModel->patient_id == $postData['patient_id'] &&
                    $labPatientModel->user_id_discharge == NULL &&
                    $labPatientModel->discharge_date == NULL) 
                {
                    $labPatientModel->delete();
                    echo 'ok';
                } else {
                    echo 'mismatch';
                }
            } else {
                echo 'invalid';
            }
        }
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

    private function _labBaseQuery()
    {
        $db = \Config\Database::connect();

        return $db->table('lab_requests lr')
            ->select("
                lr.*,
                pv.queue_number,
                pasien.first_name AS patient_first_name,
                pasien.last_name AS patient_last_name,
                dokter.first_name AS doctor_first_name,
                dokter.last_name AS doctor_last_name,
                si.item_name AS test_name
            ")
            ->join('patient_visits pv', 'pv.visit_id = lr.visit_id', 'left')
            ->join('userdata pasien', 'pasien.user_id = lr.patient_id', 'left')
            ->join('userdata dokter', 'dokter.user_id = lr.doctor_id', 'left')
            ->join('service_items si', 'si.item_id = lr.test_id AND si.item_type = "LAB"', 'left');
    }

    private function _getLabRequest($request_id)
    {
        return $this->_labBaseQuery()
            ->where('lr.request_id', $request_id)
            ->get()
            ->getRow();
    }

    private function _syncLabResultToMedicalRecord($request)
    {
        $db = \Config\Database::connect();

        if (!$request || empty($request->visit_id) || empty($request->test_id)) {
            return;
        }

        $visitItem = $db->table('visit_items')
            ->where('visit_id', $request->visit_id)
            ->where('item_id', $request->test_id)
            ->where('item_type', 'LAB')
            ->get()
            ->getRow();

        if ($visitItem) {
            $db->table('visit_items')
                ->where('visit_item_id', $visitItem->visit_item_id)
                ->update([
                    'note' => $request->result_note
                ]);

            $db->table('medical_record_details')
                ->where('visit_item_id', $visitItem->visit_item_id)
                ->update([
                    'result_note' => $request->result_note
                ]);
        }
    }

    public function queue()
    {
        $check = $this->_check_access();
        if ($check !== true) return $check;

        $data['requests'] = $this->_labBaseQuery()
            ->where('lr.status', 'Pending')
            ->orderBy('lr.created_at', 'ASC')
            ->get()
            ->getResult();

        $data['title'] = 'Daftar Antrean Pasien Lab';
        $data['includes'] = ['lab/queue'];

        return view('header', $data)
            . view('index', $data)
            . view('footer', $data);
    }

    public function results()
    {
        $check = $this->_check_access();
        if ($check !== true) return $check;

        $data['results'] = $this->_labBaseQuery()
            ->where('lr.status', 'Selesai')
            ->orderBy('lr.completed_at', 'DESC')
            ->get()
            ->getResult();

        $data['title'] = 'Lihat Daftar Hasil Lab';
        $data['includes'] = ['lab/results_list'];

        return view('header', $data)
            . view('index', $data)
            . view('footer', $data);
    }

    public function input_result($request_id = null)
    {
        $check = $this->_check_access();
        if ($check !== true) return $check;

        $db = \Config\Database::connect();

        /*
        * MODE 1:
        * Kalau masuk dari menu utama test/input_result,
        * tampilkan pencarian pasien dulu.
        * Setelah pasien dipilih, form hasil muncul di bawah.
        */
        if (empty($request_id)) {
            $requests = $this->_labBaseQuery()
                ->whereIn('lr.status', ['Pending', 'Menunggu'])
                ->orderBy('lr.created_at', 'ASC')
                ->get()
                ->getResult();

            $data['title'] = 'Input Hasil Lab';
            $data['request'] = null;
            $data['requests'] = $requests;
            $data['formAction'] = '';
            $data['buttonText'] = 'Simpan Hasil Lab';
            $data['includes'] = ['lab/input_result'];

            return view('header', $data)
                . view('index', $data)
                . view('footer', $data);
        }

        /*
        * MODE 2:
        * Kalau masuk dari tombol daftar antrean,
        * langsung buka form pasien yang dipilih.
        */
        $request = $this->_getLabRequest($request_id);

        if (!$request) {
            return redirect()->to('test/queue')
                ->with('error', 'Data permintaan lab tidak ditemukan.');
        }

        if ($this->request->is('post')) {
            $resultNote = trim($this->request->getPost('result_note'));
            $proofNote  = trim($this->request->getPost('proof_note'));

            if ($resultNote === '') {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Hasil lab wajib diisi.');
            }

            try {
                $proofFile = $this->_uploadLabProof($request->proof_file ?? null);
            } catch (\RuntimeException $e) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', $e->getMessage());
            }

            $db->table('lab_requests')
                ->where('request_id', $request_id)
                ->update([
                    'result_note'  => $resultNote,
                    'proof_note'   => $proofNote,
                    'proof_file'   => $proofFile,
                    'status'       => 'Selesai',
                    'completed_at' => time()
                ]);

            $updatedRequest = $this->_getLabRequest($request_id);
            $this->_syncLabResultToMedicalRecord($updatedRequest);

            return redirect()->to('test/results')
                ->with('message', 'Hasil lab berhasil disimpan.');
        }

        $data['title'] = 'Input Hasil Lab';
        $data['request'] = $request;
        $data['requests'] = [];
        $data['formAction'] = base_url('test/input_result/' . $request_id);
        $data['buttonText'] = 'Simpan Hasil Lab';
        $data['includes'] = ['lab/input_result'];

        return view('header', $data)
            . view('index', $data)
            . view('footer', $data);
    }

    public function edit_result($request_id)
    {
        $check = $this->_check_access();
        if ($check !== true) return $check;

        $db = \Config\Database::connect();

        $request = $this->_getLabRequest($request_id);

        if (!$request) {
            return redirect()->to('test/results')
                ->with('error', 'Data hasil lab tidak ditemukan.');
        }

        if ($this->request->is('post')) {
            $resultNote = trim($this->request->getPost('result_note'));
            $proofNote  = trim($this->request->getPost('proof_note'));

            if ($resultNote === '') {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Hasil lab wajib diisi.');
            }

            try {
                $proofFile = $this->_uploadLabProof($request->proof_file ?? null);
            } catch (\RuntimeException $e) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', $e->getMessage());
            }

            $db->table('lab_requests')
                ->where('request_id', $request_id)
                ->update([
                    'result_note'  => $resultNote,
                    'proof_note'   => $proofNote,
                    'proof_file'   => $proofFile,
                    'status'       => 'Selesai',
                    'completed_at' => time()
                ]);

            $updatedRequest = $this->_getLabRequest($request_id);
            $this->_syncLabResultToMedicalRecord($updatedRequest);

            return redirect()->to('test/results')
                ->with('message', 'Hasil lab berhasil diperbarui.');
        }

        $data['title'] = 'Ubah Detail Lab';
        $data['request'] = $request;
        $data['requests'] = [];
        $data['formAction'] = base_url('test/edit_result/' . $request_id);
        $data['buttonText'] = 'Update Hasil Lab';
        $data['includes'] = ['lab/input_result'];

        return view('header', $data)
            . view('index', $data)
            . view('footer', $data);
    }

    public function delete_result($request_id)
    {
        $check = $this->_check_access();
        if ($check !== true) return $check;

        $db = \Config\Database::connect();

        $db->table('lab_requests')
            ->where('request_id', $request_id)
            ->delete();

        return redirect()->to('test/results')
            ->with('message', 'Hasil lab berhasil dihapus.');
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