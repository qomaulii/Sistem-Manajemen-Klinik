<?php

namespace App\Models;

use CodeIgniter\Model;

class BillingModel extends Model
{
    protected $table            = 'billing';
    protected $primaryKey       = 'bill_id';
    
    // Kolom-kolom yang diizinkan untuk diisi dari form
    protected $allowedFields    = [
        'patient_id', 
        'user_id', 
        'service_details', 
        'total_amount', 
        'payment_method', 
        'payment_status', 
        'create_date', 
        'paid_date'
    ];

    // Karena format waktu di database lama pakai INT (Unix Timestamp), kita matikan timestamps bawaan CI4
    protected $useTimestamps    = false;
}