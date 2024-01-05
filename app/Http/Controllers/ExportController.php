<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Exports\DataExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function export()
    {
        $data = new DataExport();
        $collection = $data->collection();
        return Excel::download(new DataExport, 'data.csv');
    }
}