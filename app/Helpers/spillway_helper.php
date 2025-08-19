<?php
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

if (!function_exists('hitungSpillway')) {
    function hitungSpillway(float $B1, float $B3Thomson): float
    {
        return $B1 + $B3Thomson;
    }
}

if (!function_exists('getAmbangSpillway')) {
    /**
     * Ambil seluruh data ambang spillway dari sheet Excel jadi array.
     * Key = string nilai TMA, Value = ambang spillway (kolom D)
     */
    function getAmbangSpillway(Worksheet $sheet): array
    {
        $highestRow = $sheet->getHighestRow();
        $data = [];

        for ($row = 5; $row <= $highestRow; $row++) {
            $valLookup = $sheet->getCell('B' . $row)->getCalculatedValue();
            $valResult = $sheet->getCell('D' . $row)->getCalculatedValue();
            if ($valLookup === null || $valLookup === '') continue;
            $data[(string)$valLookup] = (float)$valResult;
        }

        return $data;
    }
}

if (!function_exists('cariAmbangSpillway')) {
    function cariAmbangSpillway(float $tma, array $spillwayData)
    {
        $key = (string)$tma;
        return $spillwayData[$key] ?? null;
    }
}

if (!function_exists('loadAmbangSpillway')) {
    function loadAmbangSpillway(string $filePath, string $sheetName): array
    {
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getSheetByName($sheetName) ?: $spreadsheet->getActiveSheet();
        return getAmbangSpillway($sheet);
    }
}
