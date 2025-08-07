<?php

namespace App\Controllers;

use App\Models\DataGabunganModel;

class MenuController extends BaseController
{
    public function index()
    {
        return view('menu');
    }

    public function inputData()
    {
        $model = new DataGabunganModel();
        $dataGabungan = $model->getDataGabungan();

        return view('Data/data_rembesan', [
            'dataGabungan' => $dataGabungan, 
            'active' => 'pengukuran'
        ]);
    }

    public function grafikData()
    {
        return view('grafik_data');
    }
}
