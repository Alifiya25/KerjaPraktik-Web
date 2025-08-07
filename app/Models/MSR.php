<?php
namespace App\Models;
use CodeIgniter\Model;

class MSR extends Model
{
    protected $table = 't_sr';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'pengukuran_id',
        'sr_1_kode', 'sr_1_nilai', 'sr_40_kode', 'sr_40_nilai', 'sr_66_kode', 'sr_66_nilai',
        'sr_68_kode', 'sr_68_nilai', 'sr_70_kode', 'sr_70_nilai', 'sr_79_kode', 'sr_79_nilai',
        'sr_81_kode', 'sr_81_nilai', 'sr_83_kode', 'sr_83_nilai', 'sr_85_kode', 'sr_85_nilai',
        'sr_92_kode', 'sr_92_nilai', 'sr_94_kode', 'sr_94_nilai', 'sr_96_kode', 'sr_96_nilai',
        'sr_98_kode', 'sr_98_nilai', 'sr_100_kode', 'sr_100_nilai', 'sr_102_kode', 'sr_102_nilai',
        'sr_104_kode', 'sr_104_nilai', 'sr_106_kode', 'sr_106_nilai'
    ];
}
