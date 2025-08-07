<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\IOFactory;
use CodeIgniter\Controller;

class ExcelViewerController extends BaseController
{
    public function tabelThomson()
    {
        $filePath = FCPATH . 'assets/excel/tabel_thomson.xlsx';

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }

        // Load Excel
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();

        return view('Data/view_tabel_thomson', ['data' => $data]);
    }

    public function tabelAmbangBatas()
    {
        $filePath = FCPATH . 'assets/excel/tabel_ambang.xlsx';

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }

        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();

        return view('Data/view_tabel_ambang', ['data' => $data]);
    }
}
