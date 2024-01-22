<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Contact;
use App\Models\ApiOrder;
use App\Models\ApiOrderItem;
use App\Models\UsState;
use App\Models\UsCity;
use App\Models\UserLog;
use App\Models\BuyList;
use App\Models\Product;
use App\Models\SecondaryContact;
use App\Models\CustomCompanyRole;
use App\Models\CustomRole;
use App\Http\Requests\Users\UserSignUpRequest;
use App\Http\Requests\Users\CompanyInfoRequest;
use App\Jobs\SyncContacts;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use App\Helpers\MailHelper;
use App\Helpers\UserHelper;
use App\Helpers\SettingHelper;
use App\Helpers\UtilHelper;
use \Illuminate\Support\Str;
use \Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SalesOrders;
use App\Models\Cart;
use App\Models\AdminSetting;
use App\Models\Category;
use App\Models\DailyApiLog;
use App\Models\Pricing;
use App\Models\Pricingnew;
use App\Models\ProductBuyList;
use App\Models\ProductOption;
use App\Models\WholesaleApplicationInformation;
use App\Models\WholesaleApplicationAddress;
use App\Models\WholesaleApplicationAuthorizationDetail;
use App\Models\WholesaleApplicationRegulationDetail;
use App\Models\WholesaleApplicationCard;
use App\Models\WholesaleApplicationImage;
use Illuminate\Support\Facades\File;
use App\Services\ZendeskService;
use Zendesk\API\HttpClient as ZendeskClient;
use Illuminate\Auth\Events\Validated;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use PDF;

