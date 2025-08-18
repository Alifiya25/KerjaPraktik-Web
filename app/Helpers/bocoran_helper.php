<?php

if (!function_exists('perhitunganQ_bocoran')) {
    function perhitunganQ_bocoran($nilai, $kode)
    {
        // Jika nilai 0 atau kosong => hasil 0
        if (empty($nilai) || $nilai == 0) {
            return 0;
        }

        $kode = strtoupper(trim($kode));

        // Rumus sesuai Excel
        switch ($kode) {
            case 'L': $hasil = (1 / $nilai); break;
            case 'E': $hasil = (11 / $nilai); break;
            case 'S': $hasil = (0.2 / $nilai); break;
            case 'M': $hasil = (0.5 / $nilai); break;
            default:  $hasil = 0; break;
        }

        return $hasil * 60; // dikali 60 di akhir
    }
}
