<?php

namespace App\Models;

use CodeIgniter\Model;

class PerhitunganSpillwayModel extends Model
{
    protected $table = 'p_spillway';
    protected $primaryKey = 'id';
    protected $allowedFields = ['pengukuran_id', 'B3', 'ambang', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
}
