<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\MailHelper;
use App\Helpers\SettingHelper;
use \App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Middleware\IsAdmin;
use App\Models\AdminSetting;
use App\Models\Contact;
use App\Models\ContactLogs;
use App\Models\ProductStockNotification;
use App\Models\SpecificAdminNotification;
use App\Models\User;
use App\Models\UserLog;
use Illuminate\Support\Facades\DB;

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
        $product_stock_notification_users = ProductStockNotification::orderBy('created_at' , 'Desc')->paginate(10);
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
}