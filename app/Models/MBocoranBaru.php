<?php
namespace App\Models;
use CodeIgniter\Model;

class MBocoranBaru extends Model
{
    protected $table = 't_bocoran_baru';
    protected $primaryKey = 'id';
    protected $allowedFields = ['pengukuran_id', 'elv_624_t1', 'elv_615_t2', 'pipa_p1'];
}
