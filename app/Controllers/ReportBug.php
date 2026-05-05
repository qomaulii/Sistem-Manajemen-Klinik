<?php

namespace App\Controllers;

class ReportBug extends BaseController 
{
    public function __construct()
    {
        helper(['url', 'form', 'date']);
    }
  
    public function index($patient_id = 0, $page = 1, $limit = 15)
    {
        // Asumsi autentikasi bitauth
        // if (!session()->has('ba_user_id')) {
        //     session()->set('redir', current_url());
        //     return redirect()->to('account/login');
        // }
    }
  
    public function add()
    {
        /*
        if (!session()->has('ba_user_id')) {
            session()->set('redir', current_url());
            return redirect()->to('account/login');
        }
        */

        $data = [];
        $reportsModel = model('Reports');

        if ($this->request->getMethod() === 'post') {
            // permit_empty berarti boleh kosong (karena di kode lamamu rules-nya kosong)
            $rules = [
                'subject'     => 'permit_empty',
                'url'         => 'permit_empty',
                'description' => 'permit_empty',
            ];

            if ($this->validate($rules)) {
                $postData = $this->request->getPost();
                
                $reportsModel->user_id = session()->get('ba_user_id');
                $reportsModel->subject = $postData['subject'];
                $reportsModel->url = $postData['url']; // Bug di kodemu yang lama sudah saya perbaiki di sini
                $reportsModel->description = $postData['description'];
                $reportsModel->create_date = time();
                $reportsModel->save();
            }
        }

        if (isset($reportsModel->report_id) && $reportsModel->report_id) {
            $data['report'] = $reportsModel;
        }

        return view('report/add', $data);
    }
}