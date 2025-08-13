<?php

if (!function_exists('perhitunganQ_sr')) {
    function perhitunganQ_sr($nilai, $kode)
    {
        // Pastikan nilai berupa angka
        if (!is_numeric($nilai) || $nilai == 0) {
            return 0;
        }

        // Daftar faktor berdasarkan kode
        $faktor = [
            'L' => 1,
            'E' => 11,
            'S' => 0.2,
            'M' => 0.5
        ];

        // Normalisasi kode ke huruf besar
        $kode = strtoupper(trim($kode));

        // Jika kode tidak ada di daftar faktor, kembalikan 0
        if (!isset($faktor[$kode])) {
            return 0;
        }

        // Hitung Q_sr
        return ($faktor[$kode] / $nilai) * 60;
    }
}
