<?php

namespace App\Controllers;

class Xray extends BaseController
{
    public function __construct()
    {
        helper(['url', 'form', 'date']);
    }

    public function index($limit = 15, $page = 1)
    {
        $xraysModel = model('XraysModel'); // Sesuaikan dengan nama file model
        $data['xrays'] = $xraysModel->findAll(); // Gunakan findAll() untuk standar CI4
        $data['title'] = 'Xray List';
        $data['navActiveId'] = 'navbarLiXray';
        $data['page'] = (int)$page;
        $data['per_page'] = (int)$limit;
        $data['pagination'] = ""; // Pagination CI4
        
        $path = 'xray/list';
        if ($this->request->getGet('ajax')) {
            return view($path, $data);
        } else {
            $data['includes'] = [$path];
            return view('header', $data) . view('index', $data) . view('footer', $data);
        }
    }
  
    public function search()
    {
        if ($this->request->getMethod() === 'post') {
            $xraysModel = model('Xrays');
            $q = $this->request->getPost('q');
            
            $data['xrays'] = $xraysModel->search(['xray_name_en' => $q, 'xray_name_fa' => $q]);
            return view('xray/result', $data);
        }
        $data['title'] = 'Xray Search';
        return view('xray/search', $data);
    }
  
    public function edit($xray_id = 0)
    {
        $xraysModel = model('Xrays');
        $xraysModel->load($xray_id);
        
        $data = [];
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'xray_name_en' => 'required',
                'xray_name_fa' => 'required',
                'price'        => 'required'
            ];
            
