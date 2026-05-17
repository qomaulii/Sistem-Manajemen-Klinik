<?php

namespace App\Models;

// Jika MyBaseModel ada di folder yang sama, CI4 otomatis mengenalinya.
// Tapi kalau error "Class MyBaseModel not found", ubah 'extends MyBaseModel' menjadi 'extends Model' 
// dan tambahkan 'use CodeIgniter\Model;' di bawah namespace.

class LabModel extends MyBaseModel
{
    protected $table = 'lab';
    protected $primaryKey = 'test_id';
    
    // Typo 'catagory' diperbaiki menjadi 'category'
    protected $allowedFields = [
        'test_name_en', 'test_name_fa', 'category', 'price', 'memo'
    ];
    
    // (Opsional) Tambahkan ini jika kamu tidak punya kolom created_at / updated_at di tabel 'lab'
    protected $useTimestamps = false; 
}