<?php
namespace App\Models;

class DrugsModel extends MyBaseModel
{
    protected $table = 'drugs'; 
    protected $primaryKey = 'drug_id'; 
    
    // Typo 'catagory' diperbaiki dan kolom 'num' ditambahkan sesuai SQL
    protected $allowedFields = [
        'drug_name_en', 'drug_name_fa', 'category', 'price', 'num', 'memo'
    ];
}