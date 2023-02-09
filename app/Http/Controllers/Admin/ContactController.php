<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\ApiOrder;
use App\Jobs\SyncContacts;
use Illuminate\Support\Facades\Mail;
use App\Mail\Subscribe;
use App\Helpers\MailHelper;
use App\Models\User;
use DB;
use URL;
use Carbon\Carbon;



class ContactController extends Controller
{


    function __construct()
    {
       $this->middleware(['role:Admin'])->except('contomer_invitation');
    }

    public function supplier()
    {
        $contacts = Contact::where('type', 'Supplier')->get();
        return view('admin/contacts', compact('contacts'));
    }

    public function customer(Request $request)
    {
        $perPage = $request->get('perPage');
        $search = $request->get('search');
        $contact_query = Contact::where('type', 'Customer');
        if (!empty($contact_query)) {
            $contact_query->where('firstName', 'LIKE', '%' . $search . '%')
                ->orWhere('lastName', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('company', 'like', '%' . $search . '%')
                ->orWhere('phone', 'like', '%' . $search . '%')
                ->orWhere('mobile', 'like', '%' . $search . '%');
        }

        $contacts = $contact_query->paginate($perPage);
        return view('admin/customers', compact(
            'contacts',
            'search',
            'perPage'
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
        $customer = Contact::where('id', $id)->first();

        $customer_orders =  ApiOrder::where('user_id', $customer->user_id)->with(['createdby', 'processedby'])->limit('5')->get();
        $statuses = OrderStatus::all();
        if ($customer->hashKey && $customer->hashUsed == false ) {
            $invitation_url = URL::to("/");
            $invitation_url = $invitation_url.'/customer/invitation/'.$customer->hashKey;
        }
        return view('admin/customer-details', compact('customer', 'statuses', 'customer_orders', 'invitation_url'));
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
        SyncContacts::dispatch('create_contact', $contact);
        sleep(10);
        $is_updated = Contact::where('id', $contact_id)->pluck('contact_id')->first();
        $admin_users =  DB::table('model_has_roles')->where('role_id', 1)->pluck('model_id');
        $admin_users = $admin_users->toArray();
        $users_with_role_admin = User::select("email")
            ->whereIn('id', $admin_users)
            ->get();

        if ($is_updated) {
            $name = $currentContact['firstName'];
            $email = $currentContact['email'];
            $subject = 'Account  approval';
            $template = 'emails.approval-notifications';

            $data = [
                'contact_name' => $name,
                'name' =>  'Admin',
                'email' => $email,
                'contact_email' => $currentContact['email'],
                'contact_id' => $is_updated,
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


            // MailHelper::sendMailNotification('emails.admin-order-received', $data);
            return response()->json([
                'success' => true,
                'created' => true,
                'msg' => 'Welcome, new player.'
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
        //dd($request->all());
        $contact_id = $request->contact_id;
        $priceColumn = $request->pricingCol;
        //dd()
        $first_name = $request->first_name;
        $last_name = $request->last_name;
        $pricingCol = $request->pricingCol;
        // dd($pricingCol);

        $contact = Contact::where('contact_id', $request->contact_id)->first();
        //dd($contact);
        if ($pricingCol) {
            //dd($pricingCol);
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
        return view('admin/customer-edit', compact('contact'));
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
                'postalPostCode' => $request->zip
            ]
        );
        return redirect()->back();
    }

    public function send_invitation_email(Request $request) {
        $current_date_time = Carbon::now()->toDateTimeString();
        $secret = "QCOM".$current_date_time;
        $sig = hash_hmac('sha256', $request->customer_email, $secret);
        $url = URL::to("/");
        $url = $url.'/customer/invitation/'.$sig;
        $email = $request->customer_email;

        $data = [
                'email' => $email,
                'subject' => 'Customer Registration Invitation',
                'from' => env('MAIL_FROM_ADDRESS'),
                'content' => 'Customer Registration Invitation',
                'url' => $url
            ];

        MailHelper::sendMailNotification('emails.invitaion-emails', $data);
             $contact_id = $request->contact_id;
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
    }

    public function contomer_invitation($hash) 
    {
         $contact = Contact::where('hashKey', $hash)->first();

         $msg = 'hashKey already used !';

        if($contact->hashUsed == 1){

            return view('contomer_invitation-error', compact('msg'));
        } else {

            return view('contomer_invitation', compact('contact'));
        }

        return view('contomer_invitation', compact('contact'));
    }
}