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
use Illuminate\Support\Str;
use App\Models\UsState;

class ContactController extends Controller
{


    function __construct()
    {
        $this->middleware(['role:Admin'])->except('contomer_invitation', 'send_invitation_email');
    }

    public function supplier()
    {
        $contacts = Contact::where('type', 'Supplier')->paginate(10);
        return view('admin/contacts', compact('contacts'));
    }

    public function customer(Request $request)
    {
        $perPage = $request->get('perPage');
        $search = $request->get('search');
        $activeCustomer = $request->get('active-customer');
        $pendingApproval = $request->get('pending-approval');
        $contact_query = Contact::where('type', 'Customer');
        if (!empty($activeCustomer)) {
            if ($activeCustomer == 'active-customer') {
                $contact_query = $contact_query->where('contact_id', '!=', null);
            }
            if ($activeCustomer == 'disable-customer') {
                $contact_query = Contact::where('contact_id', NULL);
            }
            if ($activeCustomer == 'pending-approval') {
                $contact_query = Contact::where('contact_id', NULL)
                ->where('user_id', '!=', NULL);
            }
        }
        if ($pendingApproval == 'pending-approval') {
            $contact_query = Contact::where('contact_id', NULL)
            ->where('user_id', '!=', NULL);
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
                ->orWhere('notes', 'like', '%' . $search . '%');
        }

        $contacts = $contact_query->paginate($perPage);
        return view('admin/customers', compact(
            'contacts',
            'search',
            'perPage',
            'activeCustomer',
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
                'IndoorSunHydro2US',
                '764c3409324f4c14b5eadf8dcdd7dd2f'
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

    public function show_customer($id)
    {
        $primary_contact = '';
        $secondary_contacts = '';
        $contact_is_parent = '';
        $customer = Contact::where('id', $id)->first();
        $pricing = $customer->priceColumn;
        $logs = UserLog::where('contact_id', $customer->contact_id)
            ->orderBy('id', 'desc')
            ->limit(8)
            ->get();
        $user_id = $customer->user_id;
        if (!empty($customer->contact_id)) {
            $secondary_contacts = Contact::where('parent_id', $customer->contact_id)->get();
        } else {
            $secondary_contacts = '';
        }

        $user = Contact::where('id', $id)->first();
        $all_ids = UserHelper::getAllMemberIds($user);
        $contact_ids = Contact::whereIn('id', $all_ids)
            ->pluck('contact_id')
            ->toArray();

        $customer_orders = ApiOrder::whereIn('memberId', $contact_ids)
            ->with('contact')
            ->with('apiOrderItem')
            ->orderBy('id', 'desc')
            ->get();

        $statuses = OrderStatus::all();
        if ($customer->hashKey && $customer->hashUsed == false) {
            $invitation_url = URL::to("/");
            $invitation_url = $invitation_url . '/customer/invitation/' . $customer->hashKey;
        } else {
            $invitation_url = '';
        }
        return view('admin/customer-details', compact(
            'customer',
            'secondary_contacts',
            'statuses',
            'customer_orders',
            'invitation_url',
            'customer',
            'primary_contact',
            'statuses',
            'customer_orders',
            'invitation_url',
            'logs',
            'pricing'
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
            // $name = $currentContact['firstName'];
            // $email = $currentContact['email'];
            // $subject = 'Account  approval';
            // $template = 'emails.approval-notifications';

            // $data = [
            //     'contact_name' => $name,
            //     'name' =>  'Admin',
            //     'email' => $email,
            //     'contact_email' => $currentContact['email'],
            //     'contact_id' => $is_updated ? $is_updated->contact_id : null,
            //     'subject' => 'New Account activated',
            //     'from' => env('MAIL_FROM_ADDRESS'),
            //     'content' => 'New account activated.'
            // ];

            // if (!empty($users_with_role_admin)) {
            //     foreach ($users_with_role_admin as $role_admin) {
            //         $data['email'] = $role_admin->email;
            //         $adminTemplate = 'emails.approval-notifications';
            //         MailHelper::sendMailNotification('emails.approval-notifications', $data);
            //     }
            // }
            // $data['name'] = $name;
            // $data['email'] = $email;
            // $data['content'] = 'Your account has been approved';
            // $data['subject'] = 'Your account has been approved';
            // MailHelper::sendMailNotification('emails.approval-notifications', $data);
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
            $contact->update(
                [
                    'priceColumn' => $pricingCol,
                ]
            );
            return response()->json([
                'success' => true,
                'created' => true,
                'msg' => 'Pricing Column Updated'
            ]);
        } else {
            $contact->update(
                [
                    'firstName' => $first_name,
                    'lastName' => $last_name
                ]
            );
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
        $customer->delete();
        $user_id = $customer->user_id;
        $user = User::find($user_id);
        $user->delete();
        return redirect()->back()->with('success', 'Customer Deleted Successfully');
    }

    public function customer_edit($id)
    {
        $contact = Contact::where('id', $id)->first();
        $states = UsState::all();
        return view('admin/customer-edit', compact('contact', 'states'));
    }

    public function customer_update(Request $request)
    {
        $id = $request->id;
        Contact::where('id', $id)->update(
            [
                'firstName' => $request->first_name,
                'lastName' => $request->last_name,
                'company' => $request->company,
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
        return redirect()->back();
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

    public function contomer_invitation(Request $request, $hash)
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

                return view('contomer_invitation-error', compact('msg'));
            } else {

                return view('contomer_invitation', compact('contact'));
            }

            return view('contomer_invitation', compact('contact'));
        } else {
            $contact = Contact::where('hashKey', $hash)->first();
            if ($contact->hashUsed == 1) {

                return view('contomer_invitation-error', compact('msg'));
            } else {
                return view('contomer_invitation', compact('contact', 'secondary'));
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
        $contact_id  = $request->contactId;
        if ($request->type == 'primary') {
            $contact = Contact::where('contact_id', $contact_id)->first();
            $client = new \GuzzleHttp\Client();

            $res = $client->request(
                'GET',
                'https://api.cin7.com/api/v1/Contacts/' . $contact_id,
                [
                    'auth' => [
                        env('API_USER'),
                        env('API_PASSWORD')
                    ]
                ]
            );
            $api_contact = $res->getBody()->getContents();


            $api_contact = json_decode($api_contact);
            $pricing = preg_replace('/\R/', '', $api_contact->priceColumn);
            Contact::where('contact_id', $contact_id)->update([
                'email'  => $api_contact->email,
                'firstName' => $api_contact->firstName,
                'lastName' => $api_contact->lastName,
                'priceColumn' => $pricing,
                'company' => $api_contact->company,
                'phone' => $api_contact->phone,
                'mobile' => $api_contact->mobile,
                'website' => $api_contact->website,
                'postalAddress1' => $api_contact->postalAddress1,
                'postalAddress2' => $api_contact->postalAddress2,
                'postCode' => $api_contact->postCode,
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
                        env('API_USER'),
                        env('API_PASSWORD')
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
}
