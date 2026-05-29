public function medical_history_detail($patient_id)
{
    $this->_check_access();
    $db = \Config\Database::connect();
    
    $data['patient'] = $db->table('userdata')
        ->where('user_id', $patient_id)
        ->get()
        ->getRow();

    $history = $db->table('medical_records mr')
        ->select('mr.*, ud.first_name as doc_first, ud.last_name as doc_last')
        ->join('userdata ud', 'mr.doctor_id = ud.user_id', 'left')
        ->where('mr.patient_id', $patient_id)
        ->orderBy('mr.created_at', 'DESC')
        ->get()
        ->getResult();

    foreach ($history as $h) {
        $h->details = $db->table('medical_record_details')
            ->where('record_id', $h->record_id)
            ->orderBy('created_at', 'ASC')
            ->get()
            ->getResult();
    }

    $data['history'] = $history;
    $data['title'] = 'Detail Riwayat Medis';
    $data['includes'] = ['doctor/medical_history_detail'];

    return view('header', $data)
         . view('index', $data)
         . view('footer', $data);
}