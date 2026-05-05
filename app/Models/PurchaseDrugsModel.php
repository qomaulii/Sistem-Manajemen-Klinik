<?php
namespace App\Models;

class PurchasedDrugsModel extends MyBaseModel
{
    protected $table = 'purchased_drugs';
    protected $primaryKey = 'purchased_drug_id'; 
    
    protected $allowedFields = [
        'drug_id', 'purchase_date', 'purchase_price', 'no_of_item', 
        'total_cost', 'memo', 'user_id'
    ];
}