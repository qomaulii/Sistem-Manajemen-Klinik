<?php
namespace App\Models;

class LabModel extends MyBaseModel
{
    protected $table = 'lab';
    protected $primaryKey = 'test_id';
    
    // Typo 'catagory' diperbaiki menjadi 'category'
    protected $allowedFields = [
        'test_name_en', 'test_name_fa', 'category', 'price', 'memo'
    ];
}