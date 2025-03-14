<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\MailHelper;
use App\Helpers\SettingHelper;
use \App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Middleware\IsAdmin;
use App\Models\AdminSetting;
use App\Models\AIImageGeneration;
use App\Models\AiQuestion;
use App\Models\ApiEventLog;
use App\Models\ApiKeys;
use App\Models\AutoLabelSetting;
use App\Models\AutoLabelTimeRange;
use App\Models\Contact;
use App\Models\ContactLogs;
use App\Models\ProductStockNotification;
use App\Models\SelectedShippingQuote;
use App\Models\ShippingQuote;
use App\Models\ShipstationApiLogs;
use App\Models\SpecificAdminNotification;
use App\Models\SurchargeSetting;
use App\Models\User;
use App\Models\UserLog;
use Carbon\Carbon;
use Facade\FlareClient\Time\Time;
use GrahamCampbell\ResultType\Success;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminSettingsController extends Controller
{
    function __construct()
    {
        $this->middleware(['role:Admin']);

    }
    
    public function autoFullfill(Request $request) {
        // dd($request->all());
        $option = AdminSetting::where('option_name', 'auto_full_fill')->first();
        if ($option->option_value == 1) {
            $option->option_value = 0;
        }
        else {
            $option->option_value = 1;
        }
        $option->save();
        return response()->json([
                'success' => true, 
                'msg' => 'Updated!'
            ]);
    }
    public function autoCreateLabel(Request $request) {
        // dd($request->all());
        $option = AdminSetting::where('option_name', 'auto_create_label')->first();
        if (strtolower($option->option_value) == 'yes') {
            $option->option_value = 'No';
        }
        else {
            $option->option_value = 'Yes';
        }
        $option->save();
        return response()->json([
                'success' => true, 
                'msg' => 'Updated!'
            ]);
    }

    public function index() {
        $settings = AdminSetting::where('is_visible', true)->get();
        return view('admin.settings.index', compact('settings'));
    }

    // public function create() {
    //     return view('admin.settings.create');
    // }

    public function store(Request $request) {
        $request->validate([
            'option_name' => 'required',
            'type' => 'required',
           // 'option_value' => 'required'
        ]);
        $setting = new AdminSetting();
        $setting->option_name = $request->option_name;
        $setting->type = $request->type;
        if($request->type == 'text') {
            $request->validate(
                [
                    'option_value_text' => 'required'
                ],
                [
                    'option_value_text.required' => 'Option value is required'
                ]
            );
            $setting->option_value = $request->option_value_text;
        }
        elseif($request->type == 'boolean') {
            $request->validate(
                [
                    'option_value_boolean' => 'required'
                ],
                [
                    'option_value_boolean.required' => 'Option value is required'
                ]
            );
            $setting->option_value = $request->option_value_boolean;
        }
        elseif($request->type == 'number') {
            $request->validate(
                [
                    'option_value_number' => 'required'
                ],
                [
                    'option_value_number.required' => 'Option value is required'
                ]
            );
            $setting->option_value = $request->option_value_number;
        }
        elseif($request->type == 'yes/no') {
            $request->validate(
                [
                    'option_value_yes_no' => 'required'
                ],
                [
                    'option_value_yes_no.required' => 'Option value is required'
                ]
            );
            $setting->option_value = $request->option_value_yes_no;
        }
        $setting->save();
        return redirect()->route('admin.settings.index')->with('success', 'Setting created successfully.');
    }

    public function edit($id) {
        $setting = AdminSetting::findOrFail($id);
        $option_value_text = '';
        $option_value_number = '';
        $option_value_boolean = '';
        $option_value_yes_no = '';
        if($setting->type == 'text') {
            $option_value_text = $setting->option_value;   
        }else if($setting->type == 'number') {
            $option_value_number = $setting->option_value; 
        }else if($setting->type == 'boolean') {
            $option_value_boolean = $setting->option_value; 
        }elseif($setting->type == 'yes/no') {
            $option_value_yes_no = $setting->option_value;
        }
        return view('admin.settings.edit', compact('setting' , 'option_value_text' , 'option_value_number' , 'option_value_boolean' , 'option_value_yes_no'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'option_name' => 'required'
        ]);
        $setting = AdminSetting::where('id', $id)->first();
        if($setting->type == 'text') {
            // $request->validate(
            //     [
            //         'option_value_text' => 'required'
            //     ],
            //     [
            //         'option_value_text.required' => 'Option value is required'
            //     ]
            // );
            $setting->option_value = $request->option_value_text;
        }
        elseif($setting->type == 'boolean') {
            $request->validate(
                [
                    'option_value_boolean' => 'required'
                ],
                [
                    'option_value_boolean.required' => 'Option value is required'
                ]
            );
            $setting->option_value = $request->option_value_boolean;
        }
        elseif($setting->type == 'number') {
            $request->validate(
                [
                    'option_value_number' => 'required'
                ],
                [
                    'option_value_number.required' => 'Option value is required'
                ]
            );
            $setting->option_value = $request->option_value_number;
        }
        elseif($setting->type == 'yes/no') {
            $request->validate(
                [
                    'option_value_yes_no' => 'required'
                ],
                [
                    'option_value_yes_no.required' => 'Option value is required'
                ]
            );
            $setting->option_value = $request->option_value_yes_no;
        }
        $setting->save(); 
        return redirect()->route('admin.settings.index')->with('success', 'Setting updated successfully.');
    }

    public function delete($id) {
        $setting = AdminSetting::findOrFail($id);
        $setting->delete();
        return redirect()->route('admin.settings.index')->with('success', 'Setting deleted successfully.');
    }

    // search customers
    public function search_customer(Request $request) {
        $search = $request->search;
        if (!empty($search)) {
            $contacts = Contact::with('customerDiscount')->where('type', 'Customer')
            ->where('status', 1)
            ->where('firstName', 'LIKE', "%{$search}%")
            ->orWhere('lastName', 'LIKE', "%{$search}%")
            ->orWhere('email', 'LIKE', "%{$search}%")
            ->orWhere('company', 'LIKE', "%{$search}%")
            ->get();
            return response()->json([
                'success' => true,
                'contacts' => $contacts
            ]);
        } else {
            return response()->json([
                'success' => false,
                'msg' => 'No customer found'
            ]);
        }
    }

    // Recycle  Bin 
    public function recycle_bin() {
        $deletedContacts = Contact::onlyTrashed()->paginate(10);
        return view('admin.recycle_bin.index', compact('deletedContacts'));
    }

    // restore contact
    public function restore_contact($id) {
        $contact = Contact::withTrashed()->findOrFail($id);
        $contact->is_deleted = null;
        $contact->restore();
        if (!empty($contact->user_id)) {
            $user_id = $contact->id;
        } else {
            $user_id = null;
        }

        $contact_log = new ContactLogs();
        $contact_log->user_id = $contact->user_id;
        $contact_log->action_by = auth()->user()->id;
        $contact_log->action = 'Restoration';
        $contact_log->description = !empty($contact->email) ? $contact->email .' '. 'is ' . 'restored by ' . auth()->user()->email .' ' .'at'. ' '. now()  : $contact->firstName .' '. $contact->lastName  . 'is ' . 'restored by ' . auth()->user()->email .' ' .'at'. ' '. now();
        $contact_log->save();

        $user_log = new UserLog();
        $user_log->user_id = auth()->user()->id;
        $user_log->contact_id = !empty($contact->contact_id) ? $contact->contact_id : $user_id;
        $user_log->secondary_id = !empty($contact->secondary_id) ? $contact->secondary_id : $user_id;
        $user_log->action = 'Restoration';
        $user_log->user_notes = !empty($contact->email) ? $contact->email .' '. 'is ' . 'restored by ' . auth()->user()->email .' ' .'at'. ' '. now() : $contact->firstName .' '. $contact->lastName  . 'is ' . 'deleted by ' . auth()->user()->email .' ' .'at'. ' '. now();
        $user_log->save();

        $user = User::withTrashed()->where('id', $contact->user_id)->first();
        if (!empty($user)) {
            $user->is_deleted = null;
            $user->restore();

            $user_log = new UserLog();
            $user_log->user_id = auth()->user()->id;
            $user_log->contact_id = !empty($contact->contact_id) ? $contact->contact_id : $contact->user_id;
            $user_log->secondary_id = !empty($contact->secondary_id) ? $contact->secondary_id : $contact->user_id;
            $user_log->action = 'Restoration';
            $user_log->user_notes = !empty($contact->email) ? $contact->email .' ' . 'is ' . 'restored by ' . auth()->user()->email : $contact->firstName .' '. $contact->lastName .' ' . 'is ' . 'deleted by ' . auth()->user()->email .' ' .'at'. ' '. now();
            $user_log->save();
        }
        if (!empty($contact) && !empty($user)) {
            return redirect()->back()->with('success', 'Contact restored successfully.');
        } elseif(!empty($contact) && empty($user)) {
            return redirect()->back()->with('success', 'Contact restored successfully.');
        } else {
            return redirect()->back()->with('error', 'Contact not restored.');
        }
    }

    // delete contact permanently
    public function delete_contact_permanently($id) {
        $contact = Contact::withTrashed()->findOrFail($id);
        $user = User::withTrashed()->where('id', $contact->user_id)->first();
        $contact_log = new ContactLogs();
        $contact_log->user_id = $contact->user_id;
        $contact_log->action_by = auth()->user()->id;
        $contact_log->action = 'Deletion';
        $contact_log->description = !empty($contact->email) ? $contact->email .' ' . 'is ' . 'deleted by ' . auth()->user()->email .' ' .'at'. ' '. now() : $contact->firstName .' '. $contact->lastName  . 'is ' . 'deleted by ' . auth()->user()->email .' ' .'at'. ' '. now();
        $contact_log->save();

        $user_log = new UserLog();
        $user_log->user_id = auth()->user()->id;
        $user_log->contact_id = !empty($contact->contact_id) ? $contact->contact_id : $contact->id;
        $user_log->secondary_id = !empty($contact->secondary_id) ? $contact->secondary_id : $contact->id;
        $user_log->action = 'Deletion';
        $user_log->user_notes = !empty($contact->email) ? $contact->email.' '. 'is ' . 'deleted by ' . auth()->user()->email .' ' .'at'. ' '. now() : $contact->firstName .' '. $contact->lastName .' ' . 'is ' . 'deleted by ' . auth()->user()->email .' ' .'at'. ' '. now();
        $user_log->save();

        $contact->delete();
        $user->delete();
        if ($contact && $user) {
            return redirect()->route('recycle_bin')->with('success', 'Contact deleted permanently.');
        } else {
            return redirect()->route('recycle_bin')->with('error', 'Contact not deleted.');
        }
    }

    public function contact_logs() {
        $contact_logs = ContactLogs::with('adminuser' , 'user')->orderBY('id' , 'desc')->paginate(10);
        return view('admin.contact_logs.index', compact('contact_logs'));
    }

    public function notify_users() {
        $auto_notify = false;
        $auto_notify = AdminSetting::where('option_name', 'auto_notify')->first();
        $auto_notify_value = $auto_notify->option_value;
        if (!empty($auto_notify_value) && strtolower($auto_notify_value) ==  'yes') {
            $auto_notify = true;
        } else {
            $auto_notify = false;
        }
        $product_stock_notification_users = ProductStockNotification::with('product' , 'productStockNotificationAlternatives')->orderBy('created_at' , 'Desc')->paginate(10);
        return view ('admin.product_stock_notification_users', compact('product_stock_notification_users' , 'auto_notify'));
    }

    public function product_stock_notification (Request $request) {
        $user_stock_notification  = $request->product_stock_notification_user;
        $product_stock_notification = ProductStockNotification::with('product' , 'product.options')
        ->where('id', $user_stock_notification)
        ->first();
        if (!empty($product_stock_notification)) {
            
            $data = [
                'email' => $product_stock_notification->email,
                'product' => $product_stock_notification->product,
                'product_options' => $product_stock_notification->product->options,
                'subject' => 'Product Stock Notification',
                'request_title' => 'The Item you have been waiting for is back in stock',
                'from' => SettingHelper::getSetting('noreply_email_address')
            ];

            $mail = MailHelper::stockMailNotification('emails.user-stock-notification', $data);
            if ($mail) {
                $product_stock_notification->is_notified = 1;
                $product_stock_notification->status = 1;
                $product_stock_notification->save();
                return back()->with('success', 'User notified successfully.');
            } else {
                return back()->with('error', 'User not notified due to invalid email.');
            }

        } else {
            return back()->with('error', 'User not found.');
        }
    }

    public function all_admins () {
        $admin_users = DB::table('model_has_roles')->where('role_id', 1)->pluck('model_id');

        $admin_users = $admin_users->toArray();

        $admins = User::select("id" , "email")
            ->whereIn('id', $admin_users)
            ->get();
        $specific_admins = SpecificAdminNotification::pluck('user_id')->toArray();
        return view('admin.all_admins.index', compact('admins' , 'specific_admins'));
    }

    public function send_email_to_specific_admin (Request $request) {
        $admin_users = $request->admin_users;
        $check_previous_admins = SpecificAdminNotification::pluck('user_id')->toArray();
        $update_previous_admins = SpecificAdminNotification::whereIn('user_id', $check_previous_admins)->delete();
        $status = false;
        if ( !empty($admin_users)) {
            foreach ($admin_users as $admin_user) {
                $specific_admin = new SpecificAdminNotification();
                $specific_admin->user_id = $admin_user;
                $specific_admin->email = User::where('id', $admin_user)->first()->email;
                $specific_admin->save();
            }

            $status = true;
        } else {
            $status = false;
        }

        return response()->json([
            'status' => $status,
            'msg' => 'Admins Selected Succesfully'
        ]);
    }

    public function shipping_quotes() {
        $shipping_quotes = ShippingQuote::with('selected_shipping_quote')->get();
        $surcharge_settings = SurchargeSetting::first();
        return view('admin.shipping_quotes.index', compact('shipping_quotes', 'surcharge_settings'));
    }

    public function update_shipping_quotes(Request $request) {
        $shipping_quotes = $request->shipping_quote;
        $selected_shipping_quotes = SelectedShippingQuote::all();
        if (!empty($selected_shipping_quotes)) {
            foreach ($selected_shipping_quotes as $selected_shipping_quote) {
                $selected_shipping_quote->delete();
            }
        }
        if (!empty($shipping_quotes)) {
            foreach ($shipping_quotes as $shipping_quote) {
                $selected_shipping_quote = new SelectedShippingQuote();
                $selected_shipping_quote->shipping_quote_id = $shipping_quote;
                $selected_shipping_quote->save();
            }
        }

        $check_surcharge_settings = SurchargeSetting::first();
        if (!empty($check_surcharge_settings)) {
            $surcharge_settings = SurchargeSetting::first();
            $surcharge_settings->delete();

            $adding_surcharges = new SurchargeSetting();
            $adding_surcharges->apply_surcharge = !empty($request->apply_surcharge) ? 1 : 0;
            $adding_surcharges->surcharge_type = $request->surcharge_type;
            $adding_surcharges->surcharge_value = $request->surcharge_value;
            $adding_surcharges->save();
            
        } else {
            if (!empty($request->surcharge_value) && !empty($request->surcharge_type)) {
                $adding_surcharges = new SurchargeSetting();
                $adding_surcharges->apply_surcharge = !empty($request->apply_surcharge) ? 1 : 0;
                $adding_surcharges->surcharge_type = $request->surcharge_type;
                $adding_surcharges->surcharge_value = $request->surcharge_value;
                $adding_surcharges->save();
            }
        }
        
        return redirect()->back()->with('success', 'Shipping quotes updated successfully.');
    }

    public function empty_failed_jobs() {
        $failed_jobs = DB::table('failed_jobs')->get();
        if (count($failed_jobs) > 0) {
            DB::table('failed_jobs')->truncate();
            return response()->json([
                'status' => 'success',
                'msg' => 'Failed jobs cleared successfully.'
            ]);
        }
        return response()->json([
            'status' => 'error',
            'msg' => 'Failed jobs not found.'
        ]);
    }


    // AI Questions

    public function create_ai_question() {
        return view('admin.ai_question.create');
    }


    public function store_ai_question(Request $request) {
        $validated = $request->validate([
            'question' => 'required',
        ]);
        $ai_question = AiQuestion::create([
            'question' => $request->question,
        ]);
        return redirect()->route('ai_questions')->with('success', 'Ai Question created successfully.');
    }

    public function ai_questions() {
        $ai_questions = AiQuestion::paginate(10);
        return view('admin.ai_question.index', compact('ai_questions'));
    }

    public function edit_ai_question($id) {
        $ai_question = AiQuestion::findOrFail($id);
        return view('admin.ai_question.edit', compact('ai_question'));
    }

    public function update_ai_question(Request $request, $id) {
        $validated = $request->validate([
            'question' => 'required',
        ]);
        $ai_question = AiQuestion::findOrFail($id);
        $ai_question->update([
            'question' => $request->question,
        ]);
        return redirect()->route('ai_questions')->with('success', 'Ai Question updated successfully.');
    }

    public function delete_ai_question($id) {
        $ai_question = AiQuestion::findOrFail($id);
        $ai_question->delete();
        return redirect()->route('ai_questions')->with('success', 'Ai Question deleted successfully.');
    }



    

    public function show_label_settings()
    {
        $auto_label_settings = AutoLabelSetting::with('timeRanges')->first();
        $timeRanges = $auto_label_settings ? $auto_label_settings->timeRanges : [];

        // Convert time ranges to 24-hour format (H:i) for input[type="time"]
        $start_times = [];
        $end_times = [];
        
        foreach ($timeRanges as $timeRange) {
            // Convert stored time to America/Los_Angeles timezone for display
            $start_times[] = Carbon::parse($timeRange->start_time)->timezone('America/Los_Angeles')->format('H:i');
            $end_times[] = Carbon::parse($timeRange->end_time)->timezone('America/Los_Angeles')->format('H:i');
        }

        // Handle selected days of the week (if any), ensuring it's always an array
        $selectedDays = isset($auto_label_settings) && !empty($auto_label_settings->days_of_week)
                        ? json_decode($auto_label_settings->days_of_week, true)
                        : [];

        return view('admin.auto_label_settings.index', compact('auto_label_settings', 'start_times', 'end_times', 'selectedDays', 'timeRanges'));
    }
        
    public function update_label_settings(Request $request)
    {
        // Validate the request input
        $request->validate([
            'time_ranges' => 'required|array',
        ]);

        // Retrieve the first auto label setting or create a new one
        $autoLabelSetting = AutoLabelSetting::first();

        if ($autoLabelSetting) {
            // Update existing auto label settings
            $autoLabelSetting->days_of_week = json_encode($request->days_of_week);
            $autoLabelSetting->delay_processing = $request->delay_processing;
            $autoLabelSetting->delay_duration = $request->delay_duration;
            $autoLabelSetting->delay_unit = $request->delay_unit;
            $autoLabelSetting->save();
        } else {
            // Create new auto label setting
            $autoLabelSetting = new AutoLabelSetting();
            $autoLabelSetting->days_of_week = json_encode($request->days_of_week);
            $autoLabelSetting->delay_processing = $request->delay_processing;
            $autoLabelSetting->delay_duration = $request->delay_duration;
            $autoLabelSetting->delay_unit = $request->delay_unit;
            $autoLabelSetting->save();
        }

        // Delete any existing time ranges for the current auto label setting
        $autoLabelSetting->timeRanges()->delete();

        // Insert new time ranges with timezone conversion to 'America/Los_Angeles'
        foreach ($request->time_ranges as $index => $timeRange) {
            // Split the time range string into start and end times
            list($start_minutes, $end_minutes) = explode(';', $timeRange);

            // Convert the minutes into hours and minutes format (H:i)
            $start_time = Carbon::createFromFormat('H', floor($start_minutes / 60))
                                ->minute($start_minutes % 60)
                                ->timezone('America/Los_Angeles')
                                ->format('H:i');

            $end_time = Carbon::createFromFormat('H', floor($end_minutes / 60))
                            ->minute($end_minutes % 60)
                            ->timezone('America/Los_Angeles')
                            ->format('H:i');

            // Save the time range
            $autoLabelSetting->timeRanges()->create([
                'start_time' => $start_time,
                'end_time' => $end_time,
            ]);
        }

        // Redirect or return a response
        return redirect()->back()->with('success', 'Auto label settings updated successfully.');
    }


    // cin7 api keys settings

    // public function cin7_api_keys_settings(Request $request) {
    //     // Get the current date from the request or default to today
    //     $current_date = $request->current_date ? Carbon::parse($request->current_date) : Carbon::today();
    
    //     // Fetch API keys with related requests and event logs for the specified date
    //     $cin7_api_keys = ApiKeys::with([
    //         'api_event_logs' => function ($query) use ($current_date) {
    //             $query->whereDate('created_at', $current_date);
    //         },
    //         'api_endpoint_requests' => function ($query) use ($current_date) {
    //             $query->whereDate('created_at', $current_date);
    //         }
    //     ])
    //     ->where('is_active', 1)
    //     ->orderBy('id', 'asc')
    //     ->get();
    
    //     // Check if API keys exist and log if threshold is exceeded
    //     if (!$cin7_api_keys->isEmpty()) {
    //         foreach ($cin7_api_keys as $cin7_api_key) {
    //             // Check if the request count exceeds the threshold
    //             if ($cin7_api_key->request_count >= $cin7_api_key->threshold) {
    //                 try {
    //                     // Log threshold exceeded event
    //                     $cin7_api_key_event_log = new ApiEventLog();
    //                     $cin7_api_key_event_log->api_key_id = $cin7_api_key->id;
    //                     $cin7_api_key_event_log->description = 'Threshold Exceeded';
    //                     $cin7_api_key_event_log->save();
    //                 } catch (\Exception $e) {
    //                     // Handle potential errors (e.g., failed to save log)
    //                     Log::error('Error saving API key event log: ' . $e->getMessage());
    //                 }
    //             }
    //         }
    //     }
    
    //     // Return the view with API keys and the selected date
    //     return view('admin.cin7_api_keys_settings.index', compact('cin7_api_keys', 'current_date'));
    // }

    public function cin7_api_keys_settings(Request $request) {
        // Get the current date from the request or default to today
        $current_date = $request->current_date ? Carbon::parse($request->current_date) : Carbon::today();

        // Determine if the selected date is in the past
        $is_past_date = $current_date->lt(Carbon::today());
        
        $cin7_api_keys = ApiKeys::with([
            'api_event_logs' => function ($query) use ($current_date) {
                $query->whereDate('created_at', $current_date);
            },
            'api_endpoint_requests' => function ($query) use ($current_date) {
                $query->whereDate('created_at', $current_date);
            }
        ])
        ->when($is_past_date, function ($query) use ($current_date) {
            return  $query->whereDate('created_at', $current_date);
        }, function ($query) {
            return $query->where('is_active', 1);
        })
        ->orderBy('id', 'asc')
        ->get();
    
        // Check if API keys exist and log if threshold is exceeded
        if (!$cin7_api_keys->isEmpty()) {
            foreach ($cin7_api_keys as $cin7_api_key) {
                // Check if the request count exceeds the threshold
                if ($cin7_api_key->request_count >= $cin7_api_key->threshold) {
                    try {
                        // Log threshold exceeded event
                        $cin7_api_key_event_log = new ApiEventLog();
                        $cin7_api_key_event_log->api_key_id = $cin7_api_key->id;
                        $cin7_api_key_event_log->description = 'Threshold Exceeded';
                        $cin7_api_key_event_log->save();
                    } catch (\Exception $e) {
                        // Handle potential errors (e.g., failed to save log)
                        Log::error('Error saving API key event log: ' . $e->getMessage());
                    }
                }
            }
        }
    
        // Return the view with API keys and the selected date
        return view('admin.cin7_api_keys_settings.index', compact('cin7_api_keys', 'current_date'));
    }
    

    

    // stop cin7 api key

    public function stop_cin7_api(Request $request) {
        try {
           
            $cin7_id = $request->id;
            $old_date = $request->current_date ? Carbon::parse($request->current_date) : null;
            $cin7_api_key = ApiKeys::where('id', $cin7_id)->first();
            if (!$cin7_api_key) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cin7 API Key not found.'
                ]);
            }
        
            
            if ($old_date && $old_date->lt(Carbon::today())) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'API keys with old dates cannot be stopped or started.'
                ]);
            }
        

            $is_stop = 0;

            if (!empty($cin7_api_key) && $cin7_api_key->is_stop == 1) {
                $is_stop = 0;
            } elseif (!empty($cin7_api_key) && $cin7_api_key->is_stop == 0) {
                $is_stop = 1;
            }

            $cin7_api_key->is_stop = $is_stop;
            $cin7_api_key->save();

            $toggle = $cin7_api_key->is_stop == 1 ? 'stopped' : 'activated';


            $cin7_api_key_event_log = new ApiEventLog();
            $cin7_api_key_event_log->api_key_id = $cin7_api_key->id;
            $cin7_api_key_event_log->description = $cin7_api_key->name . ' '. $toggle .' manually by ' . auth()->user()->email;
            $cin7_api_key_event_log->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Cin7 API Key '. $toggle .' successfully.',
                'is_stop' => $cin7_api_key->is_stop
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cin7 API Key not found.'
            ]);
        }
    }


    // update cin7 api key threshold

    public function update_cin7_api_threshold(Request $request) {
        try {
            $old_date = $request->current_date ? Carbon::parse($request->current_date) : null;
            $cin7_id = $request->id;
            $cin7_api_key = ApiKeys::where('id', $cin7_id)->first();
            $threshold = $request->threshold;

            if (!$cin7_api_key) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cin7 API Key not found.'
                ]);
            }
        
            if ($old_date && $old_date->lt(Carbon::today())) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'API keys with old dates cannot updated.'
                ]);
            }

            if (!empty($cin7_api_key) && !empty($threshold) && intval($threshold) <= 5000) {
                
                $cin7_api_key->threshold = $threshold;
                $cin7_api_key->save();

                $cin7_api_key_event_log = new ApiEventLog();
                $cin7_api_key_event_log->api_key_id = $cin7_api_key->id;
                $cin7_api_key_event_log->description = $cin7_api_key->name . ' '. 'threshold updated to ' . $threshold . ' by ' . auth()->user()->email;
                $cin7_api_key_event_log->save();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Cin7 API Key threshold updated successfully.',
                    'threshold' => $cin7_api_key->threshold
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cin7 API Key not found or threshold must be less than or equal to 5000.'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cin7 API Key not found.'
            ]);
        }
    }


    // get shipstation api logs 

    public function get_shipstation_api_logs(Request $request) {
        $search = $request->search;

        if (!empty($search)) {
            $shipstation_api_logs = ShipstationApiLogs::where('order_id', $search)
            ->orderBy('id', 'desc')
            ->paginate(10);
        } else {
            $shipstation_api_logs = ShipstationApiLogs::orderBy('id', 'desc')->paginate(10);
        }

        return view('admin.shipstation_api_logs.index', compact('shipstation_api_logs' , 'search'));
    }


    public function images_requests(Request $request) {
        $search = $request->search;
        if (!empty($search)) {
            $ai_image_generations = AIImageGeneration::with('product')
            ->whereHas('product', function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('code', 'LIKE', "%{$search}%");
            })
            ->orderBy('id', 'desc')
            ->paginate(10);
        } else {
            $ai_image_generations = AIImageGeneration::with('product')
            ->orderBy('id', 'desc')
            ->paginate(10);
        }

        return view('admin.images_reviews.index', compact('ai_image_generations' , 'search'));
    }


    public function images_requests_approve(Request $request , $id) {
        $product_id = $id;
        $ai_image_generation = AIImageGeneration::where('product_id', $product_id)->first();
        $ai_image_generation->status = 1;
        $ai_image_generation->save();
        redirect()->back()->with('success', 'Image request approved successfully.');
    }

    
    


    
}