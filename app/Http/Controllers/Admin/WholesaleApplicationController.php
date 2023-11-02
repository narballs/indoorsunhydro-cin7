<?php

namespace App\Http\Controllers\Admin;
use \App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\WholesaleApplicationInformation;
use App\Models\WholesaleApplicationAddress;
use App\Models\WholesaleApplicationAuthorizationDetail;
use App\Models\WholesaleApplicationRegulationDetail;
use App\Models\WholesaleApplicationCard;
use Illuminate\Support\Facades\DB;

class WholesaleApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $wholesale_applications = WholesaleApplicationInformation::
        // whereHas('wholesale_application_address', function ($query) {
        //     $query->select(DB::raw(1))->from('wholesale_application_addresses')
        //     ->whereRaw('wholesale_application_addresses.wholesale_application_id = wholesale_application_information.id');
        // })
        // ->whereHas('wholesale_application_regulation_detail', function ($query) {
        //     $query->select(DB::raw(1))->from('wholesale_application_regulation_details')
        //     ->whereRaw('wholesale_application_regulation_details.wholesale_application_id = wholesale_application_information.id');
        // })
        // ->whereHas('wholesale_application_authorization_detail', function ($query) {
        //     $query->select(DB::raw(1))->from('wholesale_application_authorization_details')
        //     ->whereRaw('wholesale_application_authorization_details	.wholesale_application_id = wholesale_application_information.id');
        // })
        // ->whereHas('wholesale_application_card', function ($query) {
        //     $query->select(DB::raw(1))->from('wholesale_application_cards')
        //     ->whereRaw('wholesale_application_cards.wholesale_application_id = wholesale_application_information.id');
        // })
        with('wholesale_application_address' , 'wholesale_application_regulation_detail' , 'wholesale_application_authorization_detail' , 'wholesale_application_card')
        ->orderBy('created_at' , 'Desc')
        ->paginate(10);
        return view('admin.wholesale_applications.index', compact('wholesale_applications'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $wholesale_application =  WholesaleApplicationInformation::with('wholesale_application_address' , 'wholesale_application_regulation_detail' , 'wholesale_application_authorization_detail' , 'wholesale_application_card')
        ->where('id' , $id)
        ->orderBy('id' , 'Desc')->first();
        return view('admin.wholesale_applications.show' , compact('wholesale_application'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
