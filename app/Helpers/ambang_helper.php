<?php
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

if (!function_exists('cariAmbang')) {
function cariAmbang($nilaiA1, $sheetAmbang)
{
    if ($nilaiA1 === null || $nilaiA1 == 0) {
        return 0;
    }

    // Ambil semua data dari Sheet1
    $rows = $sheetAmbang->toArray();

    $closestValue = null;
    $closestDiff  = PHP_FLOAT_MAX;

    foreach ($rows as $row) {
        $lookupValue = floatval($row[0]); // Kolom pertama
        $diff = abs($lookupValue - $nilaiA1);

        if ($diff < $closestDiff) {
            $closestDiff  = $diff;
            $closestValue = $row[1]; // Kolom kedua = nilai ambang
        }
    }

    return $closestValue ?? 0;
}

}

