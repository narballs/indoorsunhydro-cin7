<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TaxClass;

class TaxClassController extends Controller
{
    public function index() {
        $tax_classes = TaxClass::paginate(10);
        return view('admin.tax_classes.index' , compact('tax_classes'));
    }
}