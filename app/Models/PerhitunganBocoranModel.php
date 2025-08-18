<?php

namespace App\Models;

use CodeIgniter\Model;

class PerhitunganBocoranModel extends Model
{
    protected $table      = 'p_bocoran_baru';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'pengukuran_id',
        'talang1',
        'talang2',
        'pipa'
    ];
    protected $useTimestamps = true;
}
