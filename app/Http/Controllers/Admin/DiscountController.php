<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Discount;
use App\Models\CustomerDiscountUses;
use App\Models\CustomerDiscount;

use Illuminate\Http\Request;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $discounts = Discount::paginate(10);
        return view('admin.discounts.index', compact('discounts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.discounts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $validated = $request->validate([
            // 'name' => 'required',
            'type' => 'required',
            'mode' => 'required',
            'discount_code' => 'required',
            'minimum_purchase_requirements' => 'required',
            'customer_eligibility' => 'required',
            'status' => 'required',
        ]);

        $discount = Discount::create([
            'name' => $request->name,
            'type' => $request->type,
            'mode' => $request->mode,
            'discount_code' => $request->discount_code,
            'minimum_purchase_requirements' => $request->minimum_purchase_requirements,
            'minimum_quantity_items' => $request->minimum_quantity_items,
            'customer_eligibility' => $request->customer_eligibility,
            'discount_variation' => $request->discount_variation,
            'discount_variation_value' => $request->discount_variation_value,
            'minimum_purchase_amount' => $request->minimum_purchase_amount,
            'max_usage_count' => $request->max_usage_count,
            'limit_per_user' => $request->limit_per_user,
            'usage_count' => $request->usage_count,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,

        ]);

        $contact_ids = $request->add_contact_ids;
        if ((count($contact_ids) > 0) && $contact_ids != null) {
            foreach ($contact_ids as $contact_id) {
                CustomerDiscount::create([
                    'discount_id' => $discount->id,
                    'contact_id' => $contact_id,
                ]);
            }
        }
        return redirect()->route('discounts.index')->with('success', 'Discount created successfully.');
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
        $discount = Discount::findOrFail($id);
        return view('admin.discounts.edit', compact('discount'));
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
        $validated = $request->validate([
            'name' => 'required',
            'type' => 'required',
            'mode' => 'required',
            'discount_code' => 'required',
            'minimum_purchase_requirements' => 'required',
            'customer_eligibility' => 'required',
            'status' => 'required',
        ]);

        $discount = Discount::findOrFail($id);
        $discount->update([
            'name' => $request->name,
            'type' => $request->type,
            'mode' => $request->mode,
            'discount_code' => $request->discount_code,
            'minimum_purchase_requirements' => $request->minimum_purchase_requirements,
            'minimum_quantity_items' => $request->minimum_quantity_items,
            'customer_eligibility' => $request->customer_eligibility,
            'discount_variation' => $request->discount_variation,
            'discount_variation_value' => $request->discount_variation_value,
            'minimum_purchase_amount' => $request->minimum_purchase_amount,
            'max_usage_count' => $request->max_usage_count,
            'limit_per_user' => $request->limit_per_user,
            'usage_count' => $request->usage_count,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
        ]);
        return redirect()->route('discounts.index')->with('success', 'Discount updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
            
        $discount = Discount::findOrFail($id);
        $customerDiscounts = CustomerDiscount::where('discount_id', $id)->get();
        foreach ($customerDiscounts as $customerDiscount) {
            $customerDiscountUses = CustomerDiscountUses::where('customer_discount_id', $customerDiscount->id)->get();
            foreach ($customerDiscountUses as $customerDiscountUse) {
                $customerDiscountUse->delete();
            }
            $customerDiscount->delete();
        }
        $discount->delete();
        return redirect()->route('discounts.index')->with('success', 'Discount deleted successfully.');      
    }
}
