<?php

namespace App\Http\Controllers\Admin;

use \App\Http\Controllers\Controller;
use App\Http\Middleware\IsAdmin;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    function __construct()
    {
        $this->middleware(['role:Admin']);

    }
  // function __construct()
  //   {
  //        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
  //        $this->middleware('permission:role-create', ['only' => ['create','store']]);
  //        $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
  //        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
  //   }
    public function index() {
        return view('admin/dashboard');
    }
}
