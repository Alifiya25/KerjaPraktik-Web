<?php
namespace App\Models;
use CodeIgniter\Model;

class DataGabunganModel extends Model
{
    protected $table = 't_data_pengukuran';
    protected $primaryKey = 'id';

    public function getDataGabungan()
    {
        $builder = $this->db->table('t_data_pengukuran p')
            ->select('p.id as pengukuran_id, 
                      p.tahun, p.bulan, p.periode, p.tanggal, p.tma_waduk, p.curah_hujan,
                      sr.*, 
                      thomson.*, 
                      bocoran.*, 
                      ambang.*')
            ->join('t_sr sr', 'sr.pengukuran_id = p.id', 'left')
            ->join('t_thomson_weir thomson', 'thomson.pengukuran_id = p.id', 'left')
            ->join('t_bocoran_baru bocoran', 'bocoran.pengukuran_id = p.id', 'left')
            ->join('t_ambang_batas ambang', 'ambang.id = p.id', 'left'); // ✅ pakai id karena tabel ini tidak punya pengukuran_id

        $results = $builder->get()->getResultArray();

        $final = [];

        foreach ($results as $row) {
            $final[] = [
                'pengukuran_id' => $row['pengukuran_id'],
                'pengukuran' => [
                    'tahun'       => $row['tahun'],
                    'bulan'       => $row['bulan'],
                    'periode'     => $row['periode'],
                    'tanggal'     => $row['tanggal'],
                    'tma_waduk'   => $row['tma_waduk'],
                    'curah_hujan' => $row['curah_hujan'],
                ],
                'thomson' => [
                    'a1_r' => $row['a1_r'],
                    'a1_l' => $row['a1_l'],
                    'b1'   => $row['b1'],
                    'b3'   => $row['b3'],
                    'b5'   => $row['b5'],
                ],
                'bocoran' => [
                    'elv_624_t1'      => $row['elv_624_t1'],
                    'elv_624_t1_kode' => $row['elv_624_t1_kode'],
                    'elv_615_t2'      => $row['elv_615_t2'],
                    'elv_615_t2_kode' => $row['elv_615_t2_kode'],
                    'pipa_p1'         => $row['pipa_p1'],
                    'pipa_p1_kode'    => $row['pipa_p1_kode'],
                ],
                'sr' => [
                    'sr_1_kode'   => $row['sr_1_kode'],
                    'sr_1_nilai'  => $row['sr_1_nilai'],
                    'sr_40_kode'  => $row['sr_40_kode'],
                    'sr_40_nilai' => $row['sr_40_nilai'],
                    'sr_66_kode'  => $row['sr_66_kode'],
                    'sr_66_nilai' => $row['sr_66_nilai'],
                    'sr_68_kode'  => $row['sr_68_kode'],
                    'sr_68_nilai' => $row['sr_68_nilai'],
                    'sr_70_kode'  => $row['sr_70_kode'],
                    'sr_70_nilai' => $row['sr_70_nilai'],
                    'sr_79_kode'  => $row['sr_79_kode'],
                    'sr_79_nilai' => $row['sr_79_nilai'],
                    'sr_81_kode'  => $row['sr_81_kode'],
                    'sr_81_nilai' => $row['sr_81_nilai'],
                    'sr_83_kode'  => $row['sr_83_kode'],
                    'sr_83_nilai' => $row['sr_83_nilai'],
                    'sr_85_kode'  => $row['sr_85_kode'],
                    'sr_85_nilai' => $row['sr_85_nilai'],
                    'sr_92_kode'  => $row['sr_92_kode'],
                    'sr_92_nilai' => $row['sr_92_nilai'],
                    'sr_94_kode'  => $row['sr_94_kode'],
                    'sr_94_nilai' => $row['sr_94_nilai'],
                    'sr_96_kode'  => $row['sr_96_kode'],
                    'sr_96_nilai' => $row['sr_96_nilai'],
                    'sr_98_kode'  => $row['sr_98_kode'],
                    'sr_98_nilai' => $row['sr_98_nilai'],
                    'sr_100_kode' => $row['sr_100_kode'],
                    'sr_100_nilai'=> $row['sr_100_nilai'],
                    'sr_102_kode' => $row['sr_102_kode'],
                    'sr_102_nilai'=> $row['sr_102_nilai'],
                    'sr_104_kode' => $row['sr_104_kode'],
                    'sr_104_nilai'=> $row['sr_104_nilai'],
                    'sr_106_kode' => $row['sr_106_kode'],
                    'sr_106_nilai'=> $row['sr_106_nilai'],
                ],
                'ambang' => [
                    'tma'        => $row['tma'],
                    'ambang_a1'  => $row['ambang_a1'],
                    'ambang_b3'  => $row['ambang_b3'],
                    'ambang_sr'  => $row['ambang_sr'],
                    'ambang_b5'  => $row['ambang_b5'],
                ]
            ];
        }

        return $final;
    }
}
