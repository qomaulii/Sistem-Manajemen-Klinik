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
        $labModel = model('Lab');
        
        $data['tests'] = $labModel->get();
        $data['title'] = 'Tests List';
        $data['navActiveId'] = 'navbarLiLab';
        $data['page'] = (int)$page;
        $data['per_page'] = (int)$limit;
        $data['pagination'] = ""; 
        
        $path = 'lab/list';
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