            if ($this->validate($rules)) {
                $session_check = session()->get(current_url());
                session()->remove(current_url());
                
                if ($session_check && $session_check[0] == $xray_id) {
                    $postData = $this->request->getPost();
                    unset($postData['submit']);
                    
                    foreach ($postData as $key => $value) {
                        $xraysModel->$key = $value;
                    }
                    $xraysModel->save();
                    
                    $data['script'] = '<script>alert("'. esc($xraysModel->xray_name_en). ' has been updated successfuly.");</script>';
                    return redirect()->to('xray');
                } else {
                    $data['error'] = '<div class="alert alert-danger">Form URL Error</div>';
                }
            } else {
                $data['error'] = $this->validator->listErrors();
            }
        }
        
        session()->set(current_url(), [$xray_id]);
        $data['title'] = 'Edit Xray';
        $data['xray'] = $xraysModel;
        
        $path = 'xray/edit';
        if ($this->request->getGet('ajax')) {
            return view($path, $data);
        } else {
            $data['includes'] = [$path];
            return view('header', $data) . view('index', $data) . view('footer', $data);
        }
    }

    public function delete($xray_id = 0)
    {
        $xraysModel = model('Xrays');
        $xraysModel->load($xray_id);
        
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'xray_id' => 'required',
                'del'     => 'required'
            ];
            
            if ($this->validate($rules) && $this->request->getPost('xray_id') == $xray_id) {
                $session_check = session()->get(current_url());
                session()->remove(current_url());
                
                if ($session_check && $session_check[0] == $xray_id) {
                    $xrayPatientModel = model('XrayPatient');
                    $xrayPatientModel->get_by_fkey('xray_id', $xray_id);
                    
                    if (!$xrayPatientModel->xray_patient_id) {
                        $xraysModel->delete();
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
        
        session()->set(current_url(), [$xray_id]);
        $data['xray'] = $xraysModel;
        return view('xray/confirm_delete', $data);
    }
  
    public function new_xray()
    {
        $data = [];
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'xray_name_en' => 'required',
                'xray_name_fa' => 'required',
                'price'        => 'required'
            ];
            
            if ($this->validate($rules)) {
                $xraysModel = model('Xrays');
                $postData = $this->request->getPost();
                unset($postData['submit']);
                
                foreach ($postData as $key => $value) {
                    $xraysModel->$key = $value;
                }
                $xraysModel->save();
                $data['script'] = '<script>alert("'. esc($xraysModel->xray_name_en). ' has been registered successfuly.");</script>';
            } else {
                $data['error'] = $this->validator->listErrors();
            }
        }
        
        $data['title'] = 'New Xray';
        $path = 'xray/new';
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
                'xray_id'    => 'required|numeric',
                'patient_id' => 'required|numeric',
                'no_of_item' => 'required|numeric',
                'total_cost' => 'required|numeric'
            ];
            
            if ($this->validate($rules)) {
                $xrayPatientModel = model('XrayPatient');
                $postData = $this->request->getPost();
                unset($postData['submit']);
                
                foreach ($postData as $key => $value) {
                    $xrayPatientModel->$key = $value;
                }
                $xrayPatientModel->user_id_assign = session()->get('ba_user_id');
                $xrayPatientModel->assign_date = time();
                $xrayPatientModel->save();
                
                $xraysModel = model('Xrays');
                $xraysModel->load($xrayPatientModel->xray_id);
                
                echo '<tr id="dpi'.$xrayPatientModel->xray_patient_id.'"><td class="id"></td>'.
                    '<td>'.$xraysModel->xray_name_en.'</td>'.
                    '<td>'.$xraysModel->xray_name_fa.'</td>'.
                    '<td>'.$xraysModel->price.'</td>'.
                    '<td>'.$xrayPatientModel->no_of_item.'</td>'.
                    '<td>'.$xrayPatientModel->total_cost.'</td>'.
                    '<td class="actions"><a href="#">Delete</a> <a href="#">Pay</a></td></tr>';
                return;
            }
        }
    }
  
    public function payment($xray_patient_id)
    {
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'xray_patient_id' => 'required|numeric',
                'xray_id'         => 'required|numeric',
                'patient_id'      => 'required|numeric'
            ];
            
            $postData = $this->request->getPost();
            if ($this->validate($rules) && $postData['xray_patient_id'] == $xray_patient_id) {
                $xrayPatientModel = model('XrayPatient');
                $xrayPatientModel->load($postData['xray_patient_id']);
                
                if ($xrayPatientModel->xray_id == $postData['xray_id'] &&
                    $xrayPatientModel->patient_id == $postData['patient_id'] &&
                    $xrayPatientModel->user_id_discharge == NULL &&
                    $xrayPatientModel->discharge_date == NULL) 
                {
                    $xrayPatientModel->user_id_discharge = session()->get('ba_user_id');
                    $xrayPatientModel->discharge_date = time();
                    $xrayPatientModel->save();
                    echo 'ok';
                } else {
                    echo 'mismatch';
                }
            } else {
                echo 'invalid';
            }
        }
    }

    public function results()
    {
        $check = $this->_check_access();
        if ($check !== true) return $check;

        $data['results'] = $this->_xrayBaseQuery()
            ->where('xr.status', 'Selesai')
            ->orderBy('xr.completed_at', 'DESC')
            ->get()
            ->getResult();

        $data['title'] = 'Lihat Daftar X-Ray';
        $data['includes'] = ['xray/results_list'];

        return view('header', $data)
            . view('index', $data)
            . view('footer', $data);
    }

    public function deletedpi($xray_patient_id)
    {
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'xray_patient_id' => 'required|numeric',
                'xray_id'         => 'required|numeric',
                'patient_id'      => 'required|numeric'
            ];
            
            $postData = $this->request->getPost();
            if ($this->validate($rules) && $postData['xray_patient_id'] == $xray_patient_id) {
                $xrayPatientModel = model('XrayPatient');
                $xrayPatientModel->load($postData['xray_patient_id']);
                
                if ($xrayPatientModel->xray_id == $postData['xray_id'] &&
                    $xrayPatientModel->patient_id == $postData['patient_id'] &&
                    $xrayPatientModel->user_id_discharge == NULL &&
                    $xrayPatientModel->discharge_date == NULL) 
                {
                    $xrayPatientModel->delete();
                    echo 'ok';
                } else {
                    echo 'mismatch';
                }
            } else {
                echo 'invalid';
            }
        }
    }

    public function details($xray_patient_id)
    {
        $xrayFilesModel = model('XrayFiles');
        $data = [];
        
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'xray_patient_id' => 'required|numeric',
                // 'memo' dibiarkan opsional
            ];
            
            if ($this->validate($rules)) {
                // Upload Gambar Xray menggunakan gaya CI4
                $picture = $this->request->getFile('picture');
                
                if ($picture && $picture->isValid() && !$picture->hasMoved()) {
                    $xrayPatientModel = model('XrayPatient');
                    $xrayPatientModel->load($xray_patient_id);
                    
                    $path = 'uploads/patient/' . $xrayPatientModel->patient_id . '/xray/';
                    $newName = uniqid() . uniqid() . '.' . $picture->getExtension();
                    
                    $picture->move($path, $newName);
                    
                    $xrayFilesModel->xray_patient_id = $xray_patient_id;
                    $xrayFilesModel->upload_date = time();
                    $xrayFilesModel->path = $path . $newName;
                    $xrayFilesModel->memo = $this->request->getPost('memo');
                    $xrayFilesModel->save();
                    
                    return redirect()->to('xray/details/' . $xray_patient_id);
                } else {
                    $data['error'] = '<div class="alert alert-danger">Upload gagal.</div>';
                }
            }
        }    
        
        $data['xray_files'] = $xrayFilesModel->get_by_fkey('xray_patient_id', $xray_patient_id, 'asc', null);
        $data['xray_patient_id'] = $xray_patient_id;
        $data['title'] = 'Xray Details';    
        
        return view('xray/details', $data);
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

    private function _xrayBaseQuery()
    {
        $db = \Config\Database::connect();

        return $db->table('xray_requests xr')
            ->select("
                xr.*,
                pv.queue_number,
                pasien.first_name AS patient_first_name,
                pasien.last_name AS patient_last_name,
                dokter.first_name AS doctor_first_name,
                dokter.last_name AS doctor_last_name,
                si.item_name AS xray_name
            ")
            ->join('patient_visits pv', 'pv.visit_id = xr.visit_id', 'left')
            ->join('userdata pasien', 'pasien.user_id = xr.patient_id', 'left')
            ->join('userdata dokter', 'dokter.user_id = xr.doctor_id', 'left')
            ->join('service_items si', 'si.item_id = xr.xray_id AND si.item_type = "XRAY"', 'left');
    }

    private function _getXrayRequest($request_id)
    {
        return $this->_xrayBaseQuery()
            ->where('xr.request_id', $request_id)
            ->get()
            ->getRow();
    }

    private function _syncXrayResultToMedicalRecord($request)
    {
        $db = \Config\Database::connect();

        if (!$request || empty($request->visit_id) || empty($request->xray_id)) {
            return;
        }

        $visitItem = $db->table('visit_items')
            ->where('visit_id', $request->visit_id)
            ->where('item_id', $request->xray_id)
            ->where('item_type', 'XRAY')
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

        $data['requests'] = $this->_xrayBaseQuery()
            ->where('xr.status', 'Pending')
            ->orderBy('xr.created_at', 'ASC')
            ->get()
            ->getResult();

        $data['title'] = 'Daftar Antrean Pasien X-Ray';
        $data['includes'] = ['xray/queue'];

        return view('header', $data)
            . view('index', $data)
            . view('footer', $data);
    }

    private function _uploadXrayProof($oldFile = null)
    {
        $file = $this->request->getFile('proof_file');

        if (!$file || $file->getError() === 4) {
            return $oldFile;
        }

        if (!$file->isValid()) {
            throw new \RuntimeException('File bukti x-ray tidak valid.');
        }

        $allowedExt = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'];
        $ext = strtolower($file->getClientExtension());

        if (!in_array($ext, $allowedExt)) {
            throw new \RuntimeException('Format file harus jpg, jpeg, png, pdf, doc, atau docx.');
        }

        if ($file->getSize() > 5 * 1024 * 1024) {
            throw new \RuntimeException('Ukuran file maksimal 5 MB.');
        }

        $uploadPath = FCPATH . 'uploads/xray_results/';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $newName = $file->getRandomName();
        $file->move($uploadPath, $newName);

        if (!empty($oldFile) && file_exists(FCPATH . $oldFile)) {
            unlink(FCPATH . $oldFile);
        }

        return 'uploads/xray_results/' . $newName;
    }

    public function input_result($request_id = null)
    {
        $check = $this->_check_access();
        if ($check !== true) return $check;

        $db = \Config\Database::connect();

        /*
        * MODE 1:
        * Kalau masuk dari menu utama xray/input_result,
        * request_id masih kosong.
        * Jadi tampilkan form pencarian pasien dulu.
        */
        if (empty($request_id)) {
            $requests = $this->_xrayBaseQuery()
                ->whereIn('xr.status', ['Pending', 'Menunggu'])
                ->orderBy('xr.created_at', 'ASC')
                ->get()
                ->getResult();

            $data['title'] = 'Input Hasil X-Ray';
            $data['request'] = null;
            $data['requests'] = $requests;
            $data['formAction'] = '';
            $data['buttonText'] = 'Lanjut Input Hasil';
            $data['includes'] = ['xray/input_result'];

            return view('header', $data)
                . view('index', $data)
                . view('footer', $data);
        }

        /*
        * MODE 2:
        * Kalau masuk dari daftar antrean,
        * request_id sudah ada.
        * Jadi langsung buka form pasien yang dipilih.
        */
        $request = $this->_getXrayRequest($request_id);

        if (!$request) {
            return redirect()->to('xray/queue')
                ->with('error', 'Data permintaan x-ray tidak ditemukan.');
        }

        if ($this->request->is('post')) {
            $resultNote = trim($this->request->getPost('result_note'));
            $proofNote  = trim($this->request->getPost('proof_note'));

            if ($resultNote === '') {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Hasil x-ray wajib diisi.');
            }

            try {
                $proofFile = $this->_uploadXrayProof($request->proof_file ?? null);
            } catch (\RuntimeException $e) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', $e->getMessage());
            }

            $db->table('xray_requests')
                ->where('request_id', $request_id)
                ->update([
                    'result_note'  => $resultNote,
                    'proof_note'   => $proofNote,
                    'proof_file'   => $proofFile,
                    'status'       => 'Selesai',
                    'completed_at' => time()
                ]);

            $updatedRequest = $this->_getXrayRequest($request_id);
            $this->_syncXrayResultToMedicalRecord($updatedRequest);

            return redirect()->to('xray/results')
                ->with('message', 'Hasil x-ray berhasil disimpan.');
        }

        $data['title'] = 'Input Hasil X-Ray';
        $data['request'] = $request;
        $data['requests'] = [];
        $data['formAction'] = base_url('xray/input_result/' . $request_id);
        $data['buttonText'] = 'Simpan Hasil X-Ray';
        $data['includes'] = ['xray/input_result'];

        return view('header', $data)
            . view('index', $data)
            . view('footer', $data);
    }

    public function edit_result($request_id)
    {
        $check = $this->_check_access();
        if ($check !== true) return $check;

        $db = \Config\Database::connect();

        $request = $this->_getXrayRequest($request_id);

        if (!$request) {
            return redirect()->to('xray/results')
                ->with('error', 'Data hasil x-ray tidak ditemukan.');
        }

        if ($this->request->is('post')) {
            $resultNote = trim($this->request->getPost('result_note'));
            $proofNote  = trim($this->request->getPost('proof_note'));

            if ($resultNote === '') {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Hasil x-ray wajib diisi.');
            }

            try {
                $proofFile = $this->_uploadXrayProof($request->proof_file ?? null);
            } catch (\RuntimeException $e) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', $e->getMessage());
            }

            $db->table('xray_requests')
                ->where('request_id', $request_id)
                ->update([
                    'result_note'  => $resultNote,
                    'proof_note'   => $proofNote,
                    'proof_file'   => $proofFile,
                    'status'       => 'Selesai',
                    'completed_at' => time()
                ]);

            $updatedRequest = $this->_getXrayRequest($request_id);
            $this->_syncXrayResultToMedicalRecord($updatedRequest);

            return redirect()->to('xray/results')
                ->with('message', 'Hasil x-ray berhasil diperbarui.');
        }

        $data['title'] = 'Ubah Detail X-Ray';
        $data['request'] = $request;
        $data['formAction'] = base_url('xray/edit_result/' . $request_id);
        $data['buttonText'] = 'Update Hasil X-Ray';
        $data['includes'] = ['xray/input_result'];

        return view('header', $data)
            . view('index', $data)
            . view('footer', $data);
    }

    public function delete_result($request_id)
    {
        $check = $this->_check_access();
        if ($check !== true) return $check;

        $db = \Config\Database::connect();

        $db->table('xray_requests')
            ->where('request_id', $request_id)
            ->delete();

        return redirect()->to('xray/results')
            ->with('message', 'Hasil x-ray berhasil dihapus.');
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