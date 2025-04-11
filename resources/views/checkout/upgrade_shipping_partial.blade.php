<div id="upgrade_shipping_box" class="d-none">
    @if (!empty($admin_area_for_shipping) && strtolower($admin_area_for_shipping->option_value) == 'yes')
        <input type="hidden" name="upgrade_admin_control_shipping" id="upgrade_admin_control_shipping" value="true">
        <input type="hidden" name="shipment_error" id="shipment_error" value="{{$shipment_error}}">
        @if (!empty($products_weight) && $products_weight > 99)
            
            @php
                $ups_ground_value = 0;
                $upgrade_adding_surcharge = 0;
                $upgrade_shipment_plus_surcharge = 0;
                $upgrade_get_original_shipment_price = !empty($upgrade_shipment_price) ? $upgrade_shipment_price : 0;
                if (!empty($upgrade_surcharge_settings) && strtolower($upgrade_surcharge_settings->option_value) == 'yes') {
                if (!empty($upgrade_surcharge_type_settings_for_weight_greater_then_150) &&
                strtolower($upgrade_surcharge_type_settings_for_weight_greater_then_150->option_value) == 'percentage') {
                $upgrade_surcharge_value_greater_weight = $upgrade_get_original_shipment_price *
                (floatval($upgrade_surcharge_settings_for_weight_greater_then_150->option_value) / 100);
                } else {
                $upgrade_surcharge_value_greater_weight = floatval($upgrade_surcharge_settings_for_weight_greater_then_150->option_value);
                }
                } else {
                $upgrade_surcharge_value_greater_weight = 0;
                }

                $upgrade_shipment_plus_surcharge = $upgrade_get_original_shipment_price + $upgrade_surcharge_value_greater_weight;
                if ($upgrade_shipment_plus_surcharge > 0) {
                $upgrade_parcel_guard_price = (ceil(floatval($upgrade_cart_total) / 100) * 0.99);
                $upgrade_shipment_price = $upgrade_shipment_plus_surcharge + $upgrade_parcel_guard_price + $upgrade_extra_shipping_value +
                $upgrade_extra_charges_for_total_over_499;
                } else {
                $upgrade_shipment_price = $upgrade_shipment_plus_surcharge + $upgrade_extra_shipping_value + $upgrade_extra_charges_for_total_over_499;
                }
            @endphp
            <input type="hidden" name="upgrade_shipping_carrier_code" id="" value="{{$upgrade_shipping_carrier_code}}">
            <input type="hidden" name="upgrade_shipping_service_code" id="" value="{{$upgrade_shipping_service_code}}">
            <input type="hidden" name="upgrade_shipment_cost_single" id="upgrade_shipment_price_heavy_weight"
                value="{{!empty($upgrade_shipment_price) ? number_format($upgrade_shipment_price , 2, '.', '')  : 0 }}">
            <div class="row justify-content-center  align-items-center py-2">
                @if ($shipment_error == 1)
                    <div class="col-md-12">
                        <span class="upgrade_checkout_shipping_price text-danger">
                            There was an issue getting a freight quote, please try again later
                        </span>
                    </div>
                @else
                    <div class="col-md-9 col-8">
                        <span class="upgrade_checkout_shipping_heading">Shipment Price</span>
                    </div>
                    <div class="col-md-3 col-4 text-right">
                        <span class="upgrade_checkout_shipping_price">${{!empty($upgrade_shipment_price) ? number_format($upgrade_shipment_price , 2) :
                            0}}</span>
                    </div>
                @endif
            </div>
        @else
            <div class="row justify-content-center  align-items-center py-2">
                @if ($shipment_error == 1)
                    <div class="col-md-12">
                        <span class="upgrade_checkout_shipping_price text-danger">
                            There was an issue getting a freight quote, please try again later
                        </span>
                    </div>
                @else
                    @if (count($upgrade_admin_selected_shipping_quote) > 0)
                        @php
                        $upgrade_surcharge_for_lighter_weight = 0;
                        @endphp
                        <div class="col-md-12">
                            <p class="checkout_shipping_methods ml-0 mb-2">Shipping Methods</p>
                        </div>
                        @if (count($upgrade_admin_selected_shipping_quote) == 1)
                            @foreach ($upgrade_admin_selected_shipping_quote as $shipping_quote)
                                <?php
                                    $upgrade_shipment_cost_without_surcharge = $shipping_quote->shipmentCost + $shipping_quote->otherCost;
                                    if (!empty($upgrade_surcharge_settings) && strtolower($upgrade_surcharge_settings->option_value) == 'yes') {
                                        if (!empty( $shipping_quote->surcharge_type) && $shipping_quote->surcharge_type == 'fixed') {
                                            $upgrade_surcharge_for_lighter_weight = floatval($shipping_quote->surcharge_amount);
                                        } else {
                                            $upgrade_surcharge_for_lighter_weight = $upgrade_shipment_cost_without_surcharge * (floatval($shipping_quote->surcharge_amount) / 100);
                                        }
                                    } else {
                                        $upgrade_surcharge_for_lighter_weight = 0;
                                    }
                                    $upgrade_shipment_cost_with_surcharge_only = $upgrade_shipment_cost_without_surcharge + $upgrade_surcharge_for_lighter_weight;
                                    $upgrade_adding_shipping_cost_to_total = 0;
                                    $upgrade_parcel_guard_price = 0 ;
                                    if ($upgrade_shipment_cost_with_surcharge_only > 0) {
                                        $upgrade_parcel_guard_price = (ceil(floatval($upgrade_cart_total) / 100) * 0.99);
                                        $upgrade_shipment_cost_with_surcharge = $upgrade_shipment_cost_with_surcharge_only + $upgrade_parcel_guard_price + $upgrade_extra_shipping_value + $upgrade_extra_charges_for_total_over_499 ;
                                    } else {
                                        $upgrade_shipment_cost_with_surcharge = $upgrade_shipment_cost_with_surcharge_only + $upgrade_extra_shipping_value  + $upgrade_extra_charges_for_total_over_499;
                                    }
                                ?>

                                <input type="hidden" name="upgrade_surcharge_for_lighter_weight" id=""
                                    value="{{ number_format($upgrade_surcharge_for_lighter_weight , 2, '.', '')}}">
                                <input type="hidden" name="upgrade_original_shipping_cost_from_shipstation" id=""
                                    value="{{ number_format($upgrade_shipment_cost_without_surcharge , 2, '.', '')}}">
                                <input type="hidden" name="upgrade_shipping_carrier_code" id="" value="{{$upgrade_shipping_carrier_code}}">
                                <input type="radio" name="upgrade_shipping_service_code" id="" class="d-none" value="{{$shipping_quote->serviceCode}}"
                                    checked>
                                <div class="col-md-9 col-8">
                                    <input type="radio" name="upgrade_shipping_multi_price" class="upgrade_shipping_multi_price" id="upgrade_single_shipping_quote"
                                        value="{{!empty($upgrade_shipment_cost_with_surcharge) ? number_format($upgrade_shipment_cost_with_surcharge , 2, '.', '') : number_format($upgrade_shipment_cost_without_surcharge , 2, '.', '')}}"
                                        checked>
                                    <span class="checkout_upgrade_shipping_heading">{{$shipping_quote->serviceName}}</span>
                                </div>
                                <div class="col-md-3 col-4 text-right">
                                    <span class="upgrade_checkout_shipping_price">${{!empty($upgrade_shipment_cost_with_surcharge) ?
                                        number_format($upgrade_shipment_cost_with_surcharge , 2) : number_format($upgrade_shipment_cost_without_surcharge ,
                                        2)}}</span>
                                </div>
                                <input type="hidden" name="upgrade_shipment_cost_multiple" id="upgrade_shipment_price_single"
                                    value="{{!empty($upgrade_shipment_cost_with_surcharge) ? number_format($upgrade_shipment_cost_with_surcharge , 2, '.', '') : number_format($upgrade_shipment_cost_without_surcharge , 2, '.', '')}}">
                            @endforeach
                        @else
                            @foreach ($upgrade_admin_selected_shipping_quote as $shipping_quote)
                                @php
                                    $upgrade_shipment_cost_without_surcharge = $shipping_quote->shipmentCost + $shipping_quote->otherCost;
                                    if (!empty($upgrade_surcharge_settings) && strtolower($upgrade_surcharge_settings->option_value) == 'yes') {
                                    if (!empty( $shipping_quote->surcharge_type) && $shipping_quote->surcharge_type == 'fixed') {
                                    $upgrade_surcharge_for_lighter_weight = floatval($shipping_quote->surcharge_amount);
                                    } else {
                                    $upgrade_surcharge_for_lighter_weight = $upgrade_shipment_cost_without_surcharge * (floatval($shipping_quote->surcharge_amount) /
                                    100);
                                    }
                                    } else {
                                    $upgrade_surcharge_for_lighter_weight = 0;
                                    }
                                    $upgrade_shipment_cost_with_surcharge_only = $upgrade_shipment_cost_without_surcharge + $upgrade_surcharge_for_lighter_weight;

                                    $upgrade_parcel_guard_price = 0 ;
                                    if ($upgrade_shipment_cost_with_surcharge_only > 0) {
                                    $upgrade_parcel_guard_price = (ceil(floatval($upgrade_cart_total) / 100) * 0.99);
                                    $upgrade_shipment_cost_with_surcharge = $upgrade_shipment_cost_with_surcharge_only + $upgrade_parcel_guard_price + $upgrade_extra_shipping_value +
                                    $upgrade_extra_charges_for_total_over_499;
                                    } else {
                                    $upgrade_shipment_cost_with_surcharge = $upgrade_shipment_cost_with_surcharge_only + $upgrade_extra_shipping_value +
                                    $upgrade_extra_charges_for_total_over_499;
                                    }


                                    $ups_ground_value = 0;


                                    if ($shipping_quote->serviceCode === 'ups_ground') {
                                        $ups_ground_value = !empty($upgrade_shipment_cost_with_surcharge) ? $upgrade_shipment_cost_with_surcharge : $upgrade_shipment_cost_without_surcharge;                                    
                                    }


                                    if ($shipping_quote->serviceCode !== 'ups_ground') {
                                        $upgrade_shipment_cost_with_surcharge = $upgrade_shipment_cost_with_surcharge - $ups_ground_value;
                                        $upgrade_shipment_cost_without_surcharge = $upgrade_shipment_cost_without_surcharge - $ups_ground_value;                                  
                                    }



                                    if ($shipping_quote->serviceCode === 'ups_ground') {
                                        continue;
                                    }



                                @endphp
                                <input type="hidden" name="upgrade_surcharge_for_lighter_weight" id=""
                                    value="{{ number_format($upgrade_surcharge_for_lighter_weight , 2, '.', '')}}">
                                <div class="col-md-9 col-8">
                                    <input type="hidden" name="upgrade_original_shipping_cost_from_shipstation" id=""
                                        value="{{ number_format($upgrade_shipment_cost_without_surcharge , 2, '.', '')}}">
                                    <input type="hidden" name="upgrade_shipping_carrier_code" id="" value="{{$upgrade_shipping_carrier_code}}">
                                    <input type="radio" name="upgrade_shipping_service_code" id="" class="upgrade_shipping_service_code d-none"
                                        value="{{$shipping_quote->serviceCode}}">
                                    <input type="radio" name="upgrade_shipping_multi_price" class="upgrade_shipping_multi_price" id="" 
                                    upgrade_shipping_cost_with_surcharge="{{!empty($upgrade_shipment_cost_with_surcharge) ?
                                    number_format($upgrade_shipment_cost_with_surcharge , 2, '.', '') : number_format($upgrade_shipment_cost_without_surcharge , 2,
                                    '.', '')}}" value="{{!empty($upgrade_shipment_cost_with_surcharge) ? number_format($upgrade_shipment_cost_with_surcharge , 2,
                                    '.', '') : number_format($upgrade_shipment_cost_without_surcharge , 2, '.', '')}}" onclick="upgrade_assign_service_code(this)">
                                    <span class="checkout_upgrade_shipping_heading">{{$shipping_quote->serviceName}}</span>
                                </div>
                                <div class="col-md-3 col-4 text-right">
                                    <span class="upgrade_checkout_shipping_price">${{!empty($upgrade_shipment_cost_with_surcharge) ?
                                        number_format($upgrade_shipment_cost_with_surcharge , 2) : number_format($upgrade_shipment_cost_without_surcharge ,
                                        2)}}</span>
                                </div>
                                <input type="hidden" name="upgrade_shipment_cost_multiple" id="upgrade_shipment_price_{{$shipping_quote->serviceCode}}"
                                    class="upgrade_shipstation_multi_shipment_price"
                                    value="{{!empty($upgrade_shipment_cost_with_surcharge) ? number_format($upgrade_shipment_cost_with_surcharge , 2, '.', '') : number_format($upgrade_shipment_cost_without_surcharge , 2, '.', '')}}">
                            @endforeach
                        @endif
                    @endif
                @endif
            </div>
        @endif
    @endif
</div>