class UserController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:user-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
        $this->middleware('permission:user-show', ['only' => ['show']]);
    }

    

    public function index(Request $request)
    {
        $page = $request->page;
        $search = $request->search;
        $sort_by_desc = $request->get('sort_by_desc');
        $sort_by_asc = $request->get('sort_by_asc');
        $sort_by_name = $request->get('sort_by_name');
        $sort_by_email = $request->get('sort_by_email');
        $primaryUserSearch = $request->primaryUserSearch;
        $secondaryUserSearch = $request->secondaryUserSearch;
        $usersData = $request->usersData;
        $secondaryUser = $request->secondaryUser;
        $user_query = User::withTrashed()->with('contact' , function($query) {
            $query->withTrashed();
        });
        if (!empty($usersData)) {
            if ($usersData == 'admin-user') {
                $user_query = $user_query->role(['Admin']);
            } elseif ($usersData == 'cin7-merged') {
                $user_query = $user_query->orWhereHas('contact', function ($query) {
                    $query->whereNotNull('contact_id');
                });
            } elseif ($usersData == 'not-merged') {
                $user_query = $user_query->orWhereHas('contact', function ($query) {
                    $query->whereNull('contact_id');
                });
            }
        }
        if (!empty($secondaryUser)) {

            if ($secondaryUser == 'secondary-user') {
                $user_query = $user_query->orWhereHas('contact', function ($query) {
                    $query->whereNotNull('secondary_id')->with('childeren');
                });
                $user = $user_query->limit(10)->get();
            }
            if ($secondaryUser == 'primary-user') {

                $user_query = $user_query->orWhereHas('contact', function ($query) {
                    $query->whereNotNull('contact_id');
                });
            }
        }
        if (!empty($search)) {
            $user_query = $user_query->where('first_name', 'LIKE', '%' . $search . '%')
                ->orWhere('last_name', 'like', '%' . $search . '%')
                ->orWhere(DB::raw("CONCAT(first_name,' ',last_name)"), 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhereHas('contact', function ($query) use ($search) {
                    $query->where('contact_id', 'like', '%' . $search . '%')
                        ->orWhere('company', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('firstName', 'like', '%' . $search . '%')
                        ->orWhere('lastName', 'like', '%' . $search . '%')
                        ->orWhere(DB::raw("CONCAT(firstName,' ',lastName)"), 'like', '%' . $search . '%');
                });
        }

        if (!empty($request->sort_by_desc)) {
            $user_query = $user_query->orderBy('id' , 'Desc');
        }
        if (!empty($request->sort_by_asc)) {
            $user_query = $user_query->orderBy('id' , 'Asc');
        }


        if (!empty($request->sort_by_name)) {
            if ($sort_by_name == 'Asc') {
                $user_query = $user_query->orderBy('first_name' , 'Asc')->orderBy('last_name' , 'Asc');
            }
            if ($sort_by_name == 'Desc') {
                $user_query = $user_query->orderBy('first_name' , 'Desc')->orderBy('last_name' , 'Desc');
            }
        }

        if (!empty($request->sort_by_email)) {
            if ($sort_by_email == 'Asc') {
                $user_query = $user_query->orderBy('email' , 'Asc');
            }
            if ($sort_by_email == 'Desc') {
                $user_query = $user_query->orderBy('email' , 'Desc');
            }
        }

        $data = $user_query->orderBy('created_at' , 'Desc')->paginate(10);
        $users = User::role(['Admin'])->get();
        $count = $users->count();
        return view('admin.users.index', compact(
            'data',
            'count',
            'search',
            'usersData',
            'secondaryUser',
            'sort_by_desc',
            'sort_by_asc',
            'sort_by_name',
            'sort_by_email',
        ))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();
        return view('admin.users.create', compact(
            'roles'
        ));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        return redirect()->route(
            'users.index'
        )
            ->with(
                'success',
                'User created successfully'
            );
    }


    public function show($id)
    {
        $user = User::find($id);
        return view('admin.users.show', compact('user'));
    }

    public function edit($id)
    {
        $this->middleware('permission:user-edit', ['only' => ['edit', 'update']]);
        $user = User::where('id', $id)->with('contact')->first();
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();

        $custom_company_rol_ids = CustomCompanyRole::pluck('role_id')->toArray();

        $custom_company_roles = Role::whereIn('id', $custom_company_rol_ids)->pluck('name', 'id')->all();

        $companies = Contact::where('user_id', $id)->pluck('company')->all();

        $custom_roles = [];

        $all_custom_roles = CustomRole::where('user_id', $id)->get();
        if (!empty($all_custom_roles)) {
            foreach ($all_custom_roles as $custom_role) {
                $custom_roles[$custom_role->company][$custom_role->role_id] = true;
            }
        }

        return view('admin.users.edit', compact(
            'user',
            'roles',
            'userRole',
            'companies',
            'custom_roles',
            'custom_company_roles'
        ));
    }

    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'same:confirm-password',
            'roles' => 'required',
        ]);


        $custom_company_roles = $request->custom_company_roles;


        $input = $request->all();
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }
        $company = $request->company;
        $user = User::find($id);
        $user->update($input);

        DB::table('model_has_roles')->where('model_id', $id)->delete();
        DB::table('custom_roles')->where('user_id', $id)->delete();

        $user->assignRole($request->input('roles'));
        if (!empty($custom_company_roles)) {
            foreach ($custom_company_roles as $company_name => $custom_company_roles) {
                foreach ($custom_company_roles as $role_id => $checked_value) {
                    if ($checked_value == 'on') {
                        CustomRole::create([
                            'role_id' => $role_id,
                            'user_id' => $id,
                            'company' => $company_name
                        ]);
                    }
                }
            }
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if (!empty($user)) {
            $user->is_deleted = now();
            $user->save();
        }
        User::find($id)->delete();
        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully');
    }

    public function userRegistration()
    {
        if (!auth()->user()) {
            $data['setting'] = AdminSetting::where('option_name' , 'enable_sign_up')->first();
            $data['states'] = UsState::get(["state_name", "id"]);
            return view('user-registration-second',  $data);
        } else {
            return redirect()->route('my_account');
        }
    }

    public function lost_password()
    {
        return view('lost-password');
    }

    public function recover_password(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        $plain_password = Str::random(10) . date('YmdHis');
        $encrypted_password = bcrypt($plain_password);
        $hash = Str::random(10000) . $user->first_name . date('YmdHis');
        $hash = md5($hash);

        $user->password = $encrypted_password;
        $user->hash = $hash;
        $user->save();
        $base_url = url('/');

        $url = $base_url . '/index?hash=' . $hash;
        $data['email'] = $user->email;
        $data['url'] = $url;


        $data['content'] = 'Password Reset';
        $data['subject'] = 'Password Reset';
        $data['from'] = env('MAIL_FROM_ADDRESS');
        $data['plain'] = $plain_password;
        MailHelper::sendMailNotification('emails.reset-password', $data);

        return redirect()->back()->with('success', 'Password reset link sent succssfully!');
    }


    public function fetchCity(Request $request)
    {
        $data['cities'] = UsCity::where("state_id", $request->state_id)->get(["city", "id"]);
        return response()->json($data);
    }

    public function process_login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);
        $credentials = $request->except(['_token']);
        $user = User::where('email', $request->email)->first();

        $email_user = session::put('user', $user);
        $cart = [];
        if (auth()->attempt($credentials)) {
            if (auth()->user()->allow_access == 0) {
                Session::flush();
                Auth::logout();
                session()->flash('message', 'Your account has been disabled.');
                return redirect()->back();
            } else {
                $user_id = auth()->user()->id;
                if ($request->session()->has('cart_hash')) {
                    $cart_hash = $request->session()->get('cart_hash');
                    $cart_items = Cart::where('cart_hash', $cart_hash)->where('is_active', 1)->where('user_id', 0)->get();
                    foreach ($cart_items as $cart_item) {
                        $cart_item->user_id = $user_id;
                        $cart_item->save();
                    }
                }
                if ($user->hasRole(['Admin'])) {
                    session()->flash('message', 'Successfully Logged in');
                    $companies = Contact::where('user_id', auth()->user()->id)->get();
                    if ($companies->count() == 1) {
                        
                        if ($companies[0]->contact_id == null) {
                            UserHelper::switch_company($companies[0]->secondary_id);
                        } else {
                            UserHelper::switch_company($companies[0]->contact_id);
                        }
                    }
                    Session::put('companies', $companies);

                    return redirect()->route('admin.view');
                } else {
                    $companies = Contact::where('user_id', auth()->user()->id)->get();
                    if ($companies->count() == 1) {
                        if ($companies[0]->contact_id == null) {
                            UserHelper::switch_company($companies[0]->secondary_id);
                        } else {
                            UserHelper::switch_company($companies[0]->contact_id);
                        }
                    }
                    if ($companies->count() == 2) {
                        foreach ($companies as $company) {
                            if ($company->status == 1) {
                                if ($company->contact_id == null) {
                                    UserHelper::switch_company($company->secondary_id);
                                } else {
                                    UserHelper::switch_company($company->contact_id);
                                }
                            }
                        }
                    }
                    Session::put('companies', $companies);
                    if (!empty(session()->get('cart'))) {
                        return redirect()->route('cart');
                    } else {
                        if ($user->is_updated == 1) {

                            $companies = Contact::where('user_id', auth()->user()->id)->get();

                            if ($companies[0]->contact_id == null) {
                                UserHelper::switch_company($companies[0]->secondary_id);
                            } else {
                                UserHelper::switch_company($companies[0]->contact_id);
                            }
                            // Session::put('companies', $companies);
                            $previousUrl = session('previous_url', '/'); 
                            return redirect()->intended($previousUrl);
                            // return redirect()->route('my_account');
                        } else {
                            $companies = Contact::where('user_id', auth()->user()->id)->get();
                            // Session::put('companies', $companies);
                            if ($companies[0]->contact_id == null) {
                                UserHelper::switch_company($companies[0]->secondary_id);
                            } else {
                                UserHelper::switch_company($companies[0]->contact_id);
                            }
                            return redirect('/');
                        }
                    }
                }
            }
            
        } else {
            session()->flash('message', 'Invalid credentials');
            return redirect()->back();
        }
    }

    public function checkEmail(Request $request) {
        $validatedData = $request->validate([
            'email' => 'required',
        ]);
        $user = User::where('email', $request->get('email'))->first();
        if (!empty($user)) {
           
            return response()->json([
                'success' => true,
                'msg' => 'Email Already Exists.'
            ]);
        }
    }
    public function process_signup(UserSignUpRequest $request)
    {
        // $user = User::where('email', $request->get('email'))->first();
        // if (!empty($user)) {
           
        //     return response()->json([
        //         'success' => true,
        //         'msg' => 'Email Already Exists.'
        //     ]);
        // }
        
        // if ($request->get('email')) {
        //     $user = User::create([
        //         'email' => strtolower($request->get('email'))
        //     ]);
        //     return response()->json([
        //         'success' => true,
        //         'created' => true,
        //         'msg' => 'Welcome, new player.'
        //     ]);
        // } else {
        //     $user = User::latest()->first();
        //     $user_id = $user->id;
        //     $registering_email = $user->email;
        //     $existing_contacts = Contact::where('email', $registering_email)->get();
        //     if ($existing_contacts->isNotEmpty()) {
        //         $user_Update = User::where("id", $user_id)->update([
        //             "password" => bcrypt($request->get('password'))
        //         ]);
        //     } else {
        //         $user_Update = User::where("id", $user_id)->update([
        //             "first_name" => $request->get('first_name'),
        //             "last_name" => $request->get('last_name'),
        //             "password" => bcrypt($request->get('password'))
        //         ]);
        //     }

        //     if ($existing_contacts->isNotEmpty()) {
        //         foreach ($existing_contacts as $existing_contact) {
        //             $existing_contact->user_id = $user->id;
        //             $existing_contact->save();
        //             if ($existing_contact->secondary_id) {
        //                 $secondary_id = $existing_contact->secondary_id;
        //             } else {
        //                 $secondary_id = '';
        //             }
        //             if ($existing_contact->contact_id || $existing_contact->secondary_id) {
        //                 $user_log = UserLog::create([
        //                     'user_id' => $user->id,
        //                     'secondary_id' => $secondary_id,
        //                     'contact_id' => $existing_contact->contact_id,
        //                     'action' => 'Singup',
        //                     'user_notes' => 'Existing Contact in Cin7 ' . Carbon::now()->toDateTimeString()
        //                 ]);
        //             }
        //             Auth::loginUsingId($user->id);
        //         }
        //         return response()->json([
        //             'code' => 201,
        //             'success' => true,
        //             'updated' => true,
        //             'msg' => 'Existing contact updated'
        //         ]);
        //     }
        // }

        // return response()->json(['success' => true, 'created' => true, 'msg' => 'Welcome, new player.']);

        $validatedData = $request->validate([
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

    public function checkAddress(Request $request) {
    
        $validatedData = $request->validate([
            'company_name' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            'phone' => 'required',
        ]);

        if($validatedData) {
            return response()->json([
                'success' => true,
                'msg' => $validatedData
            ]);
        }
    }

    public function invitation_signup(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'email|unique:users,email',
            'password' => 'required|min:5',
            'confirm_password' => 'required|same:password'
        ]);

        if ($request->is_secondary == 1) {
            $secondary_contact = SecondaryContact::where('email', $request->email)->with('contact')->first();

            if (empty($secondary_contact)) {
                return redirect('/');
            }
            $user = User::create([
                'email' => $request->email,
                'password' =>  bcrypt($request->password),
                'first_name' => $secondary_contact->firstName,
                'last_name' => $secondary_contact->lastName
            ]);

            SecondaryContact::where('id', $secondary_contact->id)->update([
                'hashUsed' => 1,
            ]);

            $user_id = $user->id;
            $contact = Contact::create([
                'status' => 0,
                'user_id' => $user_id,
                'type' => 'Customer',
                'pricingColumn' => $secondary_contact->contact->priceColumn,
                'company' => $secondary_contact->company,
                'firstName' => $secondary_contact->firstName,
                'lastName' => $secondary_contact->lastName,
                'jobTitle' => $secondary_contact->jobTitle,
                'mobile' => $secondary_contact->mobile,
                'phone' => $secondary_contact->phone,
                'email' => $secondary_contact->email,
                'hashKey' => $secondary_contact->hashKey,
                'hashUsed' => 1
            ]);
            $encodec = $contact->toArray();
            unset($encodec['id']);
            $contact = [
                $encodec
            ];
            SyncContacts::dispatch('create_contact', $contact)->onQueue(env('QUEUE_NAME'));
        } else {
            $contact = Contact::where('email', $request->email)->first();
            $user = User::create([
                'email' => $request->email,
                'password' =>  bcrypt($request->password),
                'first_name' => $contact->firstName,
                'last_name' => $contact->lastName
            ]);

            $contact->user_id = $user->id;
            $contact->hashUsed = true;
            $contact->save();
        }
        Auth::loginUsingId($user->id);
        return redirect('/');
        return back()->with('success', 'User created successfully.');
    }


    public function logout()
    {
        
        Session::forget('contact_id');
        Session::forget('company');
        Session::forget('companies');
        Session::forget('cart');
        Session::forget('logged_in_as_another_user');
        Session::flush();

        Auth::logout();

        return redirect()->route('user');
    }

    public function save_contact(CompanyInfoRequest $request)
    {
        $price_column = null;
        $default_price_column = AdminSetting::where('option_name', 'default_price_column')->first();
        if (!empty($default_price_column)) {
            $price_column = ucfirst($default_price_column->option_value);
        }
        else {
            $price_column = 'RetailUSD';
        }
        $validatedData = $request->validate(
            [
                'street_address' => [
                    'required'
                    // 'regex:/^[a-zA-Z0-9\s-]+$/'
                ],
                'state_id' => 'required',
                'city_id' => 'required',
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
                'priceColumn' => $price_column,
                'user_id' => $user_id,
                'firstName' => $user->first_name,
                'type' => 'Customer',
                'lastName' => $user->last_name,
                'email' => $user->email,
                'is_parent' => 1,
                'status' => 0,
                'tax_class' => strtolower($state_name) == strtolower('California') ? '8.75%' : 'Out of State',
                'paymentTerms' => $request->paymentTerms,
                'charge_shipping' => 1,    
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
        $data = [
            'user' => $user,
            'subject' => 'New Register User',
            'name' => $user->first_name . ' ' . $user->last_name,
            'content' => 'Your account registration request has been submitted. You will receive an email once your account has been approved.',
            'email' => $user->email,
            'subject' => 'Your account registration request ',
            'from' => 'noreply@indoorsunhydro.com',
        ];
        
        if (!empty($users_with_role_admin)) {
            foreach ($users_with_role_admin as $role_admin) {
                $subject = 'New Register User';
                $data['email'] = $role_admin->email;
                MailHelper::sendMailNotification('emails.admin_notification', $data);
            }
        }
        // $data['name'] = $user->first_name . ' ' . $user->last_name;
        // $data['email'] = $user->email;
        // $data['content'] = 'Your account registration request has been submitted. You will receive an email once your account has been approved.';
        // $data['subject'] = 'Your account registration request ';
        // MailHelper::sendMailNotification('emails.user_registration_notification', $data);

        return response()->json([
            'success' => true,
            'created' => true,
            'msg' => 'Welcome, new player.'
        ]);
        
    }

    public function my_account(Request $request)
    {
        $sort_by = '';
        $address_user = null;
        $contact_id = session()->get('contact_id');
        $user_id = auth()->id();
        if (!auth()->user()) {
            return redirect('/user/');
        } else {
            $user = User::where('id', $user_id)->first();
            $can_approve_order = $user->hasRole('Order Approver');
            $selected_company = Session::get('company');
            $all_ids = UserHelper::getAllMemberIds($user);
            $contact_ids = Contact::whereIn('id', $all_ids)
                ->pluck('contact_id')
                ->toArray();

            $frequent_products = $this->buy_again_products($request);
            
            $user_orders_query = ApiOrder::with(['createdby'])->whereIn('memberId', $contact_ids)
                ->with('contact' , function($query) {
                    $query->orderBy('company');
                })
                ->with('apiOrderItem.product');
           
            if (!empty($request->sort_by)) {
                $sort_by = $request->sort_by;
                if ($sort_by == 'recent') {

                    $user_orders = $user_orders_query->orderBy('created_at' , 'Desc')->paginate(10);
                }
                if ($sort_by == 'amount') {

                    $user_orders = $user_orders_query->orderBy('total' , 'Desc')->paginate(10);
                }

            } else {
                $user_orders = $user_orders_query->orderBy('created_at' , 'Desc')->paginate(10);
            }
            // main order search
            $search = $request->search;
            if (!empty($search)) {
                $user_orders = $user_orders_query->with('apiOrderItem')
                ->where('branchEmail' , 'like' , '%'.$search.'%')
                ->orwhereHas('apiOrderItem.product' , function($query) use ($search) {
                    $query->where('code' , 'like' , '%'.$search.'%')
                    ->orWhere('name' , 'like' , '%'.$search.'%');
                })
                ->paginate(10);
            }

            //filter date search
            
            $date_filter = $request->date_filter;
            $this_month = Carbon::now()->month;
            $last_month = [Carbon::now()->subMonth(1) , Carbon::now()];
            $last_3_months = [Carbon::now()->subMonth(3) , Carbon::now()];
            $last_5_months = [Carbon::now()->subMonth(5) , Carbon::now()];
            $past_year = [Carbon::now()->subYear(1) , Carbon::now()];

            if (!empty($date_filter)) {
                
                if ($date_filter == 'this-month') {
                    $user_orders = $user_orders_query
                    ->whereMonth('created_at', $this_month)
                    ->orderBy('created_at' , 'Desc')
                    ->paginate(10);
                }
                
                if ($date_filter == 'last-month') {
                    $user_orders = $user_orders_query
                    ->whereBetween('created_at', $last_month)
                    ->orderBy('created_at' , 'Desc')
                    ->paginate(10);
                }

                if ($date_filter == 'last-3-months') {
                    $user_orders = $user_orders_query
                    ->whereBetween('created_at', $last_3_months)
                    ->orderBy('created_at' , 'Desc')
                    ->paginate(10);
                }
                
                if ($date_filter == 'last-5-months') {
                    $user_orders = $user_orders_query
                    ->whereBetween('created_at', $last_5_months)
                    ->orderBy('created_at' , 'Desc')
                    ->paginate(10);
                }
                
                if($date_filter == 'last-year') {
                    $user_orders = $user_orders_query
                    ->whereBetween('created_at', $past_year)
                    ->orderBy('created_at' , 'Desc')
                    ->paginate(10);
                }
            }   else {
                $user_orders = $user_orders_query
                ->whereBetween('created_at', $last_3_months)
                ->orderBy('created_at' , 'Desc')
                ->paginate(10);
            }

            //order submitters
            $submitter_filter = $request->submitter_filter;
            $primary_submitters = $user_orders_query->distinct('primaryId')->pluck('primaryId')->toArray();
            $secondary_submitters = $user_orders_query->distinct('secondaryId')->pluck('secondaryId')->toArray();
            $order_submitters_array = array_merge($primary_submitters, $secondary_submitters);
            $order_submitters = Contact::whereIn('contact_id', $order_submitters_array)->orWhereIn('secondary_id' , $order_submitters_array)->get();
            if (!empty($submitter_filter) && $submitter_filter != 'all') {
                $user_orders = ApiOrder::with(['createdby'])->whereIn('memberId', $contact_ids)
                ->with('contact' , function($query) {
                    $query->orderBy('company');
                })
                ->with('apiOrderItem.product')
                ->where('primaryId' , $submitter_filter)
                ->orWhere('secondaryId' , $submitter_filter)->paginate(10);
            }
            if (empty($submitter_filter)) {
                $submitter_filter = 'all';
            } 
            $custom_roles_with_company = DB::table('custom_roles')
                ->where('user_id', $user_id)
                ->where('company', $selected_company)
                ->first();
                
            if (!empty($custom_roles_with_company) && $custom_roles_with_company->company == $selected_company) {
                $order_approver_for_company = true;
            } else {
                $order_approver_for_company = false;
            }

            $user_address = Contact::where('user_id', $user_id)->first();
            $secondary_contacts = Contact::whereIn('id', $all_ids)->get();
            $list = BuyList::where('id', 20)->with('list_products.product.options')->first();
            $contact = Contact::where('email', $user_address->email)->first();
            $companies = Contact::where('user_id', $user_id)->get();

            if ($contact) {
                $parent = Contact::where('contact_id', $contact->parent_id)->get();
            } else {
                $parent = "";
            }

            $states = UsState::all();
            $wishlist = BuyList::with('list_products')->where('user_id', $user_id)->first();

            
            
            $get_contact = Contact::where('user_id', $user_id)
            ->where('status', 1)
            ->where('company', $selected_company)
            ->with('states')
            ->with('cities')
            ->first();

            if (!empty($get_contact->contact_id)) {
                $address_user = Contact::where('user_id', $user_id)->where('contact_id' , $get_contact->contact_id)->first();
            } else {
                if (!empty($get_contact->secondary_id)) {
                    $parent = Contact::where('secondary_id', $get_contact->secondary_id)->first();
                    $address_user = Contact::where('contact_id', $parent->parent_id)->first();
                }
            }
            return view('my-account', compact(
                'user',
                'user_address',
                'states',
                'secondary_contacts',
                'parent',
                'companies',
                'user_orders',
                'can_approve_order',
                'order_approver_for_company',
                'sort_by',
                'contact_id',
                'search',
                'date_filter',
                'frequent_products',
                'order_submitters','submitter_filter','address_user'
            ));
        }
    }

    // buy again products 
    public function buy_again_products(Request $request) {
        $user_id = auth()->id();
        $user = User::where('id', $user_id)->first();
        $all_ids = UserHelper::getAllMemberIds($user);
        $contact_ids = Contact::whereIn('id', $all_ids)
                ->pluck('contact_id')
                ->toArray();
        $product_price = 0;
        $user_price_column =UserHelper::getUserPriceColumn();
        
        $frequent_products = null;

        $perPage = 4;
        $currentPage = $request->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;

        $order_ids = ApiOrder::whereIn('memberId', $contact_ids)->pluck('id')->toArray();
        $product_ids = ApiOrderItem::whereIn('order_id', $order_ids)->pluck('product_id')->toArray();
        $option_ids = ProductOption::whereIn('product_id', $product_ids)->pluck('option_id')->toArray();
        $price_ids = Pricingnew::whereIn('option_id', $option_ids)->where($user_price_column, '>' , 0)->pluck('option_id')->toArray();
        $product_options_ids = ProductOption::whereIn('option_id', $price_ids)->pluck('product_id')->toArray();
        $category_ids = Product::whereIn('product_id', $product_options_ids)->pluck('category_id')->toArray();
        $active_category_ids = Category::whereIn('category_id', $category_ids)->where('is_active' , '1')->pluck('category_id')->toArray();
        $active_products_ids = Product::whereIn('category_id' ,$active_category_ids)->where('status', '!=', 'Inactive')->pluck('product_id')->toArray();
        

        $buy_again_query = Product::with('categories' , 'options')
            ->whereIn('product_id', $active_products_ids);
        $total_products = $buy_again_query->count();

        $buy_again = $buy_again_query->take($perPage)
            ->skip($offset)
            ->get();
        
        $total = $total_products >= 16 ? 16 : $total_products;
        $frequent_products = new LengthAwarePaginator($buy_again, $total, $perPage, $currentPage, [
            'path' => url('/my-account/buy-again-products'),
            'query' => $request->query(),
        ]);

        
        return $frequent_products;
    }

    //get favorites in separate page
    public function myFavorites(Request $request)
    {
        $lists = [];
        $per_page = '';
        $address_user = null;
        $user_id = Auth::id();
        if (!$user_id) {
            return redirect('/user/');
        }
        $contact_id = session()->get('contact_id');
        $lists_query = BuyList::where('user_id', $user_id)
            ->where('contact_id', $contact_id)
            ->with('list_products.product.options.price')
            ->where('title', 'My Favorites')
            ->get();
        foreach ($lists_query as $list) {
            $favorite_list = ProductBuyList::with('buylist','product.options.price')->where('list_id', $list->id);
            if ($request->get('per_page')) {
                $per_page = $request->get('per_page');
                $lists = $favorite_list->paginate($per_page);
            } else {
                $lists = $favorite_list->paginate(6);
            }
        }

        $user = User::where('id', $user_id)->first();
        $all_ids = UserHelper::getAllMemberIds($user);
        $user_address = Contact::where('user_id', $user_id)->first();
        $secondary_contacts = Contact::whereIn('id', $all_ids)->get();
        $list = BuyList::where('id', 20)->with('list_products.product.options')->first();
        $contact = Contact::where('email', $user_address->email)->first();
        $companies = Contact::where('user_id', $user_id)->get();

        if ($contact) {
            $parent = Contact::where('contact_id', $contact->parent_id)->get();
        } else {
            $parent = "";
        }

        $states = UsState::all();
        if ($request->ajax()) {
            $user_orders = ApiOrder::where('user_id', $user_id)->with('apiOrderItem')->get();
            foreach ($user_orders as $user_order) {
                $createdDate = $user_order->created_at;
                $user_order->createdDate = $createdDate->format('F \  j, Y');
            }
            return $user_orders;
        }
        $wishlist = BuyList::with('list_products')->where('user_id', $user_id)->first();
        $selected_company = Session::get('company');
        $get_contact = Contact::where('user_id', $user_id)
            ->where('status', 1)
            ->where('company', $selected_company)
            ->with('states')
            ->with('cities')
            ->first();
            if (!empty($get_contact->contact_id)) {
                $address_user = Contact::where('user_id', $user_id)->where('contact_id' , $get_contact->contact_id)->first();
            } else {
                if (!empty($get_contact->secondary_id)) {
                    $parent = Contact::where('secondary_id', $get_contact->secondary_id)->first();
                    $address_user = Contact::where('contact_id', $parent->parent_id)->first();
                }
            }
        return view('my-account.my-favorites', compact(
            'lists',
            'user',
            'user_address',
            'secondary_contacts',
            'parent',
            'companies',
            'states',
            'per_page',
            'address_user'
        ));
        // return $images;
    }
    //end

    //order
    public function myOrders()
    {
        $user_id = auth()->id();
        $user = User::where('id', $user_id)->first();
        $all_ids = UserHelper::getAllMemberIds($user);
        $companies = Contact::where('user_id', $user_id)->get();
        $user_address = Contact::where('user_id', $user_id)->first();
        $contact = Contact::where('email', $user_address->email)->first();
        $secondary_contacts = Contact::whereIn('id', $all_ids)->get();
        if ($contact) {
            $parent = Contact::where('contact_id', $contact->parent_id)->get();
        } else {
            $parent = "";
        }
        $contact_ids = Contact::whereIn('id', $all_ids)
            ->pluck('contact_id')
            ->toArray();

        $user_orders = ApiOrder::whereIn('memberId', $contact_ids)
            ->with('contact')
            ->with('apiOrderItem')
            ->orderBy('id', 'desc')
            ->get();
        $can_approve_order = $user->hasRole('Order Approver');
        $selected_company = Session::get('company');

        $custom_roles_with_company = DB::table('custom_roles')
            ->where('user_id', $user_id)
            ->where('company', $selected_company)
            ->first();
        if (!empty($custom_roles_with_company) && $custom_roles_with_company->company == $selected_company) {
            $order_approver_for_company = true;
        } else {
            $order_approver_for_company = false;
        }

        $states = UsState::all();
        return view('my-account.my-orders', compact(
            'user',
            'user_address',
            'states',
            'secondary_contacts',
            'parent',
            'companies',
            'user_orders',
            'can_approve_order',
            'order_approver_for_company'

        ));
    }
    // order detail 
    public function order_detail(Request  $request, $id)
    {
        $order_detail = ApiOrder::where('id', $id)
            ->with('contact')
            ->with('apiOrderItem.product.options')
            ->with('texClasses')
            ->first();
        $user_id = Auth::id();
        if (!$user_id) {
            return redirect('/user/');
        }
        $contact_id = session()->get('contact_id');
        $lists = BuyList::where('user_id', $user_id)
            ->where('contact_id', $contact_id)
            ->with('list_products.product.options.price')
            ->where('title', 'My Favorites')
            ->get();

        $user = User::where('id', $user_id)->first();
        $all_ids = UserHelper::getAllMemberIds($user);
        $user_address = Contact::where('user_id', $user_id)->first();
        $secondary_contacts = Contact::whereIn('id', $all_ids)->get();
        $list = BuyList::where('id', 20)->with('list_products.product.options')->first();
        $contact = Contact::where('email', $user_address->email)->first();
        $companies = Contact::where('user_id', $user_id)->get();

        if ($contact) {
            $parent = Contact::where('contact_id', $contact->parent_id)->get();
        } else {
            $parent = "";
        }
        $user_order = ApiOrder::with('texClasses')->where('id', $id)->first();
        $createdDate = $user_order->created_at;
        $user_order->createdDate = $createdDate->format('F \  j, Y');
        $user_address = Contact::where('user_id', $user_id)->first();
        $orderdetails = ApiOrderItem::where('order_id', $id)->with('product')->get();
        return view('my-account.order-detail', compact(
            'lists',
            'user',
            'user_address',
            'secondary_contacts',
            'parent',
            'companies',
            'user_order',
            'orderdetails',
            'order_detail'
        ));
    }
    // address

    public function address(Request  $request)
    {
        $user_id = Auth::id();
        $address_user = null;
        if (!$user_id) {
            return redirect('/user/');
        }
        $contact_id = session()->get('contact_id');
        $lists = BuyList::where('user_id', $user_id)->where('contact_id', $contact_id)
            ->with('list_products.product.options.price')
            ->where('title', 'My Favorites')
            ->get();
        $selected_company = Session::get('company');
        $get_contact = Contact::where('user_id', $user_id)
        ->where('status', 1)
        ->where('company', $selected_company)
        ->with('states')
        ->with('cities')
        ->first();

        if (!empty($get_contact->contact_id)) {
            $address_user = Contact::where('user_id', $user_id)->where('contact_id' , $get_contact->contact_id)->first();
        } else {
            if (!empty($get_contact->secondary_id)) {
                $parent = Contact::where('secondary_id', $get_contact->secondary_id)->first();
                $address_user = Contact::where('contact_id', $parent->parent_id)->first();
            }
        }
        
        $user = User::where('id', $user_id)->first();
        $all_ids = UserHelper::getAllMemberIds($user);
        $user_address = Contact::where('user_id', $user_id)->first();
        $secondary_contacts = Contact::whereIn('id', $all_ids)->paginate(10);
        $secondary_contacts_data = Contact::whereIn('id', $all_ids)->get();
        $pluck_default_user = Contact::whereIn('id', $all_ids)->first();
        $list = BuyList::where('id', 20)->with('list_products.product.options')->first();
        $contact = Contact::where('email', $user_address->email)->first();
        $companies = Contact::where('user_id', $user_id)->get();
        // if (!empty($pluck_default_user)) {
        //     if (!empty($pluck_default_user->contact_id)) {
        //         $address_user = Contact::where('contact_id', $pluck_default_user->contact_id)->first();
        //     } else {
        //         $address_user = Contact::where('contact_id', $pluck_default_user->parent_id)->first();
        //     }   
        // }
        
        if ($contact) {
            $parent = Contact::where('contact_id', $contact->parent_id)->get();
        } else {
            $parent = "";
        }
        $states = UsState::all();
        return view('my-account.address', compact(
            'lists',
            'user_address',
            'secondary_contacts',
            'parent',
            'companies',
            'states',
            'address_user',
            'contact_id',
            'secondary_contacts_data'
        ));
    }

    // make address default
    public function make_address_default(Request $request) {
        $contact_key = $request->id;
        $data = $request->contacts;
        $data_decode = json_decode($data);
        foreach ($data_decode as $data) {
            if ($contact_key == $data->id) {
                $contact = Contact::where('id', $contact_key)->first();
                $contact->is_default = 1;
                $contact->save();
            } else {
                $contact = Contact::where('id', $data->id)->first();
                $contact->is_default = 0;
                $contact->save();
            }
        }
        return redirect()->back()->with('success', 'Address Set default successfully');
    }

    //account details
    public function account_profile(Request  $request)
    {
        $address_user = null;
        $user_id = Auth::id();
        if (!$user_id) {
            return redirect('/user/');
        }
        $contact_id = session()->get('contact_id');
        $lists = BuyList::where('user_id', $user_id)->where('contact_id', $contact_id)->with('list_products.product.options.price')->where('title', 'My Favorites')->get();

        $user = User::where('id', $user_id)->first();
        $all_ids = UserHelper::getAllMemberIds($user);
        $user_address = Contact::where('user_id', $user_id)->first();
        $secondary_contacts = Contact::whereIn('id', $all_ids)->get();
        $list = BuyList::where('id', 20)->with('list_products.product.options')->first();
        $contact = Contact::where('email', $user_address->email)->first();
        $companies = Contact::where('user_id', $user_id)->get();
        $user_profile = User::where('id', $user_id)->with('contact')->first();
        if ($contact) {
            $parent = Contact::where('contact_id', $contact->parent_id)->get();
        } else {
            $parent = "";
        }
        $states = UsState::all();
        $selected_company = Session::get('company');
        $get_contact = Contact::where('user_id', $user_id)
        ->where('status', 1)
        ->where('company', $selected_company)
        ->with('states')
        ->with('cities')
        ->first();
        if (!empty($get_contact->contact_id)) {
            $address_user = Contact::where('user_id', $user_id)->where('contact_id' , $get_contact->contact_id)->first();
        } else {
            if (!empty($get_contact->secondary_id)) {
                $parent = Contact::where('secondary_id', $get_contact->secondary_id)->first();
                $address_user = Contact::where('contact_id', $parent->parent_id)->first();
            }
        }
        return view('my-account.account_profile', compact(
            'lists',
            'user',
            'user_address',
            'secondary_contacts',
            'parent',
            'companies',
            'states',
            'user_profile',
            'address_user'
        ));
    }

    //additional users
    public function additional_users(Request  $request)
    {
        $address_user = null;
        $user_id = Auth::id();
        if (!$user_id) {
            return redirect('/user/');
        }
        $contact_id = session()->get('contact_id');
        $lists = BuyList::where('user_id', $user_id)->where('contact_id', $contact_id)->with('list_products.product.options.price')->where('title', 'My Favorites')->get();

        $user = User::with('contact')->where('id', $user_id)->first();
        $all_ids = UserHelper::getAllMemberIds($user);
        $user_address = Contact::where('user_id', $user_id)->first();
        $all_companies = Contact::whereIn('id', $all_ids)->groupBy('company')->get();
        $list = BuyList::where('id', 20)->with('list_products.product.options')->first();
        $contact = Contact::where('email', $user_address->email)->first();
        $companies = Contact::where('user_id', $user_id)->get();
        if ($contact) {
            $parent = Contact::where('contact_id', $contact->parent_id)->get();
        } else {
            $parent = "";
        }
        $states = UsState::all();

        // update balance owing with api request 
        $client = new \GuzzleHttp\Client();
        $cin7_auth_username = SettingHelper::getSetting('cin7_auth_username');
        $cin7_auth_password = SettingHelper::getSetting('cin7_auth_password');
        $balance_owing = AdminSetting::where('option_name', 'update_balance_owing')->first();

        if (!empty($balance_owing) && strtolower($balance_owing->option_value) == 'yes') {
            if (count($companies) > 0) {
                foreach ($companies as $company) {
                    if ($company->status == 1) {
                        $update_contact = Contact::where('id', $company->id)->first();
                        $contact_id = $update_contact->contact_id;
                        $cin7_get_contact_url = config('services.cin7.get_contact_url');
                        $url = $cin7_get_contact_url.$contact_id;

                        try {
                            $response = $client->request('GET', $url, [
                                'auth' => [$cin7_auth_username, $cin7_auth_password]
                            ]);
                            $response = json_decode($response->getBody()->getContents());
                            $balance_owing = $response->balanceOwing;
                            $credit_limit = $response->creditLimit;
                            $update_contact->balance_owing = $balance_owing;
                            $update_contact->credit_limit = $credit_limit;
                            $update_contact->save();

                            UtilHelper::saveDailyApiLog('update_contact');

                        } catch (\Exception $e) {
                            $e->getMessage();
                        }
                    }
                }
            }
        }
        $selected_company = Session::get('company');
        $get_contact = Contact::where('user_id', $user_id)
        ->where('status', 1)
        ->where('company', $selected_company)
        ->with('states')
        ->with('cities')
        ->first();
        if (!empty($get_contact->contact_id)) {
            $address_user = Contact::where('user_id', $user_id)->where('contact_id' , $get_contact->contact_id)->first();
        } else {
            if (!empty($get_contact->secondary_id)) {
                $parent = Contact::where('secondary_id', $get_contact->secondary_id)->first();
                $address_user = Contact::where('contact_id', $parent->parent_id)->first();
            }
        }

        // $secondary_contacts_data = [];
        // if (count($all_companies) > 0) {
        //     foreach($all_companies as $companies) {
        //         $get_secondary_contacts_data =  Contact::withTrashed()->with('allow_user')->where('company', $companies->company)->get();
        //         if (count($get_secondary_contacts_data)) {
        //             foreach ($get_secondary_contacts_data as $get_secondary_contact_data) {
        //                 array_push($secondary_contacts_data , $get_secondary_contact_data);
        //             }
        //         }
        //     }
        // }

        return view('my-account.additional_users', compact(
            'lists',
            'user',
            'user_address',
            // 'secondary_contacts',
            'parent',
            'companies',
            'states',
            'contact_id',
            'all_companies',
            'address_user',
            // 'secondary_contacts_data'
        ));
    }

    public function my_qoutes()
    {
        $user_id = auth()->id();
        $qoutes = BuyList::where('user_id', $user_id)->where('type', 'Quote')->get();
        return response()->json([
            'data' => $qoutes,
            'msg' => 'success'
        ]);
    }

    public function my_qoutes_details($id)
    {
        $user_id = auth()->id();
        $list = BuyList::where('user_id', $user_id)
            ->where('id', $id)
            ->where('type', 'qoute')
            ->with('list_products.product.options')
            ->first();

        return view('user-list-detail', compact(
            'list'
        ))->render();
    }

    public function my_qoute_edit($id)
    {
        $list = BuyList::where('id', $id)->with('list_products.product.options')->first();
        $products = Product::paginate(10);
        return view('user-qoute-edit', compact('products', 'list'))->render();
    }

    public function user_order_detail($id)
    {
        $user_id = auth()->id();
        // $user_info = User::where('user_')
        $user_order = ApiOrder::where('id', $id)->first();
        $createdDate = $user_order->created_at;
        $user_order->createdDate = $createdDate->format('F \  j, Y');
        $user_address = Contact::where('user_id', $user_id)->first();
        $orderitems = ApiOrderItem::where('order_id', $id)->with('product')->get();
        $data = [
            'user_order'  =>  $user_order,
            'order_items' =>  $orderitems,
            'user_address' => $user_address
        ];
        return $data;
    }

    public function address_user_my_account(Request $request)
    {
        // $user_id = auth()->id();
        // $secondary_id = $request->secondary_id;
        // $contact_id = $request->contact_id;

        // if (!empty($request->contact_id)) {
        //     $contact = Contact::where('user_id', $user_id)
        //     ->where('contact_id', $contact_id)
        //     ->first();
        //     $contact_id = $contact->contact_id;
        // }

        // if (!empty($secondary_id)) {
        //     $contact = Contact::where('user_id', $user_id)
        //     ->where('secondary_id', $secondary_id)
        //     ->first();
        //     $contact_id = $contact->secondary_id;
        // }
        // $request->validate([
        //     'first_name' => 'required',
        //     'last_name' => 'required',
        //     'company_name' => 'required',
        //     'address' => 'required',
        //     'state' => 'required|alpha',
        //     'phone' => 'required',
        //     'zip' => 'required'
        // ]);

        // $user_id = auth()->id();
        // $contact = Contact::where('user_id', $user_id)
        //     ->where('contact_id', $contact_id)
        //     ->orWhere('secondary_id' , $contact_id)
        //     ->first();
        // if ($contact) {
        //     $contact->firstName = $request->first_name;
        //     $contact->lastName = $request->last_name;
        //     $contact->address1 = $request->address;
        //     $contact->address2 = $request->address2;
        //     $contact->company = $request->company_name;
        //     $contact->state = $request->state;
        //     $contact->phone = $request->phone;
        //     $contact->city = $request->town_city;
        //     $contact->postCode = $request->zip;
        //     $contact->tax_class = strtolower($request->state) == strtolower('California') ? '8.75%' : 'Out of State';
        //     $contact->save();
        //     return response()->json(['success' => true, 'created' => true, 'msg' => 'Address updated Successfully']);
        // }else {
        //     return response()->json(['success' => false, 'created' => false, 'msg' => 'You Cannot update your primary contact']);
        // }

        $subdomain = env('ZENDESK_SUBDOMAIN'); 
        $username = env('ZENDESK_USERNAME'); 
        $token =  env('ZENDESK_TOKEN'); 
        $auth = [
            'token' => $token, 
        ];
        
        $client = new ZendeskClient($subdomain);
        $client->setAuth('basic', ['username' => $username, 'token' => $token]);

        $subject = $request->type;
        $requesterName = $request->first_name . ' ' . $request->last_name;
        $requesterEmail = $request->email;
        
        $company_name = !empty($request->company_name) ? $request->company_name : '';
        $address1 = $request->address;
        $address2 = $request->address2;
        $city = $request->town_city;
        $state = $request->state;
        $zip = $request->zip;

        $user_message = $requesterName . ' ' . 'requested to change his/her profile information.';
        $description = $user_message  . "\n" .  "Request Type : " . $subject . "\n" . "Company : " . $company_name . "\n" . "Address 1: " . $address1 . "\n" . "Address 2: " . $address2 . "\n" . "City: " . $city . "\n" . "State: " . $state . "\n" . "Zip: " . $zip . "\n";
        
        $ticketData = [
            'subject' => $subject,
            'description' => $description,
            'requester' => [
                'email' => $requesterEmail,
                'name' => $requesterName,
            ],
        ];

        $response = $client->tickets()->create($ticketData);
        return response()->json(
            [
                'success' => true, 
                'data' => $response ,
                'msg' => 'Your change profile request has been received. A ticket has been opened and admin will update the information within 24-48 hours'
            ]
        );
    }

    public function adminUsers(Request $request)
    {
        $data = User::role(['Admin'])->get();
        $count = $data->count();
        return view('admin/users/admin-users', compact('data', 'count'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function switch_user($id, $contactId)
    {
        Session::forget('contact_id');
        Session::forget('company');
        Session::forget('companies');
        Session::forget('cart');
        $switch_user = Auth::loginUsingId($id);
        $auth_user_email = $switch_user->email;
        session()->put('logged_in_as_another_user', $auth_user_email);

        Auth::loginUsingId($id);
        $active_qoutes = Cart::where('user_id', $id)->where('is_active', 1)->get();
        foreach ($active_qoutes as $active_qoute) {
            $cart[$active_qoute->qoute_id] = [
                "product_id" => $active_qoute->product_id,
                "name" => $active_qoute->name,
                "quantity" => $active_qoute->quantity,
                "price" => $active_qoute->price,
                "code" => $active_qoute->code,
                "image" => $active_qoute->image,
                'option_id' => $active_qoute->option_id,
                "slug" => $active_qoute->slug,
            ];
            Session::put('cart', $cart);
        }
        $contact_id = auth()->user()->id;
        $contact = Contact::where('user_id', $id)
            ->where('contact_id', $contactId)
            ->orWhere('secondary_id', $contactId)
            ->where('status', '!=', 0)
            ->first();
        $companies = Contact::where('user_id', auth()->user()->id)->get();

        if (!empty($contact)) {
            if ($contact->contact_id == null) {
                $active_contact_id = $contact->secondary_id;
            } else {
                $active_contact_id = $contact->contact_id;
            }
            $active_company = $contact->company;

            Session::put([
                'contact_id' => $active_contact_id,
                'company' => $active_company
            ]);
            Session::put('companies', $companies);

            return redirect('/');
        } else {
            $contact = Contact::where('secondary_id', $contact_id)->where('status', '!=', 0)->first();
            if (!empty($contact)) {
                $active_contact_id = $contact->secondary_id;
                $active_company = $contact->company;
                Session::put([
                    'contact_id' => $active_contact_id,
                    'company' => $active_company
                ]);
                Session::put('companies', $companies);
                return redirect('/');
            }
        }
        return redirect('/');
    }

    public function switch_user_back()
    {
        $admin = User::role('Admin')->first();
        Auth::loginUsingId($admin->id);
        session()->flash('logged_in_as_another_user', '');
        return redirect('admin/dashboard');
    }
    public function switch_admin()
    {
        Session::forget('contact_id');
        Session::forget('company');
        Session::forget('companies');
        Session::forget('cart');
        $admin = User::role('Admin')->where('id' , auth()->user()->id)->first();
        Auth::loginUsingId($admin->id);
        $companies = Contact::where('user_id', $admin->id)->get();
        if ($companies->count() >= 1) {
            foreach ($companies as $company) {
                if ($company->contact_id == null) {
                    UserHelper::switch_company($company->secondary_id);
                } else {
                    UserHelper::switch_company($company->contact_id);
                }
            }
            // if ($companies[0]->contact_id == null) {
            //     UserHelper::switch_company($companies[0]->secondary_id);
            // } else {
            //     UserHelper::switch_company($companies[0]->contact_id);
            // }
        }
        Session::put('companies', $companies);
        return redirect('/');
    }

    public function create_secondary_user(Request $request)
    {
        $url = '';
        $user_id = auth()->user()->id;
        $contact = Contact::where('user_id', $user_id)->first();
        $contactId = $contact->contact_id;

        $request->validate([
            'email' => 'required|email|unique:contacts,email',
            'firstName' => 'required',
            'lastName' => 'required',
        ]);

        $secondary_contact_data = [
            'parent_id' => $contact->contact_id,
            'company' => $contact->company,
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'jobTitle' => $request->jobTitle,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        Contact::create($secondary_contact_data);

        unset($secondary_contact_data['parent_id']);
        $current_date_time = Carbon::now()->toDateTimeString();
        $secret = "QCOM" . $current_date_time;
        $sig = hash_hmac('sha256', $request->email, $secret);
        $url = URL::to("/");
        if (!empty($request->email)) {
            $url = $url . '/customer/invitation/' . $sig . '?is_secondary=1';
        }

        $contact = [
            [
                'id' => $contactId,
                'type' => 'Customer',
                'secondaryContacts' => [
                    $secondary_contact_data
                ]
            ]
        ];

        $data = [
            'email' => $request->email,
            'subject' => 'Customer Registration Invitation',
            'from' => env('MAIL_FROM_ADDRESS'),
            'content' => 'Customer Registration Invitation',
            'url' => $url
        ];
        MailHelper::sendMailNotification('emails.invitaion-emails', $data);
        SyncContacts::dispatch('update_contact', $contact)->onQueue(env('QUEUE_NAME'));
        return response()->json([
            'state' => 200,
            'secondary_contact' => $secondary_contact_data,
        ]);
    }

    public function delete_secondary_user(Request $request)
    {
        $id = $request->id;
        $secondary_contact = Contact::find($id);
        $secondary_contact->delete();
    }

    public function switch_company(Request $request)
    {
        $user_id =  auth()->user()->id;
        $contact_id = $request->companyId;
        $contact = Contact::where('contact_id', $contact_id)->first();
        $company_type = null;
        $active_contact_id = null;
        $active_company = null;
        $get_company_address = null;
        $cart = [];
        if (!empty($contact)) {
            $active_contact_id = $contact->contact_id;
            $active_company = $contact->company;
            $company_type = 'primary';
            
        } else {
            $contact = Contact::where('secondary_id', $contact_id)->first();
            $active_contact_id = $contact->secondary_id;
            $active_company = $contact->company;
            $company_type = 'secondary';
            
        }
        Session::put([
            'contact_id' => $active_contact_id,
            'company' => $active_company
        ]);
        $getSelectedContact = Contact::where('company' , $active_company)->where('user_id' , $user_id)->first();
        $cartItems = Cart::where('user_id' , $getSelectedContact->user_id)->get();
        $getPriceColumn = UserHelper::getUserPriceColumn(false , $getSelectedContact->user_id);

        $cart_data = [];
        if (count($cartItems) > 0) {
            Session::forget('cart');
            foreach ($cartItems as $cartItem){
                $productPricing = Pricingnew::where('option_id' , $cartItem['option_id'])->first();
                $productPrice = $productPricing->$getPriceColumn;
                $cart = Cart::where('user_id' , $user_id)->where('product_id' , $cartItem['product_id'])->first();
                if (!empty($cart)) {
                    $cart->price = $productPrice;
                    $cart->save();
                }
                
                $cart_data[$cart['qoute_id']] = [
                    "product_id" => $cartItem['product_id'],
                    "name" => $cartItem['name'],
                    "quantity" => $cartItem['quantity'],
                    "price" => $cart['price'],
                    "code" => $cartItem['code'],
                    "image" => $cartItem['image'],
                    'option_id' => $cartItem['option_id'],
                    "slug" => $cartItem['slug'],
                ];
            }
        }

        Session::put('cart', $cart_data);
        if ($company_type == 'primary') {
            $get_company_address = Contact::where('contact_id', $active_contact_id)->first();
        } 

        if ($company_type == 'secondary') {
            $secondary_contact_user = Contact::where('secondary_id', $active_contact_id)->first();
            $get_company_address = Contact::where('contact_id', $secondary_contact_user->parent_id)->where('is_parent' , 1)->first();

        }
        return response()->json([
            'status' => '204',
            'message' => 'Company Switch Successfully !',
            'success' => true,
            'update_address' => $get_company_address
        ]);
    }

    public function switch_company_select(Request $request)
    {
        $contact_id = $request->contact_id;
        $rawContactID = explode('-', $contact_id);

        if ($rawContactID[1] == 'P') {
            $contact = Contact::where('contact_id', $rawContactID[0])->first();
            $active_contact_id = $contact->contact_id;
        }
        if ($rawContactID[1] == 'S') {
            $contact = Contact::where('secondary_id', $rawContactID[0])->first();
            $active_contact_id = $contact->secondary_id;
        }
        $active_company = $contact->company;
        Session::put([
            'contact_id' => $active_contact_id,
            'company' => $active_company
        ]);
        return response()->json([
            'status' => '204',
            'message' => 'Company Switch Select Successfully !'
        ]);
    }

    public function send_password_fornt_end($id)
    {

        $user = User::where('id', $id)->first();
        $plain_password = Str::random(10) . date('YmdHis');
        $encrypted_password = bcrypt($plain_password);
        $hash = Str::random(10000) . $user->first_name . date('YmdHis');
        $hash = md5($hash);

        $user->password = $encrypted_password;
        $user->hash = $hash;
        $user->save();

        $data['email'] = $user->email;
        $data['content'] = 'Password Reset';
        $data['subject'] = 'Password Reset';
        $data['from'] = env('MAIL_FROM_ADDRESS');
        $data['plain'] = $plain_password;
        MailHelper::sendMailNotification('emails.reset-password', $data);

        return redirect()->back()->with('success', 'Password Send Successfully !');
    }
    public function send_password($id)
    {

        $user = User::where('id', $id)->first();
        $plain_password = Str::random(10) . date('YmdHis');
        $encrypted_password = bcrypt($plain_password);
        $hash = Str::random(10000) . $user->first_name . date('YmdHis');
        $hash = md5($hash);

        $user->password = $encrypted_password;
        $user->hash = $hash;
        $user->save();
        $base_url = url('/');

        $url = $base_url . '/index?hash=' . $hash;
        $data['email'] = $user->email;
        $data['url'] = $url;


        $data['content'] = 'Password Reset';
        $data['subject'] = 'Password Reset';
        $data['from'] = env('MAIL_FROM_ADDRESS');
        $data['plain'] = $plain_password;
        MailHelper::sendMailNotification('emails.reset-password', $data);

        return redirect()->back()->with('success', 'Password Send Successfully !');
    }


    public function reset_password(Request $request)
    {
        User::where('email', $request->email)
            ->update([
                'password' => bcrypt($request->password),
                'is_updated' => 1
            ]);
        $user_id = auth()->user()->id;
        $companies = Contact::where('user_id', $user_id)->get();
        Session::put('companies', $companies);
        return redirect('my-account');
    }

    public function user_order_approve(Request $request)
    {
        $order_id = $request->order_id;
        $currentOrder = ApiOrder::where('id', $order_id)->with('contact')->first();

        $memberId = $currentOrder->memberId;
        $order_items = ApiOrderItem::with('product.options')->where('order_id', $order_id)->get();
        $dateCreated = Carbon::now();
        $lineItems = [];
        foreach ($order_items as $order_item) {
            $lineItems[] = [
                "id" => $order_item->product->product_id,
                "createdDate" => '2022-07-31T23:43:38Z',
                "transaction" => '12',
                "parentId" => 1,
                "productId" => $order_item->product->product_id,
                "productOptionId" => null,
                "integrationRef" => "sample string 15",
                "sort" => 16,
                "code" => $order_item->product->code,
                "name" => $order_item->product->name,
                "option1" => $order_item->product->option1,
                "option2" => $order_item->product->option2,
                "option3" => $order_item->product->option,
                "qty" => $order_item->quantity,
                "styleCode" => "sample string 1",
                "barcode" => "sample string 2",
                "sizeCodes" => "sample string 4",
                "lineComments" => null,
                "unitCost" => $order_item->price,
                "unitPrice" => $order_item->price,
                "discount" => null,
                "qtyShipped" => 7,
                "holdingQty" => 8,
                "accountCode" => null,
                "stockControl" => "Undefined",
                "stockMovements" => [
                    [
                        "batch" => "sample string 1",
                        "quantity" => 2.0,
                        "serial" => "sample string 3"
                    ],
                    [
                        "batch" => "sample string 1",
                        "quantity" => 2.0,
                        "serial" => "sample string 3"
                    ],
                ],
                "sizes" => [
                    [
                        "name" => "sample string 1",
                        "code" => "sample string 2",
                        "barcode" => "sample string 3",
                        "qty" => 4.0
                    ]
                ],
            ];
        }
        $order = [];
        $order = [
            [
                $currentOrder,
                "createdDate" => $dateCreated,
                "modifiedDate" => "",
                "createdBy" => 79914,
                "processedBy" => 79914,
                "isApproved" => true,
                "reference" => $currentOrder->reference,
                "branchId" => 3,
                "branchEmail" => "wqszeeshan@gmail.com",
                "memberId" => $memberId,
                "projectName" => "",
                "trackingCode" => "",
                "internalComments" => "sample string 51",
                "productTotal" => 100,
                "freightTotal" => null,
                "freightDescription" => null,
                "surcharge" => null,
                "surchargeDescription" => null,
                "discountTotal" => null,
                "discountDescription" => null,
                "total" => 100,
                "currencyCode" => "USD",
                "currencyRate" => 59.0,
                "currencySymbol" => "$",
                "taxStatus" => "Excl",
                "taxRate" => 8.75,
                "source" => "sample string 62",
                "accountingAttributes" =>
                [
                    "importDate" => "2022-07-13T15:21:16.1946848+12:00",
                    "accountingImportStatus" => "NotImported"
                ],
                "memberEmail" => "wqszeeshan@gmail.com",
                "memberCostCenter" => "sample string 6",
                "memberAlternativeTaxRate" => "",
                "costCenter" => null,
                "alternativeTaxRate" => "8.75%",
                // "estimatedDeliveryDate" => "2022-07-13T15:21:16.1946848+12:00",
                "estimatedDeliveryDate" => $currentOrder->date,
                "salesPersonId" => 10,
                "salesPersonEmail" => "wqszeeshan@gmail.com",
                "paymentTerms" => $currentOrder->paymentTerms,
                "customerOrderNo" => $currentOrder->po_number,
                "voucherCode" => "sample string 14",
                "deliveryInstructions" =>  $currentOrder->memo,
                "status" => "VOID",
                "stage" => "",
                "invoiceDate" => null,
                "invoiceNumber" => 4232,
                "dispatchedDate" => null,
                "logisticsCarrier" => $currentOrder->logisticsCarrier,
                "logisticsStatus" => 1,
                "distributionBranchId" => 0,
                "lineItems" => $lineItems

            ],
        ];
        SalesOrders::dispatch('create_order', $order)
            ->onQueue(env('QUEUE_NAME'));
        return response()->json(['success' => true]);
    }

    public function verify_order(Request $request)
    {
        $order_id = $request->order_id;
        $order = ApiOrder::where('id', $order_id)->first();
        return $data = $order;
    }

    public function send_order_approval_email(Request $request)
    {
        $order = ApiOrder::where('id', $request->order_id)->with('contact')->with('apiOrderItem')->first();
        $data['email'] = $order->contact->email;
        $data['order'] =  $order;
        $data['content'] = 'Order Approved';
        $data['subject'] = 'Order Approved';
        $data['from'] = env('MAIL_FROM_ADDRESS');
        MailHelper::sendMailNotification('emails.order-approver-email', $data);
    }

    public function choose_company(Request $request)
    {
        if ($request->ajax()) {
            $companies = Contact::where('user_id', auth()->user()->id)->get();
            return response()->json([
                'message' => 'success',
                'companies' => $companies
            ]);
        }
    }
    public function index_email_view(Request $request)
    {

        $user = User::where('hash', $request->hash)->first();
        Auth::login($user);

        return view('reset-password', compact('user'));
    }

    public function account_profile_update(Request $request)
    {
        $Validated = $request->validate([
            'email' => 'required|email|unique:users,email,' . $request->id,
            'firstName' => 'required',
            'lastName' => 'required',
            'phone' => 'required',
            'password' => 'required',
            'password_confirmation' => 'required_with:password|same:password'
        ]);
        $user_id = $request->id;
        $user_profile = User::find($user_id);
        $user_profile->email = $request->input('email');
        $user_profile->password = Hash::make($request->input('password'));
        $user_profile->save();
        $user_profile_contact = Contact::where('user_id', $user_id)->first();
        $user_profile_contact->firstName = $request->input('firstName');
        $user_profile_contact->lastName = $request->input('lastName');
        $user_profile_contact->phone = $request->input('phone');
        $user_profile_contact->save();

        return redirect()->back()->with('success', 'Profile Updated Successfully !');
    }

    // crreate wholesale account

    public function create_wholesale_account (Request $request) {
        if (auth()->user()) {
            $email = auth()->user()->email;
            $user_id = auth()->user()->id;
            $contact = Contact::where('user_id' , $user_id)->first();
            $wholesale_application = WholesaleApplicationInformation::where('email' , $email)->first();
            if (!empty($wholesale_application)) {
                $id = $wholesale_application->id;
                return redirect()->route('edit_wholesale_account' , $id);
                
            } else {
                return view('create_wholesale_account' , compact('contact'));
            }
        } else {
            $data['setting'] = AdminSetting::where('option_name' , 'enable_sign_up')->first();
            $data['states'] = UsState::get(["state_name", "id"]);
            // return view('create_wholesale_account');
            return view('sign-up-wholesale-account' , $data);
        }
    }

    // edit wholesale account
    public function edit_wholesale_account ($id) {
        $wholesale_application = WholesaleApplicationInformation::where('id' , $id)->first();
        $wholesale_application_address_billing = WholesaleApplicationAddress::where('wholesale_application_id' , $id)->where('type' , 'Billing Address')->first();
        $wholesale_application_address_delivery = WholesaleApplicationAddress::where('wholesale_application_id' , $id)->where('type' , 'Delievery Address')->first();
        $wholesale_regulation = WholesaleApplicationRegulationDetail::where('wholesale_application_id' , $id)->first();
        $wholesale_authorization = WholesaleApplicationAuthorizationDetail::where('wholesale_application_id' , $id)->first();
        $wholesale_application_card = WholesaleApplicationCard::where('wholesale_application_id' , $id)->first();
        $wholesale_application_images = WholesaleApplicationImage::where('wholesale_application_id' , $id)->get();
        return view('edit_wholesale_account', compact('id','wholesale_application' ,'wholesale_application_images', 'wholesale_application_address_billing' , 'wholesale_application_address_delivery' , 'wholesale_regulation' , 'wholesale_authorization' , 'wholesale_application_card'));
    }
    // edit wholesale account
    public function view_wholesale_account ($id) {
        $wholesale_application = WholesaleApplicationInformation::where('id' , $id)->first();
        $wholesale_application_address_billing = WholesaleApplicationAddress::where('wholesale_application_id' , $id)->where('type' , 'Billing Address')->first();
        $wholesale_application_address_delivery = WholesaleApplicationAddress::where('wholesale_application_id' , $id)->where('type' , 'Delievery Address')->first();
        $wholesale_regulation = WholesaleApplicationRegulationDetail::where('wholesale_application_id' , $id)->first();
        $wholesale_authorization = WholesaleApplicationAuthorizationDetail::where('wholesale_application_id' , $id)->first();
        $wholesale_application_card = WholesaleApplicationCard::where('wholesale_application_id' , $id)->first();
        $wholesale_application_images = WholesaleApplicationImage::where('wholesale_application_id' , $id)->get();
        return view('view_wholesale_account', compact('id','wholesale_application' ,'wholesale_application_images', 'wholesale_application_address_billing' , 'wholesale_application_address_delivery' , 'wholesale_regulation' , 'wholesale_authorization' , 'wholesale_application_card'));
    }

     // edit wholesale account
    public function wholesaleuser_thankyou($id) {
        $wholesale_application = WholesaleApplicationInformation::where('id' , $id)->first();
        return view('wholesale_thankyou', compact('wholesale_application' , 'id'));
    }

    // store wholesale account

    public function store_wholesale_account (Request $request) {
        DB::beginTransaction();
        try {

            $images_array = [];
             //save data step 1 
            $permit_image_name = null;
            if ($request->hasFile('permit_image')) {
                $images = $request->file('permit_image');
                if (!empty($images)) {
                    foreach ($images as $image) {
                        $permit_image_name = time() . random_int(100000, 999999) . '.' . $image->getClientOriginalExtension();
                        $destinationPath = public_path('wholesale/images');
                        File::makeDirectory($destinationPath, $mode = 0777, true, true);
                        $image->move($destinationPath, $permit_image_name);
                        array_push($images_array, $permit_image_name);
                        // $images_array[] = $permit_image_name;
    
                    }
                }
            }
            
            $wholesale_application_id = $request->wholesale_application_id;

            if (!empty($wholesale_application_id)) {
                $update_wholesale_application = WholesaleApplicationInformation::where('id' , $wholesale_application_id)->first();
                // if (empty($permit_image_name)) {
                //     $permit_image = $update_wholesale_application->permit_image;
                // } else {
                //     $permit_image = $permit_image_name;
                // } 
                $update_wholesale_application->update([
                    'company' => $request->company_name,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'mobile' => $request->mobile,
                    'parent_company' => $request->parent_company,
                    'payable_name' => $request->account_payable_name,
                    'payable_email' => $request->account_payable_email,
                    'payable_phone' => $request->account_payable_phone,
                    'status' => 0
                    // 'permit_image' => $permit_image,
                ]);

                if(!empty($images_array)) {
                    if (count($images_array) > 0 ) {
                        $delete_previous_images = WholesaleApplicationImage::where('wholesale_application_id' , $wholesale_application_id)->get();
                        if (count($delete_previous_images) > 0) {
                            foreach ($delete_previous_images as $delete_previous_image) {
                                $delete_previous_image->delete();
                            }
                        }
                        foreach ($images_array as $image) {
                            WholesaleApplicationImage::create([
                                'wholesale_application_id' => $wholesale_application_id,
                                'permit_image' => $image,
                            ]); 
                        }
                    }
                }

                

                $update_wholesale_application_address_billing = WholesaleApplicationAddress::where('wholesale_application_id' , $wholesale_application_id)->where('type' , 'Billing Address')->first();
                if (!empty($update_wholesale_application_address_billing)) {
                    $update_wholesale_application_address_billing->update([
                        'wholesale_application_id' => $wholesale_application_id,
                        'type' => 'Billing Address',
                        'first_name' => $request->first_name_billing,
                        'last_name' => $request->last_name_billing,
                        'company_name' => $request->company_name_billing,
                        'street_address' => $request->street_address_billing,
                        'address2' => $request->address2_billing,
                        'city' => $request->city_billing,
                        'state' => $request->state_billing,
                        'postal_code' => $request->postal_code_billing,
                        'phone' => $request->phone_billing,
                    ]);
                } else {
                    WholesaleApplicationAddress::create([
                        'wholesale_application_id' => $wholesale_application_id,
                        'type' => 'Billing Address',
                        'first_name' => $request->first_name_billing,
                        'last_name' => $request->last_name_billing,
                        'company_name' => $request->company_name_billing,
                        'street_address' => $request->street_address_billing,
                        'address2' => $request->address2_billing,
                        'city' => $request->city_billing,
                        'state' => $request->state_billing,
                        'postal_code' => $request->postal_code_billing,
                        'phone' => $request->phone_billing,
                    ]);
                }

                $update_wholesale_application_address_delivery = WholesaleApplicationAddress::where('wholesale_application_id' , $wholesale_application_id)->where('type' , 'Delievery Address')->first();
                if (!empty($update_wholesale_application_address_delivery)) {
                    $update_wholesale_application_address_delivery->update([
                        'wholesale_application_id' => $wholesale_application_id,
                        'type' => 'Delievery Address',
                        'first_name' => $request->first_name_delivery,
                        'last_name' => $request->last_name_delivery,
                        'company_name' => $request->company_name_delivery,
                        'street_address' => $request->street_address_delivery,
                        'address2' => $request->address2_delivery,
                        'city' => $request->city_delivery,
                        'state' => $request->state_delivery,
                        'postal_code' => $request->postal_code_delivery,
                        'phone' => $request->phone_delivery,
                    ]);
                } else {
                    WholesaleApplicationAddress::create([
                        'wholesale_application_id' => $wholesale_application_id,
                        'type' => 'Delievery Address',
                        'first_name' => $request->first_name_delivery,
                        'last_name' => $request->last_name_delivery,
                        'company_name' => $request->company_name_delivery,
                        'street_address' => $request->street_address_delivery,
                        'address2' => $request->address2_delivery,
                        'city' => $request->city_delivery,
                        'state' => $request->state_delivery,
                        'postal_code' => $request->postal_code_delivery,
                        'phone' => $request->phone_delivery,
                    ]);
                }
                

                // step 2 update

                $update_wholesale_regulation = WholesaleApplicationRegulationDetail::where('wholesale_application_id' , $wholesale_application_id)->first();
                if(!empty($update_wholesale_regulation)) {
                    $update_wholesale_regulation->update([
                        'wholesale_application_id' => $wholesale_application_id,
                        'seller_name' => $request->seller_name,
                        'seller_address' => $request->seller_address,
                        'purchaser_signature' => $request->signature,
                        'certificate_eligibility_1' => $request->under_signed_checkbox,
                        'certificate_eligibility_2' => $request->under_property_checkbox,
                        'equipment_type' => $request->type_of_farm,
                        'purchaser_company_name' => $request->company_name_seller,
                        'title' => $request->title,
                        'purchaser_address' => $request->address,
                        'purchaser_phone' => $request->phone_number,
                        'regulation_permit_number' => $request->permit_number,
                        'purchase_date' => $request->date,
                    ]);
                } else {
                    WholesaleApplicationRegulationDetail::create([
                        'wholesale_application_id' => $wholesale_application_id,
                        'seller_name' => $request->seller_name,
                        'seller_address' => $request->seller_address,
                        'purchaser_signature' => $request->signature,
                        'certificate_eligibility_1' => $request->under_signed_checkbox,
                        'certificate_eligibility_2' => $request->under_property_checkbox,
                        'equipment_type' => $request->type_of_farm,
                        'purchaser_company_name' => $request->company_name_seller,
                        'title' => $request->title,
                        'purchaser_address' => $request->address,
                        'purchaser_phone' => $request->phone_number,
                        'regulation_permit_number' => $request->permit_number,
                        'purchase_date' => $request->date,
                    ]);
                }
                

                // step 3 update

                $update_wholesale_authorization = WholesaleApplicationAuthorizationDetail::where('wholesale_application_id' , $wholesale_application_id)->first();
                if (!empty($update_wholesale_authorization)) {
                    $update_wholesale_authorization->update([
                        'wholesale_application_id' => $wholesale_application_id,
                        'authorize_name' => $request->authorization_name,
                        'financial_institute_name' => $request->financial_institution_name,
                        'financial_institute_address' => $request->financial_institution_address,
                        'financial_institute_signature' => $request->financial_institution_signature,
                        'set_amount' => $request->set_amount,
                        'maximum_amount' => $request->maximum_amount,
                        'financial_institute_routine_number' => $request->institute_routine_number,
                        'financial_institute_account_number' => $request->saving_account_number,
                        'financial_institute_permit_number' => $request->autorization_permit_number,
                        'financial_institute_phone_number' => $request->autorization_phone_number,
                    ]);
                } else {
                    WholesaleApplicationAuthorizationDetail::create([
                        'wholesale_application_id' => $wholesale_application_id,
                        'authorize_name' => $request->authorization_name,
                        'financial_institute_name' => $request->financial_institution_name,
                        'financial_institute_address' => $request->financial_institution_address,
                        'financial_institute_signature' => $request->financial_institution_signature,
                        'set_amount' => $request->set_amount,
                        'maximum_amount' => $request->maximum_amount,
                        'financial_institute_routine_number' => $request->institute_routine_number,
                        'financial_institute_account_number' => $request->saving_account_number,
                        'financial_institute_permit_number' => $request->autorization_permit_number,
                        'financial_institute_phone_number' => $request->autorization_phone_number,
                    ]);
                }

                // step 4 update

                $update_wholesale_application_card = WholesaleApplicationCard::where('wholesale_application_id' , $wholesale_application_id)->first();
                if(!empty($update_wholesale_application_card)) {
                    $update_wholesale_application_card->update([ 
                        'wholesale_application_id' => $wholesale_application_id,
                        'card_type' => $request->card_type,
                        'cardholder_name' => $request->cardholder_name,
                        'card_number' => $request->card_number,
                        'cardholder_zip_code' => $request->card_holder_zip_code,
                        'authorize_card_name' => $request->undertaking_name,
                        'authorize_card_text' => $request->authorize_text,
                        'expiration_date' => $request->expiration_date,
                        'customer_signature' => $request->customer_signature,
                        'date' => $request->date_wholesale,
                    ]);
                } else {
                    WholesaleApplicationCard::create([
                        'wholesale_application_id' => $wholesale_application_id,
                        'card_type' => $request->card_type,
                        'cardholder_name' => $request->cardholder_name,
                        'card_number' => $request->card_number,
                        'cardholder_zip_code' => $request->card_holder_zip_code,
                        'authorize_card_name' => $request->undertaking_name,
                        'authorize_card_text' => $request->authorize_text,
                        'expiration_date' => $request->expiration_date,
                        'customer_signature' => $request->customer_signature,
                        'date' => $request->date_wholesale,
                    ]);
                }
                

                DB::commit();
                return response()->json([
                    'status' => true,
                    'message' => 'Wholesale Application Updated Successfully !',
                    'wholesale_application_id' => $wholesale_application_id
                ],200);
    
            }

            else {
                $wholesale_application = WholesaleApplicationInformation::create([
                    'company' => $request->company_name,
                    'slug' => Str::random(20),
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'mobile' => $request->mobile,
                    'parent_company' => $request->parent_company,
                    'payable_name' => $request->account_payable_name,
                    'payable_email' => $request->account_payable_email,
                    'payable_phone' => $request->account_payable_phone,
                    'status' => 0
                    // 'permit_image' => $permit_image,
                ]);
    
                $wholesale_application->save();

                if (!empty($images_array) || count($images_array) > 0) {
                    foreach ($images_array as $image) {
                        $wholesale_application_images = WholesaleApplicationImage::create([
                            'wholesale_application_id' => $wholesale_application->id,
                            'permit_image' => $image,
                        ]);
                    }
                }
    
                if ($wholesale_application == true)  {
                    $wholesale_application_address_billing = WholesaleApplicationAddress::create([
                        'wholesale_application_id' => $wholesale_application->id,
                        'type' => 'Billing Address',
                        'first_name' => $request->first_name_billing,
                        'last_name' => $request->last_name_billing,
                        'company_name' => $request->company_name_billing,
                        'street_address' => $request->street_address_billing,
                        'address2' => $request->address2_billing,
                        'city' => $request->city_billing,
                        'state' => $request->state_billing,
                        'postal_code' => $request->postal_code_billing,
                        'phone' => $request->phone_billing,
                    ]);
    
                    $wholesale_application_address_billing->save();
    
                    $wholesale_application_address_delivery = WholesaleApplicationAddress::create([
                        'wholesale_application_id' => $wholesale_application->id,
                        'type' => 'Delievery Address',
                        'first_name' => $request->first_name_delivery,
                        'last_name' => $request->last_name_delivery,
                        'company_name' => $request->company_name_delivery,
                        'street_address' => $request->street_address_delivery,
                        'address2' => $request->address2_delivery,
                        'city' => $request->city_delivery,
                        'state' => $request->state_delivery,
                        'postal_code' => $request->postal_code_delivery,
                        'phone' => $request->phone_delivery,
                    ]);
    
                    $wholesale_application_address_delivery->save();
                    // step 2 save 
                    $wholesale_regulation = WholesaleApplicationRegulationDetail::create([
                        'wholesale_application_id' => $wholesale_application->id,
                        'seller_name' => $request->seller_name,
                        'seller_address' => $request->seller_address,
                        'purchaser_signature' => $request->signature,
                        'certificate_eligibility_1' => $request->under_signed_checkbox,
                        'certificate_eligibility_2' => $request->under_property_checkbox,
                        'equipment_type' => $request->type_of_farm,
                        'purchaser_company_name' => $request->company_name_seller,
                        'title' => $request->title,
                        'purchaser_address' => $request->address,
                        'purchaser_phone' => $request->phone_number,
                        'regulation_permit_number' => $request->permit_number,
                        'purchase_date' => $request->date,
                    ]);
    
                    $wholesale_regulation->save();
                    
                    
                    //save step 3 
                    $wholesale_authorization = WholesaleApplicationAuthorizationDetail::create([
                        'wholesale_application_id' => $wholesale_application->id,
                        'authorize_name' => $request->authorization_name,
                        'financial_institute_name' => $request->financial_institution_name,
                        'financial_institute_address' => $request->financial_institution_address,
                        'financial_institute_signature' => $request->financial_institution_signature,
                        'set_amount' => $request->set_amount,
                        'maximum_amount' => $request->maximum_amount,
                        'financial_institute_routine_number' => $request->institute_routine_number,
                        'financial_institute_account_number' => $request->saving_account_number,
                        'financial_institute_permit_number' => $request->autorization_permit_number,
                        'financial_institute_phone_number' => $request->autorization_phone_number,
                    ]);
    
                    $wholesale_authorization->save();
    
                    //save step 4
    
                    $wholesale_application_card = WholesaleApplicationCard::create([
                        'wholesale_application_id' => $wholesale_application->id,
                        'card_type' => $request->card_type,
                        'cardholder_name' => $request->cardholder_name,
                        'card_number' => $request->card_number,
                        'cardholder_zip_code' => $request->card_holder_zip_code,
                        'authorize_card_name' => $request->undertaking_name,
                        'authorize_card_text' => $request->authorize_text,
                        'expiration_date' => $request->expiration_date,
                        'customer_signature' => $request->customer_signature,
                        'date' => $request->date_wholesale,
                    ]);
    
    
                    $wholesale_application_card->save();
                    DB::commit();
                    return response()->json([
                        'status' => true,
                        'message' => 'Wholesale Application Submitted Successfully !',
                        'wholesale_application_id' => $wholesale_application->id
                    ],200);
    
                }
            }

        } catch (\Exception $e) {
            
            DB::rollback();

            return response()->json([
                'status' => false,
                'message' => 'Something Went Wrong !',
                'is_update' => false,
                'error' => $e->getMessage()
            ],500);
        }

    }




    public function update_wholesale_account (Request $request) {
        $email = $request->email_address_previous;
        $id = null;
        if (!empty($request->wholesale_application_id)) {

            $id = $request->wholesale_application_id;
        }

        if (!empty($email)) {
            $wholesale_application = WholesaleApplicationInformation::where('email' , $email)->first();
            if (!empty($wholesale_application)) {
                $id = $wholesale_application->id;
            }
        }
        $wholesale_application = WholesaleApplicationInformation::where('id' , $id)->first();
        $wholesale_application_address_billing = WholesaleApplicationAddress::where('wholesale_application_id' , $id)->where('type' , 'Billing Address')->first();
        $wholesale_application_address_delivery = WholesaleApplicationAddress::where('wholesale_application_id' , $id)->where('type' , 'Delievery Address')->first();
        $wholesale_regulation = WholesaleApplicationRegulationDetail::where('wholesale_application_id' , $id)->first();
        $wholesale_authorization = WholesaleApplicationAuthorizationDetail::where('wholesale_application_id' , $id)->first();
        $wholesale_application_card = WholesaleApplicationCard::where('wholesale_application_id' , $id)->first();
        return view('create_wholesale_account' , compact('id','wholesale_application' , 'wholesale_application_address_billing' , 'wholesale_application_address_delivery' , 'wholesale_regulation' , 'wholesale_authorization' , 'wholesale_application_card'));
    }

    

   

    // save for now 

    public function save_for_now(Request $request) {
        $permit_image_name = null;
        $step = $request->step;
        $images_array = [];
        if ($request->hasFile('permit_image')) {
            $images = $request->file('permit_image');
            if (!empty($images)) {
                foreach ($images as $image) {
                    $permit_image_name = time() . random_int(100000, 999999) . '.' . $image->getClientOriginalExtension();
                    $destinationPath = public_path('wholesale/images');
                    File::makeDirectory($destinationPath, $mode = 0777, true, true);
                    $image->move($destinationPath, $permit_image_name);
                    array_push($images_array, $permit_image_name);

                }
            }
        }
        DB::beginTransaction();
        try {
            $email = $request->email;
            $check_email = WholesaleApplicationInformation::where('email' , $email)->first();
            $wholesale_application = WholesaleApplicationInformation::where('id' , $request->wholesale_application_id)->first();
            if (!empty($wholesale_application)) {
                $wholesale_application_id = $wholesale_application->id;
                $update_wholesale_application = WholesaleApplicationInformation::where('id' , $wholesale_application_id)->first();
                // if (empty($permit_image_name)) {
                //     $permit_image = $update_wholesale_application->permit_image;
                // } else {
                //     $permit_image = $permit_image_name;
                // } 
                $update_wholesale_application->update([
                    'company' => $request->company_name,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'mobile' => $request->mobile,
                    'parent_company' => $request->parent_company,
                    'payable_name' => $request->account_payable_name,
                    'payable_email' => $request->account_payable_email,
                    'payable_phone' => $request->account_payable_phone,
                    'status' => 0
                    // 'permit_image' => $permit_image,
                ]);

                if(!empty($images_array)) {
                    if (count($images_array) > 0 ) {
                        $delete_previous_images = WholesaleApplicationImage::where('wholesale_application_id' , $wholesale_application_id)->get();
                        if (count($delete_previous_images) > 0) {
                            foreach ($delete_previous_images as $delete_previous_image) {
                                $delete_previous_image->delete();
                            }
                        }
                        foreach ($images_array as $image) {
                            WholesaleApplicationImage::create([
                                'wholesale_application_id' => $wholesale_application_id,
                                'permit_image' => $image,
                            ]); 
                        }
                    }
                }

                

                $update_wholesale_application_address_billing = WholesaleApplicationAddress::where('wholesale_application_id' , $wholesale_application_id)->where('type' , 'Billing Address')->first();
                if (!empty($update_wholesale_application_address_billing)) {
                    $update_wholesale_application_address_billing->update([
                        'wholesale_application_id' => $wholesale_application_id,
                        'type' => 'Billing Address',
                        'first_name' => $request->first_name_billing,
                        'last_name' => $request->last_name_billing,
                        'company_name' => $request->company_name_billing,
                        'street_address' => $request->street_address_billing,
                        'address2' => $request->address2_billing,
                        'city' => $request->city_billing,
                        'state' => $request->state_billing,
                        'postal_code' => $request->postal_code_billing,
                        'phone' => $request->phone_billing,
                    ]);
                } else {
                    WholesaleApplicationAddress::create([
                        'wholesale_application_id' => $wholesale_application_id,
                        'type' => 'Billing Address',
                        'first_name' => $request->first_name_billing,
                        'last_name' => $request->last_name_billing,
                        'company_name' => $request->company_name_billing,
                        'street_address' => $request->street_address_billing,
                        'address2' => $request->address2_billing,
                        'city' => $request->city_billing,
                        'state' => $request->state_billing,
                        'postal_code' => $request->postal_code_billing,
                        'phone' => $request->phone_billing,
                    ]);
                }

                $update_wholesale_application_address_delivery = WholesaleApplicationAddress::where('wholesale_application_id' , $wholesale_application_id)->where('type' , 'Delievery Address')->first();
                if (!empty($update_wholesale_application_address_delivery)) {
                    $update_wholesale_application_address_delivery->update([
                        'wholesale_application_id' => $wholesale_application_id,
                        'type' => 'Delievery Address',
                        'first_name' => $request->first_name_delivery,
                        'last_name' => $request->last_name_delivery,
                        'company_name' => $request->company_name_delivery,
                        'street_address' => $request->street_address_delivery,
                        'address2' => $request->address2_delivery,
                        'city' => $request->city_delivery,
                        'state' => $request->state_delivery,
                        'postal_code' => $request->postal_code_delivery,
                        'phone' => $request->phone_delivery,
                    ]);
                } else {
                    WholesaleApplicationAddress::create([
                        'wholesale_application_id' => $wholesale_application_id,
                        'type' => 'Delievery Address',
                        'first_name' => $request->first_name_delivery,
                        'last_name' => $request->last_name_delivery,
                        'company_name' => $request->company_name_delivery,
                        'street_address' => $request->street_address_delivery,
                        'address2' => $request->address2_delivery,
                        'city' => $request->city_delivery,
                        'state' => $request->state_delivery,
                        'postal_code' => $request->postal_code_delivery,
                        'phone' => $request->phone_delivery,
                    ]);
                }
                

                // step 2 update

                $update_wholesale_regulation = WholesaleApplicationRegulationDetail::where('wholesale_application_id' , $wholesale_application_id)->first();
                if(!empty($update_wholesale_regulation)) {
                    $update_wholesale_regulation->update([
                        'wholesale_application_id' => $wholesale_application_id,
                        'seller_name' => $request->seller_name,
                        'seller_address' => $request->seller_address,
                        'purchaser_signature' => $request->signature,
                        'certificate_eligibility_1' => $request->under_signed_checkbox,
                        'certificate_eligibility_2' => $request->under_property_checkbox,
                        'equipment_type' => $request->type_of_farm,
                        'purchaser_company_name' => $request->company_name_seller,
                        'title' => $request->title,
                        'purchaser_address' => $request->address,
                        'purchaser_phone' => $request->phone_number,
                        'regulation_permit_number' => $request->permit_number,
                        'purchase_date' => $request->date,
                    ]);
                } else {
                    WholesaleApplicationRegulationDetail::create([
                        'wholesale_application_id' => $wholesale_application_id,
                        'seller_name' => $request->seller_name,
                        'seller_address' => $request->seller_address,
                        'purchaser_signature' => $request->signature,
                        'certificate_eligibility_1' => $request->under_signed_checkbox,
                        'certificate_eligibility_2' => $request->under_property_checkbox,
                        'equipment_type' => $request->type_of_farm,
                        'purchaser_company_name' => $request->company_name_seller,
                        'title' => $request->title,
                        'purchaser_address' => $request->address,
                        'purchaser_phone' => $request->phone_number,
                        'regulation_permit_number' => $request->permit_number,
                        'purchase_date' => $request->date,
                    ]);
                }
                

                // step 3 update

                $update_wholesale_authorization = WholesaleApplicationAuthorizationDetail::where('wholesale_application_id' , $wholesale_application_id)->first();
                if (!empty($update_wholesale_authorization)) {
                    $update_wholesale_authorization->update([
                        'wholesale_application_id' => $wholesale_application_id,
                        'authorize_name' => $request->authorization_name,
                        'financial_institute_name' => $request->financial_institution_name,
                        'financial_institute_address' => $request->financial_institution_address,
                        'financial_institute_signature' => $request->financial_institution_signature,
                        'set_amount' => $request->set_amount,
                        'maximum_amount' => $request->maximum_amount,
                        'financial_institute_routine_number' => $request->institute_routing_number,
                        'financial_institute_account_number' => $request->saving_account_number,
                        'financial_institute_permit_number' => $request->autorization_permit_number,
                        'financial_institute_phone_number' => $request->autorization_phone_number,
                    ]);
                } else {
                    WholesaleApplicationAuthorizationDetail::create([
                        'wholesale_application_id' => $wholesale_application_id,
                        'authorize_name' => $request->authorization_name,
                        'financial_institute_name' => $request->financial_institution_name,
                        'financial_institute_address' => $request->financial_institution_address,
                        'financial_institute_signature' => $request->financial_institution_signature,
                        'set_amount' => $request->set_amount,
                        'maximum_amount' => $request->maximum_amount,
                        'financial_institute_routine_number' => $request->institute_routine_number,
                        'financial_institute_account_number' => $request->saving_account_number,
                        'financial_institute_permit_number' => $request->autorization_permit_number,
                        'financial_institute_phone_number' => $request->autorization_phone_number,
                    ]);
                }

                // step 4 update

                $update_wholesale_application_card = WholesaleApplicationCard::where('wholesale_application_id' , $wholesale_application_id)->first();
                if(!empty($update_wholesale_application_card)) {
                    $update_wholesale_application_card->update([ 
                        'wholesale_application_id' => $wholesale_application_id,
                        'card_type' => $request->card_type,
                        'cardholder_name' => $request->cardholder_name,
                        'card_number' => $request->card_number,
                        'cardholder_zip_code' => $request->card_holder_zip_code,
                        'authorize_card_name' => $request->undertaking_name,
                        'authorize_card_text' => $request->authorize_text,
                        'expiration_date' => $request->expiration_date,
                        'customer_signature' => $request->customer_signature,
                        'date' => $request->date_wholesale,
                    ]);
                } else {
                    WholesaleApplicationCard::create([
                        'wholesale_application_id' => $wholesale_application_id,
                        'card_type' => $request->card_type,
                        'cardholder_name' => $request->cardholder_name,
                        'card_number' => $request->card_number,
                        'cardholder_zip_code' => $request->card_holder_zip_code,
                        'authorize_card_name' => $request->undertaking_name,
                        'authorize_card_text' => $request->authorize_text,
                        'expiration_date' => $request->expiration_date,
                        'customer_signature' => $request->customer_signature,
                        'date' => $request->date_wholesale,
                    ]);
                }
                

                DB::commit();
                return response()->json([
                    'status' => true,
                    'message' => 'Wholesale Application Updated Successfully !',
                    'step' => $step,
                    'type' => 'edit',
                    'wholesale_application_id' => $wholesale_application_id
                ],200);
    
            }
            else {
                $wholesale_application = WholesaleApplicationInformation::create([
                    'company' => $request->company_name,
                    'slug' => Str::random(20),
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'mobile' => $request->mobile,
                    'parent_company' => $request->parent_company,
                    'payable_name' => $request->account_payable_name,
                    'payable_email' => $request->account_payable_email,
                    'payable_phone' => $request->account_payable_phone,
                    'status' => 0
                    // 'permit_image' => $permit_image_name,
                ]);
                $wholesale_application->save();
                if (!empty($images_array) || count($images_array) > 0) {
                    foreach ($images_array as $image) {
                        $wholesale_application_images = WholesaleApplicationImage::create([
                            'wholesale_application_id' => $wholesale_application->id,
                            'permit_image' => $image,
                        ]);
                    }
                }
                $wholesale_application_address_billing = WholesaleApplicationAddress::create([
                    'wholesale_application_id' => $wholesale_application->id,
                    'type' => 'Billing Address',
                    'first_name' => $request->first_name_billing,
                    'last_name' => $request->last_name_billing,
                    'company_name' => $request->company_name_billing,
                    'street_address' => $request->street_address_billing,
                    'address2' => $request->address2_billing,
                    'city' => $request->city_billing,
                    'state' => $request->state_billing,
                    'postal_code' => $request->postal_code_billing,
                    'phone' => $request->phone_billing,
                ]);
    
                $wholesale_application_address_billing->save();
    
                $wholesale_application_address_delivery = WholesaleApplicationAddress::create([
                    'wholesale_application_id' => $wholesale_application->id,
                    'type' => 'Delievery Address',
                    'first_name' => $request->first_name_delivery,
                    'last_name' => $request->last_name_delivery,
                    'company_name' => $request->company_name_delivery,
                    'street_address' => $request->street_address_delivery,
                    'address2' => $request->address2_delivery,
                    'city' => $request->city_delivery,
                    'state' => $request->state_delivery,
                    'postal_code' => $request->postal_code_delivery,
                    'phone' => $request->phone_delivery,
                ]);
    
                $wholesale_application_address_delivery->save();

                // step 2 save 
                $wholesale_regulation = WholesaleApplicationRegulationDetail::create([
                    'wholesale_application_id' => $wholesale_application->id,
                    'seller_name' => $request->seller_name,
                    'seller_address' => $request->seller_address,
                    'purchaser_signature' => $request->signature,
                    'certificate_eligibility_1' => $request->under_signed_checkbox,
                    'certificate_eligibility_2' => $request->under_property_checkbox,
                    'equipment_type' => $request->type_of_farm,
                    'purchaser_company_name' => $request->company_name_seller,
                    'title' => $request->title,
                    'purchaser_address' => $request->address,
                    'purchaser_phone' => $request->phone_number,
                    'regulation_permit_number' => $request->permit_number,
                    'purchase_date' => $request->date,
                ]);

                $wholesale_regulation->save();
                
                
                //save step 3 
                $wholesale_authorization = WholesaleApplicationAuthorizationDetail::create([
                    'wholesale_application_id' => $wholesale_application->id,
                    'authorize_name' => $request->authorization_name,
                    'financial_institute_name' => $request->financial_institution_name,
                    'financial_institute_address' => $request->financial_institution_address,
                    'financial_institute_signature' => $request->financial_institution_signature,
                    'set_amount' => $request->set_amount,
                    'maximum_amount' => $request->maximum_amount,
                    'financial_institute_routine_number' => $request->institute_routine_number,
                    'financial_institute_account_number' => $request->saving_account_number,
                    'financial_institute_permit_number' => $request->autorization_permit_number,
                    'financial_institute_phone_number' => $request->autorization_phone_number,
                ]);

                $wholesale_authorization->save();

                // save step 4 
                $wholesale_application_card = WholesaleApplicationCard::create([
                    'wholesale_application_id' => $wholesale_application->id,
                    'card_type' => $request->card_type,
                    'cardholder_name' => $request->cardholder_name,
                    'card_number' => $request->card_number,
                    'cardholder_zip_code' => $request->card_holder_zip_code,
                    'authorize_card_name' => $request->undertaking_name,
                    'authorize_card_text' => $request->authorize_text,
                    'expiration_date' => $request->expiration_date,
                    'customer_signature' => $request->customer_signature,
                    'date' => $request->date_wholesale,
                ]);
                
                $wholesale_application_card->save();

                DB::commit();
                return response()->json([
                    'status' => true,
                    'type' => 'create',
                    'message' => 'Data saved for now !'
                ],200);
            }
            
        } catch (\Exception $e) {
            
            DB::rollback();

            return response()->json([
                'status' => false,
                'message' => 'Something Went Wrong !',
                'error' => $e->getMessage()
            ],500);
        }
    }

    public function save_email_for_now(Request $request) {
        $email = $request->email;
        $check_email = WholesaleApplicationInformation::where('email' , $email)->first();
        if (!empty($check_email)) {
            return response()->json([
                'status' => false,
                'message' => 'Email Already Exist !',
                'id' => $check_email->id
            ],200);
        } else {
            $wholesale_application = WholesaleApplicationInformation::create([
                'email' => $request->email,
                'slug' => Str::random(20),
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Data saved for now !',
                'id' => $wholesale_application->id
            ],200);
        }
        
    }

    public function validate_email(Request $request) {
        $email = $request->email;
        $check_email = WholesaleApplicationInformation::where('email' , $email)->first();
        if (!empty($check_email)) {
            return response()->json([
                'status' => true,
                'message' => 'Email Already Exist !',
                'id' => $check_email->id
            ],200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Email Not Exist !'
            ],200);
        }
    }

    public function show_previous_data_by_email(Request $request) {
        $email = $request->email;
        $wholesale_application = WholesaleApplicationInformation::where('email' , $email)->first();
        if (!empty($wholesale_application)) {
            $wholesale_application_address_billing = WholesaleApplicationAddress::where('wholesale_application_id' , $wholesale_application->id)->where('type' , 'Billing Address')->first();
            $wholesale_application_address_delivery = WholesaleApplicationAddress::where('wholesale_application_id' , $wholesale_application->id)->where('type' , 'Delievery Address')->first();
            $wholesale_regulation = WholesaleApplicationRegulationDetail::where('wholesale_application_id' , $wholesale_application->id)->first();
            $wholesale_authorization = WholesaleApplicationAuthorizationDetail::where('wholesale_application_id' , $wholesale_application->id)->first();
            $wholesale_application_card = WholesaleApplicationCard::where('wholesale_application_id' , $wholesale_application->id)->first();
            return response()->json([
                'status' => true,
                'message' => 'Email Already Exist !',
                'wholesale_application' => $wholesale_application,
                'wholesale_application_address_billing' => $wholesale_application_address_billing,
                'wholesale_application_address_delivery' => $wholesale_application_address_delivery,
                'wholesale_regulation' => $wholesale_regulation,
                'wholesale_authorization' => $wholesale_authorization,
                'wholesale_application_card' => $wholesale_application_card,
            ],200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Email Not Exist !'
            ],200);
        }
    
    }

    public function wholesale_user_check_email(Request $request) {
        $wholesale_application = WholesaleApplicationInformation::where('email' , $request->email_address_previous)->first();
        if (!empty($wholesale_application)) {
            $id = $wholesale_application->id;
            $wholesale_application_address_billing = WholesaleApplicationAddress::where('wholesale_application_id' , $id)->where('type' , 'Billing Address')->first();
            $wholesale_application_address_delivery = WholesaleApplicationAddress::where('wholesale_application_id' , $id)->where('type' , 'Delievery Address')->first();
            $wholesale_regulation = WholesaleApplicationRegulationDetail::where('wholesale_application_id' , $id)->first();
            $wholesale_authorization = WholesaleApplicationAuthorizationDetail::where('wholesale_application_id' , $id)->first();
            $wholesale_application_card = WholesaleApplicationCard::where('wholesale_application_id' , $id)->first();
            return redirect()->route('edit_wholesale_account' , $id);
        } else {
            return redirect()->back()->with('error' , 'Email Not Exist !');
        }
        
    }

    // allow access to primary user to toggle

    public function allow_access (Request $request) {
        $contact_primary_id = $request->contact_primary_id;
        $allow_access = $request->access_value;
        $contact = Contact::where('id' , $contact_primary_id)->first();
        if (!empty($contact)) {
            $user = User::where('email' , $contact->email)->first();
            if (!empty($user)) {
                $user->update([
                    'allow_access' => $allow_access
                ]);
                return response()->json([
                    'status' => 'success',
                    'data' => $user

                ],200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User Not Found !'
                ],200);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Contact Not Found !'
            ],200);
        }
    }

    public function wholesale_application_generate_pdf($id) {
        $wholesale_application =  WholesaleApplicationInformation::with('permit_images' ,  'wholesale_application_address' , 'wholesale_application_regulation_detail' , 'wholesale_application_authorization_detail' , 'wholesale_application_card')
        ->where('id' , $id)->orderBy('id' , 'Desc')->first()->toArray();
        $html = view('admin.wholesale_applications.generate_pdf', compact('wholesale_application'))->render();
        $pdf = PDF::loadHTML($html)->setOptions(
            [
                'defaultFont' => 'sans-serif',
                'isPhpEnabled' => true, 
                'isHtml5ParserEnabled' => true, 
                'isPhpEnabled' => true, 
                'isPhpEnabled' => true
            ]
        );
        return $pdf->download('document.pdf');
    }
}
