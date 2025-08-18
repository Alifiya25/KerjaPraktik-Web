<?php
namespace App\Models;
use CodeIgniter\Model;

class PerhitunganIntiGaleryModel extends Model
{
    protected $table = 'p_intigalery';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'pengukuran_id', 'a1', 'ambang_a1', 'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;
}
