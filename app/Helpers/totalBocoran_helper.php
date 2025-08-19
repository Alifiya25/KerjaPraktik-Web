<?php

if (!function_exists('hitungTotalBocoran')) {
    /**
     * Hitung R1 untuk p_totalbocoran
     * 
     * @param float $a1  Nilai A1 dari inti galeri
     * @param float $b3  Nilai B3 dari perhitungan bawah bendungan / spillway
     * @param float $sr  Nilai SR dari Tebing Kanan
     * @return float
     */
    function hitungTotalBocoran($a1 = 0, $b3 = 0, $sr = 0)
    {
        // Pastikan semua parameter adalah angka
        $a1 = is_numeric($a1) ? $a1 : 0;
        $b3 = is_numeric($b3) ? $b3 : 0;
        $sr = is_numeric($sr) ? $sr : 0;

        // Hitung total
        $r1 = $a1 + $b3 + $sr;

        return $r1;
    }
}
