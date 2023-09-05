<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventoryLocation;

class AdminInventoryLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $inventory_locations = InventoryLocation::all();
        return view('admin.inventory_locations.index', compact('inventory_locations'));
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
        $inventory_location = InventoryLocation::find($id);
        if (!empty($inventory_location)) {
            if ($inventory_location->status == 1) {
                $inventory_location->update([
                    'status' => 0
                ]);
            } else {
                $inventory_location->update([
                    'status' => 1
                ]);
            }
            return redirect()->route('inventory-locations.index')->with('success', 'Inventory Location updated successfully');
        } else {
            return redirect()->route('inventory-locations.index')->with('error', 'Inventory Location not found');
        }
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
