<?php 
namespace App\Controllers;

use App\Models\DataGabunganModel;
use App\Models\MAmbangBatas;
use App\Models\MBocoranBaru;
use App\Models\MDataPengukuran;
use App\Models\MSR;
use App\Models\MThomsonWeir;
use App\Models\PerhitunganBocoranModel;
use App\Models\PerhitunganIntiGaleryModel;
use App\Models\PerhitunganSpillwayModel;
use App\Models\PerhitunganSRModel;
use App\Models\PerhitunganThomsonModel;
use App\Models\PerhitunganBatasMaksimalModel; // ✅ Tambahkan ini
use App\Models\TebingKananModel;
use App\Models\TotalBocoranModel;

class DataInputController extends BaseController
{
    public function rembesan()
    {
        $data = [
            'gabungan'               => (new DataGabunganModel())->findAll(),
            'ambang'                 => (new MAmbangBatas())->findAll(),
            'bocoran'                => (new MBocoranBaru())->findAll(),
            'pengukuran'             => (new MDataPengukuran())->findAll(),
            'sr'                     => (new MSR())->findAll(),
            'thomson'                => (new MThomsonWeir())->findAll(),
            'perhitungan_bocoran'    => (new PerhitunganBocoranModel())->findAll(),
            'perhitungan_ig'         => (new PerhitunganIntiGaleryModel())->findAll(),
            'perhitungan_spillway'   => (new PerhitunganSpillwayModel())->findAll(),
            'perhitungan_sr'         => (new PerhitunganSRModel())->findAll(),
            'perhitungan_thomson'    => (new PerhitunganThomsonModel())->getAllWithPengukuran(),
            'perhitungan_batas'      => (new PerhitunganBatasMaksimalModel())->getAllWithPengukuran(), // ✅ Tambah ini
            'tebing_kanan'           => (new TebingKananModel())->findAll(),
            'total_bocoran'          => (new TotalBocoranModel())->findAll(),
        ];

        return view('Data/data_rembesan', $data);
    }
}
