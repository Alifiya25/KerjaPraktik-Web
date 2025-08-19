<?php

namespace App\Controllers;

use App\Models\DataGabunganModel;
use App\Models\PerhitunganSRModel;
use App\Models\PerhitunganBocoranModel;
use App\Models\PerhitunganIntiGaleryModel;
use App\Models\PerhitunganSpillwayModel;
use App\Models\TebingKananModel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class MenuController extends BaseController
{
    public function index()
    {
        return view('menu');
    }

    public function inputData()
    {
        // Load semua helper
        helper([
            'thomson', 'sr', 'bocoran', 'ambang', 'spillway',
            'TebingKanan', 'totalBocoran', 'BatasMaksimal'
        ]);

        // Load model
        $model           = new DataGabunganModel();
        $srModel         = new PerhitunganSRModel();
        $bocoranModel    = new PerhitunganBocoranModel();
        $intiGaleryModel = new PerhitunganIntiGaleryModel();
        $spillwayModel   = new PerhitunganSpillwayModel();
        $tebingModel     = new TebingKananModel();
        $totalBocoranModel = new \App\Models\TotalBocoranModel();

        // Ambil data gabungan
        $dataGabungan = $model->getDataGabungan();

        // Load Excel
        $sheetThomson = IOFactory::load(FCPATH . 'assets/excel/tabel_thomson.xlsx')
                                 ->getSheetByName('Tabel Thomson');
        $spreadsheetAmbang = IOFactory::load(FCPATH . 'assets/excel/tabel_ambang.xlsx');
        $sheetAmbang = $spreadsheetAmbang->getSheetByName('AMBANG TIAP CM')
                      ?: $spreadsheetAmbang->getActiveSheet();

        // Ambang Inti Galeri dan Tebing
        $ambangData      = getAmbangData($sheetAmbang);
        $ambangDataTebing = getAmbangTebingKanan(FCPATH . 'assets/excel/tabel_ambang.xlsx', 'AMBANG TIAP CM');
        $spillwayDataArray = loadAmbangSpillway(FCPATH . 'assets/excel/tabel_ambang.xlsx', 'AMBANG TIAP CM');

        $sr_fields = [1, 40, 66, 68, 70, 79, 81, 83, 85, 92, 94, 96, 98, 100, 102, 104, 106];

        foreach ($dataGabungan as &$data) {
            $tma = (float)($data['pengukuran']['tma_waduk'] ?? 0);

            // ================== Perhitungan Thomson ==================
            $thomson = [
                'r'  => perhitunganQ_thomson($data['thomson']['a1_r'] ?? 0, $sheetThomson),
                'l'  => perhitunganQ_thomson($data['thomson']['a1_l'] ?? 0, $sheetThomson),
                'b1' => perhitunganQ_thomson($data['thomson']['b1'] ?? 0, $sheetThomson),
                'b3' => perhitunganQ_thomson($data['thomson']['b3'] ?? 0, $sheetThomson),
                'b5' => perhitunganQ_thomson($data['thomson']['b5'] ?? 0, $sheetThomson),
            ];
            $data['perhitungan_thomson'] = $thomson;

            // ================== Perhitungan SR ==================
            $perhitunganSR = [];
            foreach ($sr_fields as $field) {
                $nilai = $data['sr']["sr_{$field}_nilai"] ?? 0;
                $kode  = $data['sr']["sr_{$field}_kode"] ?? '';
                $perhitunganSR["sr_{$field}_q"] = perhitunganQ_sr($nilai, $kode);
            }

            // ================== Perhitungan Bocoran ==================
            $perhitunganBocoran = [
                'talang1' => perhitunganQ_bocoran($data['bocoran']['elv_624_t1'] ?? 0, $data['bocoran']['elv_624_t1_kode'] ?? ''),
                'talang2' => perhitunganQ_bocoran($data['bocoran']['elv_615_t2'] ?? 0, $data['bocoran']['elv_615_t2_kode'] ?? ''),
                'pipa'    => perhitunganQ_bocoran($data['bocoran']['pipa_p1'] ?? 0, $data['bocoran']['pipa_p1_kode'] ?? ''),
            ];

            // ================== Perhitungan Inti Galeri ==================
            $a1 = $thomson['r'] + $thomson['l'];
            $ambang_a1 = ($tma > 0) ? cariAmbangArray($tma, $ambangData) : null;
            $perhitunganInti = [
                'pengukuran_id' => $data['pengukuran_id'] ?? null,
                'a1'            => $a1,
                'ambang_a1'     => $ambang_a1,
            ];

            // ================== Perhitungan Spillway ==================
            $B3 = hitungSpillway($thomson['b1'], $thomson['b3']);
            $ambangSpillway = ($tma > 0) ? cariAmbangSpillway($tma, $spillwayDataArray) : null;
            $spillwayData = [
                'pengukuran_id' => $data['pengukuran_id'] ?? null,
                'B3'            => $B3,
                'ambang'        => $ambangSpillway
            ];

            // ================== Perhitungan Tebing Kanan ==================
            $sr_tebing = hitungSrTebingKanan($data['sr'] ?? [], $sr_fields);
            $ambang_tebing = ($tma > 0) ? cariAmbangTebingKanan($tma, $ambangDataTebing) : null;
            $b5_thomson = $data['perhitungan_thomson']['b5'] ?? 0;

            $perhitunganTebing = [
                'sr' => $sr_tebing,
                'ambang' => $ambang_tebing,
                'pengukuran_id' => $data['pengukuran_id'] ?? null,
                'b5' => $b5_thomson,
            ];

            // ================== Perhitungan Total Bocoran ==================
            $r1 = hitungTotalBocoran(
                $perhitunganInti['a1'], 
                $spillwayData['B3'], 
                $perhitunganTebing['sr']
            );

            // ================== Perhitungan Batas Maksimal ==================
            $batasData = loadBatasMaksimal($sheetAmbang);
            $batasMaksimal = cariBatasMaksimal($tma, $batasData);

            $data['batas_maksimal'] = $batasMaksimal;

            // ================== Simpan ke DB ==================
            if (!empty($data['pengukuran_id'])) {
                // SR
                $perhitunganSR['pengukuran_id'] = $data['pengukuran_id'];
                $cekSR = $srModel->where('pengukuran_id', $data['pengukuran_id'])->first();
                $cekSR ? $srModel->update($cekSR['id'], $perhitunganSR) : $srModel->insert($perhitunganSR);

                // Bocoran
                $perhitunganBocoran['pengukuran_id'] = $data['pengukuran_id'];
                $cekBocoran = $bocoranModel->where('pengukuran_id', $data['pengukuran_id'])->first();
                $cekBocoran ? $bocoranModel->update($cekBocoran['id'], $perhitunganBocoran) : $bocoranModel->insert($perhitunganBocoran);

                // Inti Galeri
                $cekInti = $intiGaleryModel->where('pengukuran_id', $data['pengukuran_id'])->first();
                $cekInti ? $intiGaleryModel->update($cekInti['id'], $perhitunganInti)
                         : $intiGaleryModel->insert($perhitunganInti);

                // Spillway
                $cekSpillway = $spillwayModel->where('pengukuran_id', $data['pengukuran_id'])->first();
                $cekSpillway ? $spillwayModel->update($cekSpillway['id'], $spillwayData)
                             : $spillwayModel->insert($spillwayData);

                // Tebing Kanan
                $cekTebing = $tebingModel->where('pengukuran_id', $data['pengukuran_id'])->first();
                $cekTebing ? $tebingModel->update($cekTebing['id'], $perhitunganTebing)
                            : $tebingModel->insert($perhitunganTebing);

                // Total Bocoran
                $totalBocoranData = [
                    'pengukuran_id' => $data['pengukuran_id'],
                    'R1' => $r1
                ];
                $cekTotal = $totalBocoranModel->where('pengukuran_id', $data['pengukuran_id'])->first();
                $cekTotal ? $totalBocoranModel->update($cekTotal['id'], $totalBocoranData)
                          : $totalBocoranModel->insert($totalBocoranData);
            }

            // ================== Untuk view ==================
            $data['perhitungan_sr']       = $perhitunganSR;
            $data['perhitungan_bocoran']  = $perhitunganBocoran;
            $data['perhitungan_inti']     = $perhitunganInti;
            $data['perhitungan_spillway'] = $spillwayData;
            $data['perhitungan_tebing_kanan'] = $perhitunganTebing;
            $data['perhitungan_total_bocoran'] = ['r1' => $r1];
        }

        return view('Data/data_rembesan', [
            'dataGabungan' => $dataGabungan,
            'active'       => 'pengukuran'
        ]);
    }

    public function getRembesanData()
    {
        helper([
            'thomson', 'sr', 'bocoran', 'ambang', 'spillway',
            'TebingKanan', 'totalBocoran', 'BatasMaksimal'
        ]);

        $dataGabungan = (new DataGabunganModel())->getDataGabungan();

        $sheetThomson = IOFactory::load(FCPATH . 'assets/excel/tabel_thomson.xlsx')
                                 ->getSheetByName('Tabel Thomson');

        $spreadsheetAmbang = IOFactory::load(FCPATH . 'assets/excel/tabel_ambang.xlsx');
        $sheetAmbang = $spreadsheetAmbang->getSheetByName('AMBANG TIAP CM')
                      ?: $spreadsheetAmbang->getActiveSheet();

        $ambangData = getAmbangData($sheetAmbang);
        $ambangDataTebing = getAmbangTebingKanan(FCPATH . 'assets/excel/tabel_ambang.xlsx', 'AMBANG TIAP CM');
        $spillwayDataArray = loadAmbangSpillway(FCPATH . 'assets/excel/tabel_ambang.xlsx', 'AMBANG TIAP CM');

        $sr_fields = [1, 40, 66, 68, 70, 79, 81, 83, 85, 92, 94, 96, 98, 100, 102, 104, 106];

        foreach ($dataGabungan as &$data) {
            $tma = (float)($data['pengukuran']['tma_waduk'] ?? 0);

            // Perhitungan Thomson
            $thomson = [
                'r'  => perhitunganQ_thomson($data['thomson']['a1_r'] ?? 0, $sheetThomson),
                'l'  => perhitunganQ_thomson($data['thomson']['a1_l'] ?? 0, $sheetThomson),
                'b1' => perhitunganQ_thomson($data['thomson']['b1'] ?? 0, $sheetThomson),
                'b3' => perhitunganQ_thomson($data['thomson']['b3'] ?? 0, $sheetThomson),
                'b5' => perhitunganQ_thomson($data['thomson']['b5'] ?? 0, $sheetThomson),
            ];
            $data['perhitungan_thomson'] = $thomson;

            // Perhitungan SR
            $perhitunganSR = [];
            foreach ($sr_fields as $field) {
                $nilai = $data['sr']["sr_{$field}_nilai"] ?? 0;
                $kode  = $data['sr']["sr_{$field}_kode"] ?? '';
                $perhitunganSR["sr_{$field}_q"] = perhitunganQ_sr($nilai, $kode);
            }
            $data['perhitungan_sr'] = $perhitunganSR;

            // Perhitungan Bocoran
            $data['perhitungan_bocoran'] = [
                'talang1' => perhitunganQ_bocoran($data['bocoran']['elv_624_t1'] ?? 0, $data['bocoran']['elv_624_t1_kode'] ?? ''),
                'talang2' => perhitunganQ_bocoran($data['bocoran']['elv_615_t2'] ?? 0, $data['bocoran']['elv_615_t2_kode'] ?? ''),
                'pipa'    => perhitunganQ_bocoran($data['bocoran']['pipa_p1'] ?? 0, $data['bocoran']['pipa_p1_kode'] ?? ''),
            ];

            // Perhitungan Inti
            $a1 = $thomson['r'] + $thomson['l'];
            $ambang_a1 = ($tma > 0) ? cariAmbangArray($tma, $ambangData) : null;
            $data['perhitungan_inti'] = ['a1' => $a1, 'ambang_a1' => $ambang_a1];

            // Spillway
            $B3 = hitungSpillway($thomson['b1'], $thomson['b3']);
            $ambangSpillway = ($tma > 0) ? cariAmbangSpillway($tma, $spillwayDataArray) : null;
            $data['perhitungan_spillway'] = ['B3' => $B3, 'ambang' => $ambangSpillway];

            // Tebing kanan
            $sr_tebing = hitungSrTebingKanan($data['sr'] ?? [], $sr_fields);
            $ambang_tebing = ($tma > 0) ? cariAmbangTebingKanan($tma, $ambangDataTebing) : null;
            $b5_thomson = $data['perhitungan_thomson']['b5'] ?? 0;
            $data['perhitungan_tebing_kanan'] = [
                'sr' => $sr_tebing,
                'ambang' => $ambang_tebing,
                'b5' => $b5_thomson,
            ];

            // Total Bocoran
            $r1 = hitungTotalBocoran($a1, $B3, $sr_tebing);
            $data['perhitungan_total_bocoran'] = ['r1' => $r1];

            // Batas Maksimal
            $batasData = loadBatasMaksimal($sheetAmbang);
            $data['batas_maksimal'] = cariBatasMaksimal($tma, $batasData);
        }

        return $this->response->setJSON($dataGabungan);
    }
}
