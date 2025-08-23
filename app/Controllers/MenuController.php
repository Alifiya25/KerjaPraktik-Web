<?php

namespace App\Controllers;

use App\Models\DataGabunganModel;
use App\Models\PerhitunganSRModel;
use App\Models\PerhitunganBocoranModel;
use App\Models\PerhitunganIntiGaleryModel;
use App\Models\PerhitunganSpillwayModel;
use App\Models\TebingKananModel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class MenuController extends BaseController
{
    public function index()
    {
        return view('menu');
    }
}