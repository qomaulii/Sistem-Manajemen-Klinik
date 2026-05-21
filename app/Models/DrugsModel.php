<?php

namespace App\Models;

use CodeIgniter\Model;

class DrugsModel extends Model
{
    protected $table = 'drugs'; 
    protected $primaryKey = 'drug_id'; 
    protected $allowedFields = ['drug_name_en', 'drug_name_fa', 'category', 'price', 'num', 'memo'];
}