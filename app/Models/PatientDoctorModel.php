<?php
namespace App\Models;

class PatientDoctorModel extends MyBaseModel
{
    protected $table = 'patient_doctor';
    protected $primaryKey = 'patient_doctor_id';
    
    protected $allowedFields = [
        'patient_id', 'user_id', 'visit_date', 'status'
    ];

    public function get_waiting($doctor_id = 0) 
    {
        $db = \Config\Database::connect();
        $builder = $db->table('patient_doctor pd');
        
        $builder->select('
            pd.patient_doctor_id,
            pd.patient_id,
            pd.user_id,
            pd.visit_date,
            pd.status,
            p.first_name,
            p.last_name,
            p.fname,
            p.phone,
            p.birth_date,
            p.gender,
            ud.first_name AS doc_first_name,
            ud.last_name AS doc_last_name
        ');
        
        $builder->join('patients p', 'p.patient_id = pd.patient_id');
        $builder->join('userdata ud', 'ud.user_id = pd.user_id', 'left');
        $builder->where('pd.status <', 2);
        
        if ($doctor_id) {
            $builder->where('pd.user_id', $doctor_id);
        }
        
        $builder->orderBy('pd.patient_doctor_id', 'asc');
        
        return $builder->get()->getResult();
    }
}