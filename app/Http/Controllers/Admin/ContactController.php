<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\ApiOrder;
use App\Jobs\SyncContacts;
use App\Helpers\MailHelper;
use App\Helpers\UserHelper;
use App\Models\User;
use Carbon\Carbon;
use App\Models\SecondaryContact;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Models\UserLog;
use App\Models\ContactLogs;
use App\Models\ContactPriceColumn;


use Illuminate\Support\Str;
use App\Models\UsState;

use App\Helpers\SettingHelper;

class ContactController extends Controller
{


    function __construct()
    {
        $this->middleware(['role:Admin'])->except('customer_invitation', 'send_invitation_email');
    }

    public function supplier(Request $request)
    {
        $sort_by_desc = $request->get('sort_by_desc');
        $sort_by_asc = $request->get('sort_by_asc');
        $sort_by_name = $request->get('sort_by_name');
        
        $contacts = Contact::where('type', 'Supplier');
        if (!empty($request->sort_by_desc)) {
            $contacts = $contacts->orderBy('id' , 'Desc');
        }
        if (!empty($request->sort_by_asc)) {
            $contacts = $contacts->orderBy('id' , 'Asc');
        }


        if (!empty($request->sort_by_name)) {
            if ($sort_by_name == 'Asc') {
                $contacts = $contacts->orderBy('firstName' , 'Asc');
            }
            if ($sort_by_name == 'Desc') {
                $contacts = $contacts->orderBy('firstName' , 'Desc');
            }
        }
        $contacts = $contacts->orderBy('id' , 'Desc')->paginate(10);
        return view('admin/contacts', compact('contacts' , 'sort_by_desc' , 'sort_by_asc' , 'sort_by_name'));
    }

