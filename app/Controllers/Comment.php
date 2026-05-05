<?php

namespace App\Controllers;

class Comment extends BaseController
{
    public function __construct()
    {
        helper(['url', 'form', 'date']);
    }
  
    public function index($patient_id = 0, $page = 1, $limit = 15)
    {
        // Kosong di kodingan aslinya. Akan dihandle jika dibutuhkan.
    }
  
    public function add($patient_doctor_id = 0)
    {
        $commentsModel = model('Comments');
        $patientDoctorModel = model('PatientDoctor');
        
        $data = [];
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'patient_doctor_id' => 'required',
                'comment'           => 'required'
            ];
            
            if ($this->validate($rules) && $this->request->getPost('patient_doctor_id') == $patient_doctor_id) {
                $patientDoctorModel->load($patient_doctor_id);
                
                // Pastikan yang memberi komentar adalah dokter yang bersangkutan
                if ($patientDoctorModel->user_id != session()->get('ba_user_id')) {
                    return;
                }
                
                $commentsModel->patient_doctor_id = $this->request->getPost('patient_doctor_id');
                $commentsModel->comment = $this->request->getPost('comment');
                $commentsModel->create_date = time();
                $commentsModel->last_edit_time = time();
                $commentsModel->save();
                
                // Reload comment untuk dikirim ke view
                $commentsModel->load($commentsModel->comment_id);
                $data['comment'] = $commentsModel;
                return view('patient/comment', $data);
            }
        }
    }
  
    public function edit($comment_id = 0)
    {
        $commentsModel = model('Comments');
        $patientDoctorModel = model('PatientDoctor');
        
        $data = [];
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'comment_id'        => 'required|numeric',
                'patient_doctor_id' => 'required|numeric',
                'comment'           => 'required'
            ];
            
            $postData = $this->request->getPost();
            if ($this->validate($rules) && $comment_id == $postData['comment_id']) {
                $commentsModel->load($comment_id);
                $patientDoctorModel->load($commentsModel->patient_doctor_id);
                
                // Validasi kepemilikan dokter
                if ($patientDoctorModel->user_id != session()->get('ba_user_id')) {
                    return;
                }
                
                $commentsModel->comment = $postData['comment'];
                $commentsModel->last_edit_time = time();
                $commentsModel->save();
                
                $data['comment'] = $commentsModel;
                return view('patient/comment', $data);
            }
        }
    }
}