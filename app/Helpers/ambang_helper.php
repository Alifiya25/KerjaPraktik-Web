<?php 
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

if (!function_exists('getAmbangData')) {
    /**
     * Ambil seluruh data ambang dari sheet Excel jadi array.
     * Key = string nilai TMA (tidak dibulatkan), Value = ambang_a1
     */
    function getAmbangData(Worksheet $sheet): array
    {
        $highestRow = $sheet->getHighestRow();
        $data = [];

        for ($row = 5; $row <= $highestRow; $row++) {
            $valLookup = $sheet->getCell('B' . $row)->getCalculatedValue();
            $valResult = $sheet->getCell('C' . $row)->getCalculatedValue();

            if ($valLookup === null || $valLookup === '') continue;

            // Key sebagai string, value tetap float
            $data[(string)$valLookup] = (float)$valResult;
        }

        return $data;
    }
}

if (!function_exists('cariAmbangArray')) {
    /**
     * Cari ambang_a1 berdasarkan TMA persis.
     * Key dicocokkan sebagai string supaya desimal persis tetap sama.
     */
    function cariAmbangArray(float $tma, array $ambangData)
    {
        $key = (string)$tma; // pakai string agar tidak terjadi pembulatan
        return $ambangData[$key] ?? null;
    }
}
