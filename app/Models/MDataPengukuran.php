<?php
namespace App\Models;
use CodeIgniter\Model;

class MDataPengukuran extends Model
{
    protected $table = 't_data_pengukuran';
    protected $primaryKey = 'id';
    protected $allowedFields = ['tahun', 'bulan', 'periode', 'tanggal', 'tma_waduk', 'curah_hujan'];
}
