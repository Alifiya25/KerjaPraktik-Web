<?php
namespace App\Models;
use CodeIgniter\Model;

class MAmbangBatas extends Model
{
    protected $table = 't_ambang_batas';
    protected $primaryKey = 'id';
    protected $allowedFields = ['tma', 'ambang_a1', 'ambang_b3', 'ambang_sr', 'ambang_b5'];
}
