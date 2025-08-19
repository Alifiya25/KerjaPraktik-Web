<?php

namespace App\Models;

use CodeIgniter\Model;

class TotalBocoranModel extends Model
{
    protected $table      = 'p_totalbocoran';
    protected $primaryKey = 'id';
    protected $allowedFields = ['pengukuran_id', 'R1', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