    public function customer(Request $request)
    {
        $perPage = $request->get('perPage');
        $search = $request->get('search');
        $sort_by_desc = $request->get('sort_by_desc');
        $sort_by_asc = $request->get('sort_by_asc');
        $sort_by_name = $request->get('sort_by_name');
        $sort_by_email = $request->get('sort_by_email');
        $activeCustomer = $request->get('active-customer');
        $pendingApproval = $request->get('pending-approval');
        $sort_by_created_at = $request->get('sort_by_created_at');
        $contact_query = Contact::withTrashed()->where('type', 'Customer');
        if (!empty($activeCustomer)) {
            if ($activeCustomer == 'active-customer') {
                $contact_query = $contact_query->where('contact_id', '!=', null);
            }
            if ($activeCustomer == 'disable-customer') {
                $contact_query = Contact::where('contact_id', NULL);
            }
            if ($activeCustomer == 'pending-approval') {
                $contact_query = Contact::where('contact_id', NULL)
                ->where('user_id', '!=', NULL)->orderBy('created_at' , 'Desc');
            }
        }
        if ($pendingApproval == 'pending-approval') {
            $contact_query = Contact::where('contact_id', NULL)
            ->where('user_id', '!=', NULL)->orderBy('created_at' , 'Desc');
        }
        if (!empty($search)) {
            $contact_query = $contact_query->where('firstName', 'LIKE', '%' . $search . '%')
                ->orWhere('lastName', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('company', 'like', '%' . $search . '%')
                ->orWhere('phone', 'like', '%' . $search . '%')
                ->orWhere('mobile', 'like', '%' . $search . '%')
                ->orWhere('contact_id', 'like', '%' . $search . '%')
                ->orWhere('id', 'like', '%' . $search . '%')
                ->orWhere('priceColumn', 'like', '%' . $search . '%')
                ->orWhere('notes', 'like', '%' . $search . '%')
                ->orWhere(DB::raw("CONCAT(firstName,' ',lastName)"), 'like', '%' . $search . '%');
        }

        if (!empty($request->sort_by_desc)) {
            $contact_query = $contact_query->orderBy('id' , 'Desc');
        }
        if (!empty($request->sort_by_asc)) {
            $contact_query = $contact_query->orderBy('id' , 'Asc');
        }


        if (!empty($request->sort_by_name)) {
            if ($sort_by_name == 'Asc') {
                $contact_query = $contact_query->orderBy('firstName' , 'Asc');
            }
            if ($sort_by_name == 'Desc') {
                $contact_query = $contact_query->orderBy('firstName' , 'Desc');
            }
        }

        if (!empty($request->sort_by_email)) {
            if ($sort_by_email == 'Asc') {
                $contact_query = $contact_query->orderBy('email' , 'Asc');
            }
            if ($sort_by_email == 'Desc') {
                $contact_query = $contact_query->orderBy('email' , 'Desc');
            }
        }
        
        if (!empty($sort_by_created_at)) {
            if ($sort_by_created_at == 'Asc') {
                $contact_query = $contact_query->orderBy('created_at' , 'Asc');
            }
            if ($sort_by_created_at == 'Desc') {
                $contact_query = $contact_query->orderBy('created_at' , 'Desc');
            }
        } else {
            $contact_query = $contact_query->orderBy('created_at' , 'Desc');
        }

        $contacts = $contact_query->paginate($perPage);
        return view('admin/customers', compact(
            'contacts',
            'search',
            'perPage',
            'activeCustomer',
            'sort_by_desc',
            'sort_by_asc',
            'sort_by_name',
            'sort_by_email',
            'sort_by_created_at',
        ));
    }

    public function customer_create()
    {
        return view('admin/customer-create');
    }

    public function customer_store(Request $request)
    {
        $company = $request->input('company');
        $mobile = $request->input('mobile');
        $first_name = $request->input('first_name');
        $phone = $request->input('phone');
        $last_name = $request->input('last_name');
        $fax = $request->input('fax');
        $job_title = $request->input('job_title');
        $website = $request->input('website');
        $city = $request->input('city');
        $email = $request->input('email');
        $status = $request->input('status');
        $type = $request->input('type');
        $pricing_tier = $request->input('priceCol');
        $billing_address_1 = $request->input('billing_address_1');
        $billing_address_2 = $request->input('billing_address_2');
        $billing_city = $request->input('billing_city');
        $billing_state = $request->input('billing_state');
        $billing_postal_code = $request->input('billing_postal_code');
        $delivery_address_1 = $request->input('delivery_address_1');
        $delivery_address_2 = $request->input('delivery_address_2');
        $delivery_city = $request->input('delivery_city');
        $delivery_state = $request->input('delivery_state');
        $delivery_postal_code = $request->input('delivery_postal_code');
        $notes = $request->input('notes');
        $contact = new Contact;
        $contact->status = $status;
        $contact->fax = $fax;
        $contact->website = $website;
        $contact->email = $email;
        $contact->type = $type;
        $contact->phone = $phone;
        $contact->priceColumn = $pricing_tier;
        $contact->company = $company;
        $contact->firstName = $first_name;
        $contact->lastName = $last_name;
        $contact->jobTitle = $job_title;
        $contact->mobile = $mobile;
        $contact->address1 = $delivery_address_1;
        $contact->address2 = $delivery_address_2;
        $contact->city = $delivery_city;
        $contact->state = $delivery_state;
        $contact->postCode = $delivery_postal_code;
        $contact->postalAddress1 = $billing_postal_code;
        $contact->postalAddress2 = $billing_address_2;
        $contact->postalCity = $billing_city;
        $contact->postalPostCode = $billing_postal_code;
        $contact->postalState = $billing_state;
        $contact->notes = $notes;
        $api_contact = $contact;
        $client = new \GuzzleHttp\Client();
        $url = "https://api.cin7.com/api/v1/Contacts/";
        $response = $client->post($url, [
            'headers' => ['Content-type' => 'application/json'],
            'auth' => [
                SettingHelper::getSetting('cin7_auth_username'),
                SettingHelper::getSetting('cin7_auth_password')
            ],
            'json' => [
                $api_contact
            ],
        ]);
        $contact->save();

        $customer_id = $contact->id;

        $api_response = $response->getBody();

        $decoded_response = json_decode($api_response);
        $customer = Contact::find($customer_id);
        $customer->contact_id = $decoded_response[0]->id;
        $customer->save();
        return redirect('admin/customers');
    }

    public function show_customer(Request $request , $id)
    {
        
        $primary_contact = '';
        $secondary_contacts = '';
        $contact_is_parent = '';
        $show_deleted_users = $request->show_deleted_users;
        $secondary_contacts_query = '';
        $customer = Contact::withTrashed()->where('id', $id)->first();
        $pricing = $customer->priceColumn;
        $get_contactID = !empty($customer->contact_id) ? $customer->contact_id : $id;
        $get_secondaryID = !empty($customer->secondary_id) ? $customer->secondary_id : $id;
        $logs = UserLog::orWhere('contact_id', $get_contactID)
        ->orWhere('secondary_id', $get_secondaryID)
        ->orderBy('created_at', 'desc')
        ->take(10)
        ->get();
        $user_id = $customer->user_id;
        if (!empty($customer->contact_id)) {
            $secondary_contacts_query = Contact::where('parent_id', $customer->contact_id);
        } else {
            $secondary_contacts = '';
        }

        if ($request->show_deleted_users != '' && isset($request->show_deleted_users)) {
            $secondary_contacts_query = $secondary_contacts_query->onlyTrashed();
        }

        if (!empty($customer->contact_id)) {
            $secondary_contacts = $secondary_contacts_query->get();
        } else {
            $secondary_contacts = '';
        }

        $user = Contact::withTrashed()->where('id', $id)->first();
        $all_ids = UserHelper::getAllMemberIds($user);
        $contact_ids = Contact::whereIn('id', $all_ids)
            ->pluck('contact_id')
            ->toArray();
        // dd($customer->contact_id);
        $customer_orders = ApiOrder::where('memberId', $customer->contact_id)->orWhere('memberId' , $customer->parent_id)
            ->with('contact')
            ->with('apiOrderItem')
            ->orderBy('id', 'desc')
            ->get();

        $order_statuses = OrderStatus::all();
        if ($customer->hashKey && $customer->hashUsed == false) {
            $invitation_url = URL::to("/");
            $invitation_url = $invitation_url . '/customer/invitation/' . $customer->hashKey;
        } else {
            $invitation_url = '';
        }


        $site_id = SettingHelper::getSetting('site_id');
        $contact_price_columns = ContactPriceColumn::where('site_id', $site_id)->pluck('price_column')->toArray();
        $get_secondary_contact = Contact::where('contact_id', $customer->parent_id)->first();

        return view('admin/customer-details', compact(
            'customer',
            'secondary_contacts',
            'order_statuses',
            'customer_orders',
            'invitation_url',
            'customer',
            'primary_contact',
            'customer_orders',
            'invitation_url',
            'logs',
            'contact_price_columns',
            'pricing',
            'get_secondary_contact',
            'show_deleted_users'
        ));
    }

    public function activate_customer(Request $request)
    {
        $contact_id = $request->input('contact_id');
        $currentContact = Contact::where('id', $contact_id)->first()->toArray();
        unset($currentContact['id']);
        unset($currentContact['contact_id']);
        unset($currentContact['user_id']);
        $json_encode = json_encode($currentContact);
        $contact = [
            $currentContact
        ];
        $request_time = date('Y-m-d H:i:s');
        $sync_data = SyncContacts::dispatch('create_contact', $contact)->onQueue(env('QUEUE_NAME'));
        sleep(10);
        
        $is_updated = Contact::where('id', $contact_id)->first();
        $admin_users = DB::table('model_has_roles')->where('role_id', 1)->pluck('model_id');
        $admin_users = $admin_users->toArray();
        $users_with_role_admin = User::select("email")
            ->whereIn('id', $admin_users)
            ->get();
        $response_time = date('Y-m-d H:i:s');
        $difference = strtotime($response_time) - strtotime($request_time);
        
        if ($is_updated) {
            $name = $currentContact['firstName'] . ' ' . $currentContact['lastName'];
            $email = $currentContact['email'];
            $subject = 'Account  approval';
            $template = 'emails.approval-notifications';

            $data = [
                'contact_name' => $name,
                'name' =>  'Admin',
                'email' => $email,
                'contact_email' => $currentContact['email'],
                // 'contact_id' => $is_updated ? $is_updated->contact_id : null,
                'subject' => 'New Account activated',
                'from' => env('MAIL_FROM_ADDRESS'),
                'content' => 'New account activated.'
            ];

            if (!empty($users_with_role_admin)) {
                foreach ($users_with_role_admin as $role_admin) {
                    $data['email'] = $role_admin->email;
                    $adminTemplate = 'emails.approval-notifications';
                    MailHelper::sendMailNotification('emails.approval-notifications', $data);
                }
            }
            $data['name'] = $name;
            $data['email'] = $email;
            $data['content'] = 'Your account has been approved';
            $data['subject'] = 'Your account has been approved';
            MailHelper::sendMailNotification('emails.approval-notifications', $data);
            return response()->json([
                'success' => true,
                'created' => true,
                'msg' => 'Welcome, new player.',
                'data' => $sync_data,
                'time' => $difference
            ]);
        } else {
            return response()->json([
                'success' => false,
                'created' => true,
                'msg' => 'failed'
            ]);
        }
    }

    public function update_pricing_column(Request $request)
    {
        $contact_id = $request->contact_id;
        $priceColumn = $request->pricingCol;
        $first_name = $request->first_name;
        $last_name = $request->last_name;
        $pricingCol = $request->pricingCol;
        $contact = Contact::where('contact_id', $request->contact_id)->first();
        if ($pricingCol) {
            $contact->priceColumn = $pricingCol;
            $contact->save();

            return response()->json([
                'success' => true,
                'created' => true,
                'msg' => 'Pricing Column Updated'
            ]);
        } else {

            $contact->firstName = $first_name;
            $contact->lastName = $last_name;
            $contact->save();
            
            return response()->json([
                'success' => true,
                'created' => true,
                'msg' => 'name updated'
            ]);
        }
    }

    public function customer_delete($id)
    {
        $customer =  Contact::find($id);
        $customer->is_deleted = now();
        $customer->save();
        $user_id = $customer->user_id;
        if (!empty($user_id)) {
            $user = User::find($user_id);
            $user->is_deleted = now();
            $user->save();
            $contact_log = new ContactLogs();
            $contact_log->user_id = $user_id;
            $contact_log->action_by = auth()->user()->id;
            $contact_log->action = 'Deletion';
            $contact_log->description = !empty($customer->email) ? $customer->email . ' ' . 'is ' . 'deleted by ' . auth()->user()->email . ' ' .'at'. ' '. now() : $customer->firstName .' '. $customer->lastName  . ' ' . 'is ' . 'deleted by ' . auth()->user()->email . ' ' .'at'. ' '. now();
            $contact_log->save();

            // adding to user log
            $user_log = new UserLog();
            $user_log->user_id = auth()->user()->id;
            $user_log->contact_id = !empty($customer->contact_id) ? $customer->contact_id : $customer->id;
            $user_log->secondary_id = !empty($customer->secondary_id) ? $customer->secondary_id : $customer->id;
            $user_log->action = 'Deletion';
            $user_log->user_notes = !empty($customer->email) ? $customer->email . ' ' . 'is ' . 'deleted by ' . auth()->user()->email . ' ' .'at'. ' '. now() : $customer->firstName .' '. $customer->lastName  . 'is ' . 'deleted by ' . auth()->user()->email .' ' .'at'. ' '. now();
            $user_log->save();
            $user->delete();
            $customer->delete();
            return redirect()->back()->with('success', 'Customer Deleted Successfully');
        } else {
            $contact_log = new ContactLogs();
            $contact_log->user_id = $user_id;
            $contact_log->action_by = auth()->user()->id;
            $contact_log->action = 'Deletion';
            $contact_log->description =  !empty($customer->email) ? $customer->email . ' ' . 'is ' . 'deleted by ' . auth()->user()->email . ' ' .'at'. ' '. now()  : $customer->firstName .' '. $customer->lastName . ' '. ' is ' . 'deleted by ' . auth()->user()->email .' ' .'at'. ' '. now();
            $contact_log->save();

            $user_log = new UserLog();
            $user_log->user_id = auth()->user()->id;
            $user_log->contact_id = !empty($customer->contact_id) ? $customer->contact_id : $customer->id;
            $user_log->secondary_id = !empty($customer->secondary_id) ? $customer->secondary_id : $customer->id;
            $user_log->action = 'Deletion';
            $user_log->user_notes = !empty($customer->email) ? $customer->email . ' ' . 'is ' . 'deleted by ' . auth()->user()->email . ' ' .'at'. ' '. now() : $customer->firstName .' '. $customer->lastName  . 'is ' . 'deleted by ' . auth()->user()->email .' ' .'at'. ' '. now();
            $user_log->save();
            $customer->delete();
            return redirect()->back()->with('error', 'Customer deleted from Contacts but not found is Users');
        }
        
    }

    public function customer_edit($id)
    {
        $contact = Contact::where('id', $id)->first();
        $states = UsState::all();
        return view('admin/customer-edit', compact('contact', 'states'));
    }

    public function customer_update(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        $id = $request->id;
        $get_user = Contact::where('id', $id)->first();
        if (!empty($get_user->user_id)) {
            $update_user = User::where('id', $get_user->user_id)->update(
                [
                    'email' => $request->email,
                ]
            );
        } else {
            $update_user = User::where('email' , $get_user->email)->update(
                [
                    'email' => $request->email,
                ]
            );
        }
        $update_contact = Contact::where('id', $id)->update(
            [
                'firstName' => $request->first_name,
                'lastName' => $request->last_name,
                'company' => $request->company,
                'email' => $request->email,
                'website' => $request->website,
                'postalAddress1' => $request->address_1,
                'postalAddress2' => $request->address_2,
                'phone' => $request->phone,
                'postalCity' => $request->city,
                'postalState' => $request->state,
                'postalPostCode' => $request->zip,
                'tax_class' => strtolower($request->state) == strtolower('California') ? '8.75%' : 'Out of State'
            ]
        );
        if ($update_contact) {
            return redirect()->back()->with('success', 'Customer Updated Successfully');
        } else {
            return redirect()->back()->with('error', 'Customer Not Updated');
        }
    }

    public function send_invitation_email(Request $request)
    {
        if (!empty($request->secondory_email)) {
            $active_email = $request->secondory_email;
        } else {
            $active_email = $request->customer_email;
        }
        $current_date_time = Carbon::now()->toDateTimeString();
        $secret = "QCOM" . $current_date_time;
        $sig = hash_hmac('sha256', $active_email, $secret);
        $url = URL::to("/");
        if (!empty($request->secondory_email)) {
            $url = $url . '/customer/invitation/' . $sig . '?is_secondary=1';
        } else {
            $url = $url . '/customer/invitation/' . $sig;
        }
        $email = $active_email;


        $data = [
            'email' => $email,
            'subject' => 'Customer Registration Invitation',
            'from' => env('MAIL_FROM_ADDRESS'),
            'content' => 'Customer Registration Invitation',
            'url' => $url
        ];

        MailHelper::sendMailNotification('emails.invitaion-emails', $data);
        $contact_id = $request->contact_id;
        if (empty($request->secondory_email)) {
            $contact = Contact::where('contact_id', $contact_id)->update(
                [
                    'hashKey' => $sig,
                    'hashUsed' => 0,
                ]
            );
            return response()->json([
                'msg' => 'success',
                'status' => 200,
                'link' => $url
            ]);
        } else {
            $secondary_contact = Contact::where('email', $active_email)->update(
                [
                    'hashKey' => $sig,
                    'hashUsed' => 0,
                ]
            );
            return response()->json([
                'msg' => 'success',
                'status' => 200,
                'link' => $url . '?is_secondary=1'
            ]);
        }
    }

    public function customer_invitation(Request $request, $hash)
    {
        if ($request->is_secondary) {
            $secondary = true;
        } else {
            $secondary = '';
        }
        $msg = 'hashKey already used !';
        $contact = Contact::where('hashKey', $hash)->first();
        if ($contact) {
            if ($contact->hashUsed == 1) {

                return view('customer-invitation-error', compact('msg'));
            } else {

                return view('customer-invitation', compact('contact'));
            }

            return view('customer-invitation', compact('contact'));
        } else {
            $contact = Contact::where('hashKey', $hash)->first();
            if ($contact->hashUsed == 1) {

                return view('customer-invitation-error', compact('msg'));
            } else {
                return view('customer-invitation', compact('contact', 'secondary'));
            }
        }
    }

    public function getParent(Request $request)
    {
        $res = Contact::select("firstName", "contact_id")
            ->where("firstName", "LIKE", "%{$request->term}%")
            ->where("is_parent", 1)
            ->get();
        return response()->json($res);
    }

    public  function assingParentChild(Request $request)
    {
        $contact = Contact::where('user_id', $request->user_id)->first();
        $secondary_contact_data = [
            'parent_id' => $request->primary_id,
            'is_parent' => 0,
            'company' => $contact->company,
            'user_id' => $contact->user_id,
            'firstName' => $contact->firstName,
            'lastName' => $contact->lastName,
            'jobTitle' => $contact->jobTitle,
            'email' => $contact->email,
            'phone' => $contact->phone,
        ];

        SecondaryContact::create($secondary_contact_data);

        $api_request = [
            [
                'id' => $request->primary_id,
                'type' => 'Customer',
                'secondaryContacts' => [
                    [
                        'company' => $contact->company,
                        'firstName' => $contact->firstName,
                        'lastName' => $contact->lastName,
                        'jobTitle' => $contact->jobTitle,
                        'email' => $contact->email,
                        'phone' => $contact->phone
                    ],
                ]
            ],
        ];

        $contact_request = $api_request;

        SyncContacts::dispatch('update_contact', $contact_request)->onQueue(env('QUEUE_NAME'));

        SecondaryContact::create($secondary_contact_data);

        return response()->json([
            'msg' => 'Assigned Successfully',
            'status' => 200
        ]);
    }

    public function refreshContact(Request $request)
    {
        $cin7_auth_username = SettingHelper::getSetting('cin7_auth_username');
        $cin7_auth_password = SettingHelper::getSetting('cin7_auth_password');

        $contact_id  = $request->contactId;
        if ($request->type == 'primary') {
            $contact = Contact::where('contact_id', $contact_id)->first();
            $client = new \GuzzleHttp\Client();

            $res = $client->request(
                'GET',
                'https://api.cin7.com/api/v1/Contacts/' . $contact_id,
                [
                    'auth' => [
                        $cin7_auth_username,
                        $cin7_auth_password
                    ]
                ]
            );
            $api_contact = $res->getBody()->getContents();

            $api_contact = json_decode($api_contact);
            $pricing = preg_replace('/\R/', '', $api_contact->priceColumn);
            $contact_update = Contact::where('contact_id', $contact_id)->first();    
            $contact_update->update([
                'email'  => $api_contact->email,
                'firstName' => $api_contact->firstName,
                'lastName' => $api_contact->lastName,
                'priceColumn' => $pricing,
                'company' => $api_contact->company,
                'phone' => $api_contact->phone,
                'mobile' => $api_contact->mobile,
                'website' => $api_contact->website,
                'address1' => $api_contact->address1,
                'address2' => $api_contact->address2,
                'postCode' => $api_contact->postCode,
                'state' => $api_contact->state,
                'city' => $api_contact->city,
                'postalAddress1' => $api_contact->postalAddress1,
                'postalAddress2' => $api_contact->postalAddress2,
                'postalPostCode' => $api_contact->postalPostCode,
                'postalState' => $api_contact->postalState,
                'postalCity' => $api_contact->postalCity,
                'status' => $api_contact->isActive,
                'tax_class' => $api_contact->taxStatus ? $api_contact->taxStatus : $contact->tax_class,
            ]);

            if ($api_contact->secondaryContacts) {
                foreach ($api_contact->secondaryContacts as $apiSecondaryContact) {
                    $secondary_contact = Contact::where('secondary_id', $apiSecondaryContact->id)->where('parent_id', $contact->contact_id)->first();
                    if ($secondary_contact) {

                        $secondary_contact->secondary_id = $apiSecondaryContact->id;
                        $secondary_contact->is_parent = 0;
                        $secondary_contact->status = 1;
                        $secondary_contact->company = $api_contact->company;
                        $secondary_contact->firstName = $apiSecondaryContact->firstName;
                        $secondary_contact->lastName = $apiSecondaryContact->lastName;
                        $secondary_contact->jobTitle  = $apiSecondaryContact->jobTitle;
                        $secondary_contact->email = $apiSecondaryContact->email;
                        $secondary_contact->mobile = $apiSecondaryContact->mobile;
                        $secondary_contact->phone = $apiSecondaryContact->phone;
                        $secondary_contact->priceColumn = $api_contact->priceColumn;
                        $secondary_contact->tax_class = $api_contact->taxStatus ? $api_contact->taxStatus : $secondary_contact->tax_class;
                        $secondary_contact->save();
                    } else {
                        $secondary_contact = new Contact();
                        $secondary_contact->parent_id = $contact->contact_id;
                        $secondary_contact->secondary_id = $apiSecondaryContact->id;
                        $secondary_contact->is_parent = 0;
                        $secondary_contact->status = 1;
                        $secondary_contact->company = $api_contact->company;
                        $secondary_contact->firstName = $apiSecondaryContact->firstName;
                        $secondary_contact->lastName = $apiSecondaryContact->lastName;
                        $secondary_contact->jobTitle  = $apiSecondaryContact->jobTitle;
                        $secondary_contact->email = $apiSecondaryContact->email;
                        $secondary_contact->mobile = $apiSecondaryContact->mobile;
                        $secondary_contact->phone = $apiSecondaryContact->phone;
                        $secondary_contact->priceColumn = $api_contact->priceColumn;
                        $secondary_contact->tax_class = $api_contact->taxStatus ? $api_contact->taxStatus : $secondary_contact->tax_class;
                        $secondary_contact->save();
                        $id = $secondary_contact->id;
                        $contact_info = Contact::where('id', $id)->first();
                        $email = $contact_info->email;
                        if ($email) {
                            $user = User::where('email', $email)->first();
                            if (empty($user)) {
                                $user = User::firstOrCreate([
                                    'email' => $email
                                ]);
                            }
                        }

                        $user_contact = User::where('email', $email)->first();
                        $user_contact_id = $user_contact->id;
                        $contacts = Contact::where('email', $email)->get();
                        foreach ($contacts as $contact) {
                            $contact->user_id = $user_contact_id;
                            $contact->save();
                        }
                    }
                }
            }
            $str = str_replace("\r", '', $api_contact->priceColumn);
            return response()->json([
                'status' => '200',
                'message' => 'Contact Refreshed Successfully',
                'updated_email' => $api_contact->email,
                'updated_firstName' => $api_contact->firstName,
                'updated_lastName' => $api_contact->lastName,
                'updated_priceColumn' => $str,
                'updated_company' => $api_contact->company,
                'success' => true
            ]);
        } elseif ($request->type == 'secondary') {

            $contact = Contact::where('secondary_id', $contact_id)->first();
            $parent_id = $contact->parent_id;
            $pricing_column = Contact::where('parent_id', $parent_id)->first();
            $pricing_column = $pricing_column->priceColumn;
            $client = new \GuzzleHttp\Client();

            $res = $client->request(
                'GET',
                'https://api.cin7.com/api/v1/Contacts/' . $parent_id,
                [
                    'auth' => [
                        $cin7_auth_username,
                        $cin7_auth_password
                    ]
                ]
            );
            $api_secondary_contact = $res->getBody()->getContents();
            $api_secondary_contact = json_decode($api_secondary_contact);

            foreach ($api_secondary_contact->secondaryContacts as $api_secondary_contact) {

                if ($api_secondary_contact->id == $contact_id) {
                    $updated_contact = Contact::where('secondary_id', $contact_id)->update([
                        'email'  => $api_secondary_contact->email,
                        'priceColumn' => $pricing_column,
                        'firstName' => $api_secondary_contact->firstName,
                        'lastName' => $api_secondary_contact->lastName,
                        'mobile' => $api_secondary_contact->mobile,
                        'phone' =>  $api_secondary_contact->phone,
                        'status' => 1,
                        'tax_class' => $api_secondary_contact->taxStatus ? $api_secondary_contact->taxStatus : $contact->tax_class,
                    ]);
                    return response()->json([
                        'status' => '200',
                        'message' => 'Contact Refreshed Successfully',
                        'updated_email' => $api_secondary_contact->email,
                        'updated_firstName' => $api_secondary_contact->firstName,
                        'updated_lastName' => $api_secondary_contact->lastName,
                        'updated_company' => $api_secondary_contact->company,
                        'success' => true
                    ]);
                }
            }
        }
    }


    public function disableSecondary(Request $request)
    {
        $contact = Contact::where('id', $request->contactId)->first();
        if ($contact->status == 1) {
            $contact->status = 0;
        } else {
            $contact->status = 1;
        }
        $contact->save();

        return response()->json([
            'msg' => 'success'
        ], 200);
    }
    public function enableShippingPrice(Request $request)
    {
        $contact = Contact::where('contact_id', $request->contactId)->first();
        if (!empty($contact)) {
            if ($contact->charge_shipping == 0) {
                $contact->charge_shipping = 1;
            } else {
                $contact->charge_shipping = 0;
            }
            $contact->save();
    
            return response()->json([
                'msg' => 'success'
            ], 200);
        } else {
            return response()->json([
                'msg' => 'failed'
            ], 200);
        }
       
    }
    public function disableShippingPrice(Request $request)
    {
        $contact = Contact::where('contact_id', $request->contactId)->first();
        if (!empty($contact)) {
            if ($contact->charge_shipping == 1) {
                $contact->charge_shipping = 0;
            } else {
                $contact->charge_shipping = 1;
            }
            $contact->save();
    
            return response()->json([
                'msg' => 'success'
            ], 200);
        } else {
            return response()->json([
                'msg' => 'failed'
            ], 200);
        }
    }
}
