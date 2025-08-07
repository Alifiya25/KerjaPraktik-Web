<?php

namespace App\Models;

use CodeIgniter\Model;

class PerhitunganThomsonModel extends Model
{
    protected $table = 'perhitungan_q_thomson';
    protected $primaryKey = 'id';
    protected $allowedFields = ['a1_r', 'a1_l', 'b1', 'b3', 'b5', 'pengukuran_id'];

    public function getAllWithPengukuran()
    {
        return $this->select('perhitungan_q_thomson.*, t_data_pengukuran.nama AS nama_pengukuran')
                    ->join('t_data_pengukuran', 't_data_pengukuran.id = perhitungan_q_thomson.pengukuran_id')
                    ->findAll();
    }
}
