<?php

namespace App\Controllers;

use App\Models\DataGabunganModel;
use App\Models\PerhitunganSRModel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class MenuController extends BaseController
{
    public function index()
    {
        return view('menu');
    }

   public function inputData()
{
    helper(['thomson', 'sr']);

    $model   = new DataGabunganModel();
    $srModel = new PerhitunganSRModel(); // Pastikan model ini diarahkan ke tabel p_sr

    $dataGabungan = $model->getDataGabungan();

    $fileExcel    = FCPATH . 'assets/excel/tabel_thomson.xlsx';
    $spreadsheet  = IOFactory::load($fileExcel);
    $sheet        = $spreadsheet->getSheetByName('Tabel Thomson');

    $sr_fields = [1, 40, 66, 68, 70, 79, 81, 83, 85, 92, 94, 96, 98, 100, 102, 104, 106];

    foreach ($dataGabungan as &$data) {
        // Hitung Q Thomson
        $data['perhitungan_thomson'] = [
            'r'  => perhitunganQ_thomson($data['thomson']['a1_r'] ?? null, $sheet),
            'l'  => perhitunganQ_thomson($data['thomson']['a1_l'] ?? null, $sheet),
            'b1' => perhitunganQ_thomson($data['thomson']['b1'] ?? null, $sheet),
            'b3' => perhitunganQ_thomson($data['thomson']['b3'] ?? null, $sheet),
            'b5' => perhitunganQ_thomson($data['thomson']['b5'] ?? null, $sheet),
        ];

        // Hitung Q SR untuk semua field
        $perhitunganSR = [];
        foreach ($sr_fields as $field) {
            $nilai = $data['sr']["sr_{$field}_nilai"] ?? 0;
            $kode  = $data['sr']["sr_{$field}_kode"] ?? '';
            $perhitunganSR["sr_{$field}_q"] = perhitunganQ_sr($nilai, $kode);
        }

        // Simpan ke tabel p_sr jika pengukuran_id ada
        if (!empty($data['pengukuran_id'])) {
            $perhitunganSR['pengukuran_id'] = $data['pengukuran_id'];
            $perhitunganSR['created_at'] = date('Y-m-d H:i:s');
            $perhitunganSR['updated_at'] = date('Y-m-d H:i:s');

            $srModel->insert($perhitunganSR);
        } else {
            log_message('error', 'Pengukuran ID kosong untuk data: ' . json_encode($data));
        }

        // Simpan untuk view
        $data['perhitungan_sr'] = $perhitunganSR;
    }

    return view('Data/data_rembesan', [
        'dataGabungan' => $dataGabungan,
        'active'       => 'pengukuran'
    ]);
}


    public function grafikData()
    {
        return view('grafik_data');
    }

    public function getRembesanData()
    {
        helper(['thomson', 'sr']);

        $dataModel = new DataGabunganModel();
        $srModel = new PerhitunganSRModel();

        $dataGabungan = $dataModel->getDataGabungan();

        $fileExcel = FCPATH . 'assets/excel/tabel_thomson.xlsx';
        $spreadsheet = IOFactory::load($fileExcel);
        $sheet = $spreadsheet->getSheetByName('Tabel Thomson');

        $sr_fields = [1, 40, 66, 68, 70, 79, 81, 83, 85, 92, 94, 96, 98, 100, 102, 104, 106];

        foreach ($dataGabungan as &$data) {
            // Perhitungan Q Thomson
            $data['perhitungan_thomson'] = [
                'r'  => perhitunganQ_thomson($data['thomson']['a1_r'] ?? null, $sheet),
                'l'  => perhitunganQ_thomson($data['thomson']['a1_l'] ?? null, $sheet),
                'b1' => perhitunganQ_thomson($data['thomson']['b1'] ?? null, $sheet),
                'b3' => perhitunganQ_thomson($data['thomson']['b3'] ?? null, $sheet),
                'b5' => perhitunganQ_thomson($data['thomson']['b5'] ?? null, $sheet),
            ];

            // Perhitungan Q SR
            $data['perhitungan_sr'] = [];

            foreach ($sr_fields as $field) {
                $nilai = $data['sr']["sr_{$field}_nilai"] ?? 0;
                $kode = $data['sr']["sr_{$field}_kode"] ?? '';
                $hasilQ = perhitunganQ_sr($nilai, $kode);
                $data['perhitungan_sr']["sr_{$field}_q"] = $hasilQ;
            }
        }

        return $this->response->setJSON($dataGabungan);
    }
}
