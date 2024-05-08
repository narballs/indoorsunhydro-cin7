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
            'discount_variation' => 'required',
            'discount_variation_value' => 'required',
            // 'minimum_purchase_requirements' => 'required',
            'customer_eligibility' => 'required',
            'status' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'max_discount_uses' => 'required',
        ]);

        if (!empty($request->max_discount_uses)) {
            if ((strtolower($request->max_discount_uses)) == 'none') {
                $limit_per_user = null;
                $limit_max_times = null;
            } elseif (strtolower($request->max_discount_uses) == 'limit for user') {
                $limit_per_user = $request->limit_per_user;
                $limit_max_times = null;
            } elseif (strtolower($request->max_discount_uses) == 'limit max times') {
                $limit_per_user = null;
                $limit_max_times = $request->max_usage_count;
                $request->validate([
                    'max_usage_count' => 'required',
                ]);
            }
        }
        
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
            'max_discount_uses' => $request->max_discount_uses,
            'max_usage_count' => $limit_max_times,
            'limit_per_user' => $limit_per_user,
            'usage_count' => $request->usage_count,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,

        ]);

        if (strtolower($discount->customer_eligibility) == 'specific customers') {
            $contact_ids = $request->contactids;
            if (!empty($contact_ids)) {
                if ((count($contact_ids) > 0)) {
                    foreach ($contact_ids as $contact_id) {
                        CustomerDiscount::create([
                            'discount_id' => $discount->id,
                            'contact_id' => $contact_id,
                        ]);
                    }
                }
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
        $customerDiscounts = CustomerDiscount::with('contact')->where('discount_id', $discount->id)->get();
        $customerDiscountsArray = $customerDiscounts->pluck('contact_id')->toArray();
        return view('admin.discounts.edit', compact('discount' , 'customerDiscounts' , 'customerDiscountsArray'));
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
            'type' => 'required',
            'mode' => 'required',
            'discount_variation' => 'required',
            'discount_variation_value' => 'required',
            // 'minimum_purchase_requirements' => 'required',
            'customer_eligibility' => 'required',
            'status' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'max_discount_uses' => 'required',
        ]);

        $limit_per_user = null;
        $limit_max_times = null;
        $discount = Discount::findOrFail($id);
        if (!empty($request->max_discount_uses)) {
            if ((strtolower($request->max_discount_uses)) == 'none') {
                $limit_per_user = null;
                $limit_max_times = null;
            } elseif (strtolower($request->max_discount_uses) == 'limit for user') {
                $limit_per_user = $request->limit_per_user;
                $limit_max_times = null;
            } elseif (strtolower($request->max_discount_uses) == 'limit max times') {
                $limit_per_user = null;
                $limit_max_times = $request->max_usage_count;
                $request->validate([
                    'max_usage_count' => 'required',
                ]);
            }
        }

        if ($request->mode === 'Manuall') {
            $request->validate([
                'discount_code' => 'required',
            ]);
        }
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
            'max_discount_uses' => $request->max_discount_uses,
            'max_usage_count' => $limit_max_times,
            'limit_per_user' => $limit_per_user,
            'usage_count' => $request->usage_count,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
        ]);
        if (strtolower($discount->customer_eligibility) == 'specific customers') {
            $contact_ids = $request->contactids;
            if (!empty($contact_ids)) {
                if ((count($contact_ids) > 0)) {
                    $customerArray = explode(',', $contact_ids[0]);
                    foreach ($customerArray as $contact_id) {
                        if ($contact_id != '') {
                            CustomerDiscount::where('contact_id', $contact_id)->where('discount_id', $discount->id)->delete();
                            CustomerDiscount::create([
                                'discount_id' => $discount->id,
                                'contact_id' => $contact_id,
                            ]);
                        }
                    }
                }
            }
        }
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
        $customerDiscounts = CustomerDiscount::where('discount_id', $discount->id)->get();
        foreach ($customerDiscounts as $customerDiscount) {
            
            $customerDiscount->delete();
        }
        $customerDiscountsUses = CustomerDiscountUses::where('discount_id', $discount->id)->get();
        foreach ($customerDiscountsUses as $customerDiscountsUse) {
            
            $customerDiscountsUse->delete();
        }
        $discount->delete();
        return redirect()->route('discounts.index')->with('success', 'Discount deleted successfully.');      
    }

    public function discounts_duplicate(Request $request) {
        $discount_id = $request->discount_id;
        $discount =  Discount::findOrFail($discount_id);
        $new_discount = $discount->replicate();
        $new_discount->save();

        $update_discount = Discount::findOrFail($new_discount->id);
        $discount_code_name = $discount->discount_code.'-copy-' . $new_discount->id;
        $update_discount->discount_code = $discount_code_name;
        $update_discount->usage_count = 0;
        $update_discount->save();

        return redirect()->route('discounts.index')->with('success', 'Discount duplicated successfully.');
                
    }

    public function redeemed_discount_users() {
        $customerDiscountUses = CustomerDiscountUses::with('discount','contact')->paginate(10);
        return view('admin.discounts.redeemed_discount_users', compact('customerDiscountUses'));
    }
}
