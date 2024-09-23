<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\ShippingQuoteSetting;
use App\Models\SelectedShippingQuote;
class ShippingQuoteSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shipping_quotes_settings = ShippingQuoteSetting::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.shipping_quote_settings.index', compact('shipping_quotes_settings'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.shipping_quote_settings.create');
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
            'service_name' => 'required',
            'service_code' => 'required',
            'carrier_name' => 'required',
            'carrier_code' => 'required',
            // 'type' => 'required',
            'surcharge_value' => 'required',
            'surcharge_type' => 'required',
            // 'status' => 'required',
        ]);
        
        $shipping_quote_setting = new ShippingQuoteSetting();
        $shipping_quote_setting->service_name = $request->service_name;
        $shipping_quote_setting->service_code = $request->service_code;
        $shipping_quote_setting->carrier_name = $request->carrier_name;
        $shipping_quote_setting->carrier_code = $request->carrier_code;
        $shipping_quote_setting->type = $request->type;
        $shipping_quote_setting->surcharge_value = $request->surcharge_value;
        $shipping_quote_setting->surcharge_type = $request->surcharge_type;
        $shipping_quote_setting->status = $request->status;
        $shipping_quote_setting->save();
        return redirect()->route('shipping_quotes.settings.index')->with('success', 'Shipping Quote Setting created successfully.');
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
        $shipping_quote_setting = ShippingQuoteSetting::find($id);
        return view('admin.shipping_quote_settings.edit', compact('shipping_quote_setting'));
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
        $shipping_quote_setting = ShippingQuoteSetting::find($id);
        if ($shipping_quote_setting) {
            $request->validate([
                'service_name' => 'required',
                'service_code' => 'required',
                'carrier_name' => 'required',
                'carrier_code' => 'required',
                // 'type' => 'required',
                'surcharge_value' => 'required',
                'surcharge_type' => 'required',
                // 'status' => 'required',
            ]);
            $shipping_quote_setting->service_name = $request->service_name;
            $shipping_quote_setting->service_code = $request->service_code;
            $shipping_quote_setting->carrier_name = $request->carrier_name;
            $shipping_quote_setting->carrier_code = $request->carrier_code;
            $shipping_quote_setting->type = $request->type;
            $shipping_quote_setting->surcharge_value = $request->surcharge_value;
            $shipping_quote_setting->surcharge_type = $request->surcharge_type;
            $shipping_quote_setting->status = $request->status;
            $shipping_quote_setting->save();
            return redirect()->route('shipping_quotes.settings.index')->with('success', 'Shipping Quote Setting updated successfully.');
        } else {
            return redirect()->route('shipping_quotes.settings.index')->with('error', 'Shipping Quote Setting not found.');
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $shipping_quotes_settings = ShippingQuoteSetting::find($id);
        if ($shipping_quotes_settings) {
            $selected_shipping_quote = SelectedShippingQuote::where('shipping_quote_id', $id)->first();
            if ($selected_shipping_quote) {
                $selected_shipping_quote->delete();
            }
            $shipping_quotes_settings->delete();
            return redirect()->route('shipping_quotes.settings.index')->with('success', 'Shipping Quote Setting deleted successfully.');
        } else {
            return redirect()->route('shipping_quotes.settings.index')->with('error', 'Shipping Quote Setting not found.');
        }
    }
}
