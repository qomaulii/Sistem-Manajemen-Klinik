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