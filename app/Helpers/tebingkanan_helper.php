<?php
use PhpOffice\PhpSpreadsheet\IOFactory;

if (!function_exists('hitungSrTebingKanan')) {
    /**
     * Hitung SR Tebing Kanan dari array data SR.
     * 
     * @param array $dataSr
     * @param array $srFields Contoh: [1, 40, 66, ..., 106]
     * @return float
     */
    function hitungSrTebingKanan(array $dataSr, array $srFields): float
    {
        $total = 0;
        foreach ($srFields as $field) {
            $nilai = $dataSr["sr_{$field}_nilai"] ?? 0;
            $kode  = $dataSr["sr_{$field}_kode"] ?? '';
            $total += perhitunganQ_sr($nilai, $kode);
        }
        return $total;
    }
}

if (!function_exists('getAmbangTebingKanan')) {
    /**
     * Ambil seluruh data ambang dari Excel (kolom B = TMA, kolom E = Ambang Tebing Kanan)
     */
    function getAmbangTebingKanan(string $fileExcel, string $sheetName = 'AMBANG TIAP CM'): array
    {
        $spreadsheet = IOFactory::load($fileExcel);
        $sheet = $spreadsheet->getSheetByName($sheetName) ?: $spreadsheet->getActiveSheet();
        $highestRow = $sheet->getHighestRow();
        $data = [];

        for ($row = 5; $row <= $highestRow; $row++) {
            $tma = $sheet->getCell('B' . $row)->getCalculatedValue();
            $ambang = $sheet->getCell('E' . $row)->getCalculatedValue();

            if ($tma === null || $tma === '') continue;

            $data[(string)$tma] = (float)$ambang;
        }

        return $data;
    }
}

if (!function_exists('cariAmbangTebingKanan')) {
    /**
     * Cari ambang Tebing Kanan berdasarkan TMA persis.
     */
    function cariAmbangTebingKanan(float $tma, array $ambangData)
    {
        $key = (string)$tma;
        return $ambangData[$key] ?? null;
    }
}
