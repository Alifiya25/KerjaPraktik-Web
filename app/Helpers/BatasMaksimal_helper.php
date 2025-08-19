<?php 
if (!function_exists('loadBatasMaksimal')) {
    /**
     * Ambil data batas maksimal dari sheet Excel
     * Kolom B = TMA, Kolom F = batas maksimal
     *
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet|null $sheet
     * @return array Array ['TMA' => 'batas maksimal']
     */
    function loadBatasMaksimal($sheet)
    {
        // jika sheet null, kembalikan array kosong
        if (!$sheet || !method_exists($sheet, 'getHighestDataRow')) return [];

        $highestRow = $sheet->getHighestDataRow();
        $data = [];

        // mulai dari row 5 sesuai permintaan
        for ($row = 5; $row <= $highestRow; $row++) {
            $valLookup = $sheet->getCell('B' . $row)->getCalculatedValue();
            $valResult = $sheet->getCell('F' . $row)->getCalculatedValue();

            if ($valLookup === null || $valLookup === '') continue;

            $data[(string)$valLookup] = (float)$valResult;
        }

        return $data;
    }
}

if (!function_exists('cariBatasMaksimal')) {
    /**
     * Cari batas maksimal dari array lookup
     *
     * @param float $tma Nilai TMA waduk
     * @param array $batasData Array ['TMA' => 'batas maksimal']
     * @return float|null Nilai batas maksimal sesuai TMA
     */
    function cariBatasMaksimal($tma, $batasData)
    {
        if (empty($batasData)) return null;

        // urutkan TMA ascending
        ksort($batasData);

        foreach ($batasData as $tmaKey => $batas) {
            if ($tma <= (float)$tmaKey) {
                return $batas;
            }
        }

        // jika TMA lebih besar dari semua nilai
        return end($batasData);
    }
}
