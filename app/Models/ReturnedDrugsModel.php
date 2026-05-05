<?php
namespace App\Models;

class ReturnedDrugsModel extends MyBaseModel
{
    protected $table = 'returned_drugs';
    protected $primaryKey = 'returned_drug_id'; 
    
    // Sesuai dengan clinic.sql tabel returned_drugs
    protected $allowedFields = [
        'drug_id', 'user_id', 'return_date', 'no_of_item', 'total_cost', 'memo'
    ];
}