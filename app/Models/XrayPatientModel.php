<?php
namespace App\Models;

class XrayPatientModel extends MyBaseModel
{
    protected $table = 'xray_patient';
    protected $primaryKey = 'xray_patient_id';
    
    // Sesuai dengan clinic.sql tabel xray_patient
    protected $allowedFields = [
        'xray_id', 'patient_id', 'user_id_assign', 'assign_date', 
        'no_of_item', 'total_cost', 'user_id_discharge', 'discharge_date', 'memo'
    ];
}