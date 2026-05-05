<?php
namespace App\Models;

class LabPatientModel extends MyBaseModel
{
    protected $table = 'lab_patient';
    protected $primaryKey = 'lab_patient_id';
    
    protected $allowedFields = [
        'test_id', 'patient_id', 'no_of_item', 'total_cost', 'memo', 
        'user_id_assign', 'assign_date', 'user_id_discharge', 'discharge_date'
    ];
}