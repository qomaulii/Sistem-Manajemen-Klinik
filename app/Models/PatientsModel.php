<?php

namespace App\Models;

class PatientsModel extends MyBaseModel
{
    // Nama tabel di database
    protected $table = 'patients'; 
    // Nama primary key
    protected $primaryKey = 'patient_id'; 
    
    // Ini PENTING di CI4: tuliskan semua nama kolom tabel yang boleh diisi datanya
    protected $allowedFields = [
        'first_name', 'last_name', 'fname', 'gender', 'email', 
        'phone', 'address', 'social_id', 'id_type', 'memo', 
        'birth_date', 'create_date', 'picture'
    ];
}