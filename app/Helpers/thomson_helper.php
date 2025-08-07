<?php

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

function perhitunganQ_thomson($lookupValue, Worksheet $sheet, $startRow = 4, $colKey = 'A', $colReturn = 'C')
{
    if ($lookupValue === null) {
        return null;
    }

    $row = $startRow;
    while (true) {
        $cellValue = $sheet->getCell("{$colKey}{$row}")->getValue();
        if ($cellValue === null) break;
        if ($cellValue == $lookupValue) {
            return $sheet->getCell("{$colReturn}{$row}")->getValue();
        }
        $row++;
    }

    return null;
}
