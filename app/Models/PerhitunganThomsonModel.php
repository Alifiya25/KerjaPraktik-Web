<?php

namespace App\Models;

use CodeIgniter\Model;

class PerhitunganThomsonModel extends Model
{
    protected $table = 'p_thomson_weir';
    protected $primaryKey = 'id';
    protected $allowedFields = ['a1_r', 'a1_l', 'b1', 'b3', 'b5', 'pengukuran_id'];
    protected $useTimestamps = true;

    /**
     * Ambil semua data Thomson dengan join data pengukuran
     */
    public function getAllWithPengukuran()
    {
        return $this->select("p_thomson_weir.*, CONCAT(t_data_pengukuran.tanggal, ' - ', t_data_pengukuran.periode) AS nama_pengukuran")
                    ->join('t_data_pengukuran', 't_data_pengukuran.id = p_thomson_weir.pengukuran_id')
                    ->orderBy('p_thomson_weir.id', 'DESC')
                    ->findAll();
    }

    /**
     * Ambil data berdasarkan ID
     */
    public function getById($id)
    {
        return $this->select("p_thomson_weir.*, CONCAT(t_data_pengukuran.tanggal, ' - ', t_data_pengukuran.periode) AS nama_pengukuran")
                    ->join('t_data_pengukuran', 't_data_pengukuran.id = p_thomson_weir.pengukuran_id')
                    ->where('p_thomson_weir.id', $id)
                    ->first();
    }

    /**
     * Ambil data berdasarkan ID pengukuran
     */
    public function getByPengukuranId($pengukuranId)
    {
        return $this->where('pengukuran_id', $pengukuranId)->findAll();
    }

    /**
     * Tambah data baru
     */
    public function tambahData($data)
    {
        return $this->insert($data);
    }

    /**
     * Update data berdasarkan ID
     */
    public function updateData($id, $data)
    {
        return $this->update($id, $data);
    }

    /**
     * Hapus data berdasarkan ID
     */
    public function hapusData($id)
    {
        return $this->delete($id);
    }

    /**
     * Insert batch (multiple data sekaligus)
     */
    public function insertBatchData($data)
    {
        return $this->insertBatch($data);
    }
}
