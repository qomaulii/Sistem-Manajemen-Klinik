<?php
namespace App\Models;

class CommentsModel extends MyBaseModel
{
    protected $table = 'comments';
    protected $primaryKey = 'comment_id';
    
    protected $allowedFields = [
        'patient_doctor_id', 'comment', 'create_date', 'last_edit_time'
    ];
}