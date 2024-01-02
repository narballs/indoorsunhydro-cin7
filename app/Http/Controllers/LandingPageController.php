<?php

namespace App\Http\Controllers;

use App\Helpers\MailHelper;
use App\Helpers\UserHelper;
use App\Http\Requests\Users\UserSignUpRequest;
use App\Models\ApiOrderItem;
use App\Models\Contact;
use App\Models\Product;
use App\Models\UsCity;
use App\Models\User;
use App\Models\UserLog;
use App\Models\UsState;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class LandingPageController extends Controller
{
    public function index()
    {
        $products = Product::with('options', 'options.defaultPrice','brand', 'options.products','categories' ,'apiorderItem' , 'product_stock')
        // ->whereHas('product_stock', function ($query) {
        //     $query->where('stockAvailable', '>=', 5);
        // })
        ->where('stockAvailable' ,'>=', 5)
        ->where('status' , '!=' , 'Inactive')
        ->orderby('created_at', 'desc')
        ->take(4)
        ->get();

        $top_sellers = ApiOrderItem::with('product', 'product.options', 'product.options.defaultPrice','product.brand', 'product.options.products','product.categories' ,'product.apiorderItem')
            ->whereHas('product' , function($query) {
                $query->where('status' , '!=' , 'Inactive');
            })
            ->select('product_id' , DB::raw('count(*) as selling_count'))
            ->orderBy('selling_count' , 'DESC')
            ->groupBy('product_id')
            ->take(4)
            ->get();
        $data['states'] = UsState::get(["state_name", "id"]);
        if (Auth::check()) {
            return redirect()->route('index');
        }
        return view('landing_page',compact('products' , 'top_sellers' , 'data'));
    }

    public function landing_page_personal_details(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email|unique:users,email',
            'first_name' => 'required',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password'
        ]);

        if($validatedData) {
            return response()->json([
                'success' => true,
                'msg' => $validatedData
            ]);
        }
    }
    public function landing_page_company_details(Request $request)
    {
        $validatedData = $request->validate([
            'company' => 'required',
            'phone' => 'required',
        ]);

        if($validatedData) {
            return response()->json([
                'success' => true,
                'msg' => $validatedData
            ]);
        }
    }
    public function landing_page_address_details(Request $request)
    {
        $validatedData = $request->validate(
            [
                'street_address' => [
                    'required'
                    // 'regex:/^[a-zA-Z0-9\s-]+$/'
                ],
                'state_id' => ['required'],
                'city_id' => ['required'],
                'zip' => [
                    'required',
                    'regex:/^\d{5}(?:[- ]?\d{4})?$/s'
                ]
            ],
            [
                'state_id.required' => 'The state field is required.',
                'city_id.required' => 'The city field is required.',
            ] 
                
        );
        
        
        $user = User::create([
            'email' => strtolower($request->get('email')),
            "first_name" => $request->get('first_name'),
            "last_name" => $request->get('last_name'),
            "password" => bcrypt($request->get('password'))
        ]);
        
        $user_id = $user->id;

        $already_in_cin7 = false;

        $states = UsState::where('id', $request->state_id)->first();
        $state_name = $states->state_name;
        $cities = UsCity::where('id', $request->city_id)->first();
        $city_name = $cities->city;

        $contacts = Contact::where('email', $user->email)->get();
        if (!empty($contacts) && count($contacts) > 0) {
            $already_in_cin7 = true;

            foreach ($contacts as $contact) {
                $contact->user_id = $user->id;
                $contact->save();
            }
        }
        else {
            $contact = new Contact([
                'website' => $request->input('company_website'),
                'company' => $request->input('company_name'),
                'phone' => $request->input('phone'),
                'status' => 0,
                'priceColumn' => 'RetailUSD',
                'user_id' => $user_id,
                'firstName' => $user->first_name,
                'type' => 'Customer',
                'lastName' => $user->last_name,
                'email' => $user->email,
                'is_parent' => 1,
                'status' => 0,
                'tax_class' => strtolower($state_name) == strtolower('California') ? '8.75%' : 'Out of State',
                'paymentTerms' => $request->paymentTerms
            ]);

            $contact->save();
        }
        
        $admin_users =  DB::table('model_has_roles')->where('role_id', 1)->pluck('model_id');
        $admin_users = $admin_users->toArray();

        $users_with_role_admin = User::select("email")
            ->whereIn('id', $admin_users)
            ->get();

        $user_log = UserLog::create([
            'user_id' => $user->id,
            'action' => 'Signup',
            'user_notes' => 'Contact does not exist in Cin7. Awaiting approval from admin to assign role ' . Carbon::now()->toDateTimeString()
        ]);

        if (!$already_in_cin7) {
            $contact = Contact::where('user_id', $user_id)->first()->update(
                [
                    'postalAddress1' => $request->input('street_address'),
                    'postalState' => $state_name,
                    'postalCity' => $city_name,
                    'postalPostCode' => $request->input('zip')
                ]
            );
        }
        
        

        $user = User::latest()->first();
        $user_id = $user->id;
        $registering_email = $user->email;
        $existing_contacts = Contact::where('email', $registering_email)->get();

        if ($existing_contacts->isNotEmpty()) {
            foreach ($existing_contacts as $existing_contact) {
                $existing_contact->user_id = $user->id;
                $existing_contact->save();
                if ($existing_contact->secondary_id) {
                    $secondary_id = $existing_contact->secondary_id;
                } else {
                    $secondary_id = '';
                }
                if ($existing_contact->contact_id || $existing_contact->secondary_id) {
                    $user_log = UserLog::create([
                        'user_id' => $user->id,
                        'secondary_id' => $secondary_id,
                        'contact_id' => $existing_contact->contact_id,
                        'action' => 'Singup',
                        'user_notes' => 'Existing Contact in Cin7 ' . Carbon::now()->toDateTimeString()
                    ]);
                }
                
                Auth::loginUsingId($user->id);
                $companies = Contact::where('user_id', auth()->user()->id)->get();
                if ($companies->count() == 1) {
                    if ($companies[0]->contact_id == null) {
                        UserHelper::switch_company($companies[0]->secondary_id);
                    } else {
                        UserHelper::switch_company($companies[0]->contact_id);
                    }
                }
                Session::put('companies', $companies);
            }
        }

        // $data = [
        //     'user' => $user,
        //     'subject' => 'New Register User',
        //     'from' => 'noreply@indoorsunhydro.com',
        // ];
        
        // if (!empty($users_with_role_admin)) {
        //     foreach ($users_with_role_admin as $role_admin) {
        //         $subject = 'New Register User';
        //         $data['email'] = $role_admin->email;
        //         MailHelper::sendMailNotification('emails.admin_notification', $data);
        //     }
        // }

        return response()->json([
            'success' => true,
            'created' => true,
            'msg' => 'Welcome, new player.'
        ]);
    }
}
