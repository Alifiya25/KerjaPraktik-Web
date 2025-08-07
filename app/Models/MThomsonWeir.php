<?php
namespace App\Models;
use CodeIgniter\Model;

class MThomsonWeir extends Model
{
    protected $table = 't_thomson_weir';
    protected $primaryKey = 'id';
    protected $allowedFields = ['pengukuran_id', 'a1_r', 'a1_l', 'b1', 'b3', 'b5'];
}
