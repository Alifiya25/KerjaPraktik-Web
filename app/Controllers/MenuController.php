<?php

namespace App\Controllers;

use App\Models\DataGabunganModel;
use App\Models\PerhitunganSRModel;
use App\Models\PerhitunganBocoranModel;
use App\Models\PerhitunganIntiGaleryModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MenuController extends BaseController
{
    public function index()
    {
        return view('menu');
    }

    public function inputData()
    {
        helper(['thomson', 'sr', 'bocoran']);

        $model           = new DataGabunganModel();
        $srModel         = new PerhitunganSRModel();
        $bocoranModel    = new PerhitunganBocoranModel();
        $intiGaleryModel = new PerhitunganIntiGaleryModel();

        $dataGabungan = $model->getDataGabungan();

        // Thomson
        $fileExcelThomson = FCPATH . 'assets/excel/tabel_thomson.xlsx';
        $sheetThomson     = IOFactory::load($fileExcelThomson)->getSheetByName('Tabel Thomson');

        // Ambang
        $fileAmbang        = FCPATH . 'assets/excel/tabel_ambang.xlsx';
        $spreadsheetAmbang = IOFactory::load($fileAmbang);

        // Prioritas nama sheet → fallback ke Sheet1 → fallback ke active
        $sheetAmbang = $spreadsheetAmbang->getSheetByName('AMBANG TIAP CM')
            ?: $spreadsheetAmbang->getSheetByName('Sheet1')
            ?: $spreadsheetAmbang->getActiveSheet();

        // Build index lookup (B->C) mulai baris 5
        $ambangIndex = $this->buildAmbangIndex($sheetAmbang, 5, 'B', 'C');

        $sr_fields = [1, 40, 66, 68, 70, 79, 81, 83, 85, 92, 94, 96, 98, 100, 102, 104, 106];

        foreach ($dataGabungan as &$data) {
            // Perhitungan Thomson
            $data['perhitungan_thomson'] = [
                'r'  => perhitunganQ_thomson($data['thomson']['a1_r'] ?? null, $sheetThomson),
                'l'  => perhitunganQ_thomson($data['thomson']['a1_l'] ?? null, $sheetThomson),
                'b1' => perhitunganQ_thomson($data['thomson']['b1'] ?? null, $sheetThomson),
                'b3' => perhitunganQ_thomson($data['thomson']['b3'] ?? null, $sheetThomson),
                'b5' => perhitunganQ_thomson($data['thomson']['b5'] ?? null, $sheetThomson),
            ];

            // Perhitungan SR
            $perhitunganSR = [];
            foreach ($sr_fields as $field) {
                $nilai = $data['sr']["sr_{$field}_nilai"] ?? 0;
                $kode  = $data['sr']["sr_{$field}_kode"] ?? '';
                $perhitunganSR["sr_{$field}_q"] = perhitunganQ_sr($nilai, $kode);
            }

            // Perhitungan Bocoran
            $perhitunganBocoran = [
                'talang1' => perhitunganQ_bocoran($data['bocoran']['elv_624_t1'] ?? 0, $data['bocoran']['elv_624_t1_kode'] ?? ''),
                'talang2' => perhitunganQ_bocoran($data['bocoran']['elv_615_t2'] ?? 0, $data['bocoran']['elv_615_t2_kode'] ?? ''),
                'pipa'    => perhitunganQ_bocoran($data['bocoran']['pipa_p1'] ?? 0, $data['bocoran']['pipa_p1_kode'] ?? ''),
            ];

            // Inti Galeri: a1 = r + l
            $a1 = round((float)($data['perhitungan_thomson']['r'] ?? 0) + (float)($data['perhitungan_thomson']['l'] ?? 0), 3);
            $ambang_a1 = $this->ambangLookup($a1, $ambangIndex); // EXACT match ala VLOOKUP(...,0)

            $perhitunganIntiGalery = [
                'pengukuran_id' => $data['pengukuran_id'] ?? null,
                'a1'            => $a1,
                'ambang'        => $ambang_a1,
            ];

            // Simpan ke DB
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
                $cekInti ? $intiGaleryModel->update($cekInti['id'], $perhitunganIntiGalery) : $intiGaleryModel->insert($perhitunganIntiGalery);
            }

            // Untuk view
            $data['perhitungan_sr']      = $perhitunganSR;
            $data['perhitungan_bocoran'] = $perhitunganBocoran;
            $data['perhitungan_inti']    = $perhitunganIntiGalery;
        }

        return view('Data/data_rembesan', [
            'dataGabungan' => $dataGabungan,
            'active'       => 'pengukuran'
        ]);
    }

    public function getRembesanData()
    {
        helper(['thomson', 'sr', 'bocoran']);

        $dataModel    = new DataGabunganModel();
        $dataGabungan = $dataModel->getDataGabungan();

        // Thomson
        $fileExcelThomson = FCPATH . 'assets/excel/tabel_thomson.xlsx';
        $sheetThomson     = IOFactory::load($fileExcelThomson)->getSheetByName('Tabel Thomson');

        // Ambang
        $fileAmbang        = FCPATH . 'assets/excel/tabel_ambang.xlsx';
        $spreadsheetAmbang = IOFactory::load($fileAmbang);
        $sheetAmbang = $spreadsheetAmbang->getSheetByName('AMBANG TIAP CM')
            ?: $spreadsheetAmbang->getSheetByName('Sheet1')
            ?: $spreadsheetAmbang->getActiveSheet();

        $ambangIndex = $this->buildAmbangIndex($sheetAmbang, 5, 'B', 'C');

        $sr_fields = [1, 40, 66, 68, 70, 79, 81, 83, 85, 92, 94, 96, 98, 100, 102, 104, 106];

        foreach ($dataGabungan as &$data) {
            // Thomson
            $data['perhitungan_thomson'] = [
                'r'  => perhitunganQ_thomson($data['thomson']['a1_r'] ?? null, $sheetThomson),
                'l'  => perhitunganQ_thomson($data['thomson']['a1_l'] ?? null, $sheetThomson),
                'b1' => perhitunganQ_thomson($data['thomson']['b1'] ?? null, $sheetThomson),
                'b3' => perhitunganQ_thomson($data['thomson']['b3'] ?? null, $sheetThomson),
                'b5' => perhitunganQ_thomson($data['thomson']['b5'] ?? null, $sheetThomson),
            ];

            // SR
            $data['perhitungan_sr'] = [];
            foreach ($sr_fields as $field) {
                $nilai = $data['sr']["sr_{$field}_nilai"] ?? 0;
                $kode  = $data['sr']["sr_{$field}_kode"] ?? '';
                $data['perhitungan_sr']["sr_{$field}_q"] = perhitunganQ_sr($nilai, $kode);
            }

            // Bocoran
            $data['perhitungan_bocoran'] = [
                'talang1' => perhitunganQ_bocoran($data['bocoran']['elv_624_t1'] ?? 0, $data['bocoran']['elv_624_t1_kode'] ?? ''),
                'talang2' => perhitunganQ_bocoran($data['bocoran']['elv_615_t2'] ?? 0, $data['bocoran']['elv_615_t2_kode'] ?? ''),
                'pipa'    => perhitunganQ_bocoran($data['bocoran']['pipa_p1'] ?? 0, $data['bocoran']['pipa_p1_kode'] ?? ''),
            ];

            // Inti Galeri
            $a1 = round((float)($data['perhitungan_thomson']['r'] ?? 0) + (float)($data['perhitungan_thomson']['l'] ?? 0), 3);
            $ambang_a1 = $this->ambangLookup($a1, $ambangIndex);

            $data['perhitungan_inti'] = [
                'a1'     => $a1,
                'ambang' => $ambang_a1,
            ];
        }

        return $this->response->setJSON($dataGabungan);
    }

    /** ===================== Helper Lookup ===================== */

    // Bangun index: kunci angka (dibulatkan 3 & 2 desimal) → nilai ambang
    private function buildAmbangIndex(Worksheet $sheet, int $startRow, string $colLookup, string $colResult): array
    {
        $idx = [];
        $highestRow = $sheet->getHighestRow();

        for ($row = $startRow; $row <= $highestRow; $row++) {
            $rawLookup = $sheet->getCell($colLookup . $row)->getCalculatedValue();
            $rawResult = $sheet->getCell($colResult . $row)->getCalculatedValue();

            $val = $this->toFloat($rawLookup);
            if ($val === null) {
                continue;
            }

            // Simpan 3 dan 2 desimal agar matching lebih mudah
            $k3 = number_format($val, 3, '.', '');
            $k2 = number_format($val, 2, '.', '');

            if (!array_key_exists($k3, $idx)) $idx[$k3] = $rawResult;
            if (!array_key_exists($k2, $idx)) $idx[$k2] = $rawResult;
        }

        return $idx;
    }

    // Lookup EXACT (ala VLOOKUP range_lookup = 0)
    private function ambangLookup(float $lookup, array $idx)
    {
        $k3 = number_format($lookup, 3, '.', '');
        if (array_key_exists($k3, $idx)) {
            return $idx[$k3];
        }

        $k2 = number_format($lookup, 2, '.', '');
        if (array_key_exists($k2, $idx)) {
            return $idx[$k2];
        }

        // Tidak ada exact → kembalikan null (sesuai VLOOKUP(...,0))
        return null;
    }

    // Normalisasi string angka (titik/koma, spasi, thousand sep) → float
    private function toFloat($value): ?float
    {
        if (is_null($value)) return null;
        if (is_float($value) || is_int($value)) return (float)$value;

        $s = trim((string)$value);
        if ($s === '') return null;

        // hilangkan spasi & non-breaking space
        $s = str_replace(["\xC2\xA0", ' '], '', $s);

        $comma = substr_count($s, ',');
        $dot   = substr_count($s, '.');

        // Kasus umum: "1.234,56" → "1234.56"
        if ($comma === 1 && $dot >= 1) {
            $s = str_replace('.', '', $s);
            $s = str_replace(',', '.', $s);
        }
        // Kasus: "1234,56" → "1234.56"
        elseif ($comma >= 1 && $dot === 0) {
            $s = str_replace(',', '.', $s);
        }
        // Kasus: "1,234.56" → "1234.56"
        elseif ($dot === 1 && $comma >= 1) {
            $s = str_replace(',', '', $s);
        }

        // Sisakan angka, minus, titik
        $s = preg_replace('/[^0-9\.\-]/', '', $s);
        if ($s === '' || $s === '-' || $s === '.') return null;

        return (float)$s;
    }
}
