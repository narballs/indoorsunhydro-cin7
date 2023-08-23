<?php

namespace App\Http\Controllers\Admin;
use \App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OperationalZipCode;

class OperationalZipCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $operationalZipCodes = OperationalZipCode::orderBy('id' , 'Desc')->paginate(20);
        return view('admin.operational_zip_codes.index', compact('operationalZipCodes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.operational_zip_codes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'zip_code' => 'required|unique:operational_zip_codes|max:255',
            'status' => 'required',
        ]);
        OperationalZipCode::create($request->all());
        return redirect()->route('operational-zip-codes.index')->with('success', 'Operational Zip Code created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $operational_zip_code = OperationalZipCode::find($id);
        return view('admin.operational_zip_codes.edit', compact('operational_zip_code'));
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
        $request->validate([            
            'status' => 'required',
            'zip_code' => 'required|unique:operational_zip_codes|max:255',
        ]);
        OperationalZipCode::find($id)->update($request->all());
        return redirect()->route('operational-zip-codes.index')->with('success', 'Operational Zip Code updated successfully.'); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        OperationalZipCode::find($id)->delete();
        return redirect()->route('operational-zip-codes.index')->with('success', 'Operational Zip Code deleted successfully.');
    }
}
