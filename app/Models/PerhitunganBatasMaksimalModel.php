<?php

namespace App\Models;

use CodeIgniter\Model;

class PerhitunganBatasMaksimalModel extends Model
{
    protected $table            = 'p_batasmaksimal';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['pengukuran_id', 'batas_maksimal'];
    protected $returnType       = 'array';
    protected $useTimestamps    = false;

    /**
     * Ambil semua data batas maksimal dengan join ke t_data_pengukuran
     */
    public function getAllWithPengukuran()
    {
        return $this->select('p_batasmaksimal.*, t_data_pengukuran.*')
                    ->join('t_data_pengukuran', 't_data_pengukuran.id = p_batasmaksimal.pengukuran_id')
                    ->findAll();
    }

    /**
     * Ambil data berdasarkan pengukuran_id
     */
    public function getByPengukuranId($pengukuranId)
    {
        return $this->where('pengukuran_id', $pengukuranId)->first();
    }

    /**
     * Tambah data batas maksimal
     */
    public function insertBatasMaksimal($data)
    {
        return $this->insert($data);
    }

    /**
     * Update data batas maksimal
     */
    public function updateBatasMaksimal($id, $data)
    {
        return $this->update($id, $data);
    }

    /**
     * Hapus data batas maksimal
     */
    public function deleteBatasMaksimal($id)
    {
        return $this->delete($id);
    }
}
