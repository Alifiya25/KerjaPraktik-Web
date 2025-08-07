<?php

namespace App\Controllers;

use App\Models\DataGabunganModel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class MenuController extends BaseController
{
    public function index()
    {
        return view('menu');
    }

    public function inputData()
    {
        helper('thomson');

        $model = new DataGabunganModel();
        $dataGabungan = $model->getDataGabungan();

        // Load Excel Tabel Thomson sekali
        $fileExcel = FCPATH . 'assets/excel/tabel_thomson.xlsx';
        $spreadsheet = IOFactory::load($fileExcel);
        $sheet = $spreadsheet->getSheetByName('Tabel Thomson');

        // Loop data untuk hitung per baris
        foreach ($dataGabungan as &$data) {
            $data['perhitungan_thomson'] = [
                'r'  => perhitunganQ_thomson($data['thomson']['a1_r'] ?? null, $sheet),
                'l'  => perhitunganQ_thomson($data['thomson']['a1_l'] ?? null, $sheet),
                'b1' => perhitunganQ_thomson($data['thomson']['b1'] ?? null, $sheet),
                'b3' => perhitunganQ_thomson($data['thomson']['b3'] ?? null, $sheet),
                'b5' => perhitunganQ_thomson($data['thomson']['b5'] ?? null, $sheet),
            ];
        }

        return view('Data/data_rembesan', [
            'dataGabungan' => $dataGabungan,
            'active' => 'pengukuran'
        ]);
    }

    public function grafikData()
    {
        return view('grafik_data');
    }

    /**
     * Endpoint API untuk polling data terbaru secara real-time
     * Akan dipanggil lewat AJAX di view
     */
    public function getRembesanData()
    {
        helper('thomson');

        $model = new DataGabunganModel();
        $dataGabungan = $model->getDataGabungan();

        // Muat Excel sekali saja
        $fileExcel = FCPATH . 'assets/excel/tabel_thomson.xlsx';
        $spreadsheet = IOFactory::load($fileExcel);
        $sheet = $spreadsheet->getSheetByName('Tabel Thomson');

        // Hitung ulang per baris untuk update real-time
        foreach ($dataGabungan as &$data) {
            $data['perhitungan_thomson'] = [
                'r'  => perhitunganQ_thomson($data['thomson']['a1_r'] ?? null, $sheet),
                'l'  => perhitunganQ_thomson($data['thomson']['a1_l'] ?? null, $sheet),
                'b1' => perhitunganQ_thomson($data['thomson']['b1'] ?? null, $sheet),
                'b3' => perhitunganQ_thomson($data['thomson']['b3'] ?? null, $sheet),
                'b5' => perhitunganQ_thomson($data['thomson']['b5'] ?? null, $sheet),
            ];
        }

        return $this->response->setJSON($dataGabungan);
    }
}
