<?php
namespace App\Models;

class XrayFilesModel extends MyBaseModel
{
    protected $table = 'xray_files';
    protected $primaryKey = 'xray_file_id'; 
    
    // Sesuai dengan clinic.sql tabel xray_files
    protected $allowedFields = [
        'xray_patient_id', 'upload_date', 'path', 'memo'
    ];
}