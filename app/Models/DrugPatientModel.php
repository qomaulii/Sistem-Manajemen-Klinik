<?php
namespace App\Models;

class DrugPatientModel extends MyBaseModel
{
    protected $table = 'drug_patient';
    protected $primaryKey = 'drug_patient_id';
    
    protected $allowedFields = [
        'drug_id', 'patient_id', 'no_of_item', 'total_cost', 'memo', 
        'user_id_assign', 'assign_date', 'user_id_discharge', 'discharge_date'
    ];

    // Fungsi khusus untuk menghitung obat terjual
    public function get_sold($drug_id) 
    {
        return $this->builder()
                    ->where('drug_id', $drug_id)
                    ->where('user_id_discharge IS NOT NULL')
                    ->get()->getResult();
    }
}