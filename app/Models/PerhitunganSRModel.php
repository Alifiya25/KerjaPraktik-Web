<?php
namespace App\Models;
use CodeIgniter\Model;

class PerhitunganSRModel extends Model
{
    protected $table = 'p_sr';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'pengukuran_id',
        'sr_1_q', 'sr_40_q', 'sr_66_q', 'sr_68_q', 'sr_70_q',
        'sr_79_q', 'sr_81_q', 'sr_83_q', 'sr_85_q', 'sr_92_q',
        'sr_94_q', 'sr_96_q', 'sr_98_q', 'sr_100_q', 'sr_102_q',
        'sr_104_q', 'sr_106_q',
        'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;
}
