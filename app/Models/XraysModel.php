<?php
namespace App\Models;

class XraysModel extends MyBaseModel
{
    protected $table = 'xrays';
    protected $primaryKey = 'xray_id';
    
    // Typo 'catagory' sudah diperbaiki menjadi 'category' sesuai SQL
    protected $allowedFields = [
        'xray_name_en', 'xray_name_fa', 'category', 'price', 'memo'
    ];
}