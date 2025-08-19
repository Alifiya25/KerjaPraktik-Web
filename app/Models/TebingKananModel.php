<?php

namespace App\Models;

use CodeIgniter\Model;

class TebingKananModel extends Model
{
    protected $table      = 'p_tebingkanan';
    protected $primaryKey = 'id';
    protected $allowedFields = ['pengukuran_id', 'sr', 'ambang', 'B5', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
