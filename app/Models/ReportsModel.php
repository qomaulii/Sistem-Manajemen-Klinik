<?php
namespace App\Models;

class ReportsModel extends MyBaseModel
{
    protected $table = 'reports';
    protected $primaryKey = 'report_id'; 
    
    protected $allowedFields = [
        'user_id', 'subject', 'url', 'description', 'create_date'
    ];
}