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
use App\Models\ApiErrorLog;
use App\Models\ApiKeys;
use App\Models\Category;
use App\Models\ContactLogs;
use App\Models\ContactsAddress;
use App\Models\DailyApiLog;
use App\Models\NewsletterSubscription;
use App\Models\Pricing;
use App\Models\Pricingnew;
use App\Models\ProductBuyList;
use App\Models\ProductOption;
use App\Models\SpecificAdminNotification;
use App\Models\TaxClass;
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
use Illuminate\Support\Facades\Log;
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
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $primaryUserSearch = $request->primaryUserSearch;
        $secondaryUserSearch = $request->secondaryUserSearch;
        $usersData = $request->usersData;
        $secondaryUser = $request->secondaryUser;
        $secondary_user = $request->secondary_user;
        // $user_query = User::withTrashed()->with('contact' , function($query) {
        //     $query->withTrashed();
        // });
        $user_query = User::with('contact');
        if (!empty($usersData)) {
            if ($usersData == 'admin-user') {
                $user_query = $user_query->role(['Admin']);
            } elseif ($usersData == 'cin7-merged') {
                $user_query = $user_query->orWhereHas('contact', function ($query) {
                    $query->whereNotNull('contact_id')
                    ->orWhereNotNull('secondary_id');
                });
            } elseif ($usersData == 'not-merged') {
                $user_query = $user_query->orWhereHas('contact', function ($query) {
                    $query->whereNull('contact_id')
                    ->whereNull('secondary_id');
                });
            }
        }
        // if (!empty($secondaryUser)) {

        //     if ($secondaryUser == 'secondary-user') {
        //         $user_query = $user_query->orWhereHas('contact', function ($query) {
        //             $query->whereNotNull('secondary_id')->with('childeren');
        //         });
        //         $user = $user_query->limit(10)->get();
        //     }
        //     if ($secondaryUser == 'primary-user') {

        //         $user_query = $user_query->orWhereHas('contact', function ($query) {
        //             $query->whereNotNull('contact_id');
        //         });
        //     }
        // }
        if (!empty($secondary_user)) {

            if ($secondary_user == 'secondary-user') {
                $user_query = $user_query->orWhereHas('contact', function ($query) {
                    $query->whereNotNull('secondary_id')->with('childeren');
                });
                $user = $user_query->limit(10)->get();
            }
            if ($secondary_user == 'primary-user') {

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


        if (!empty($from_date) || !empty($to_date)) {
            if (!empty($from_date) && !empty($to_date)) {
                $user_query = $user_query->whereHas('contact', function ($query) use ($from_date, $to_date) {
                    $query->whereBetween('created_at', [$from_date, $to_date]);
                });
            } elseif (!empty($from_date)) {
                $user_query = $user_query->whereHas('contact', function ($query) use ($from_date) {
                    $query->where('created_at', '>=', $from_date);
                });
            } elseif (!empty($to_date)) {
                $user_query = $user_query->whereHas('contact', function ($query) use ($to_date) {
                    $query->where('created_at', '<=', $to_date);
                });
            }
        }




        $download_csv = $request->get('download_csv');
        if ($download_csv == '1') {
            $data = $user_query->get();
            if (!empty($data)) {

                $csv_data = [];
                $csv_data[] = [
                    'First Name',
                    'Last Name',
                    'Email',
                    'Phone No',
                    'Address',
                    'Address 2',
                    'City',
                    'State',
                    'Country',
                    'Zip Code',
                ];

                foreach ($data as $key => $user) {
                    foreach ($user->contact as $contact) {
                      
                        $csv_data[] = [
                            !empty($contact->firstName) ? $contact->firstName : $user->first_name,
                            !empty($contact->lastName) ? $contact->lastName : $user->last_name,
                            !empty($contact->email) ? $contact->email : $user->email,
                            !empty($contact->phone) ? $contact->phone : $contact->mobile,
                            !empty($contact->postalAddress1) ? $contact->postalAddress1 : '',
                            !empty($contact->postalAddress2) ? $contact->postalAddress2 : '',
                            !empty($contact->postalCity) ? $contact->postalCity : '',
                            !empty($contact->postalState) ? $contact->postalState : '',
                            'US',
                            !empty($contact->postalPostCode) ? $contact->postalPostCode : '',
                        ];
                    }
                }

                $csv_file_name = 'customers.csv';
                $file_path = public_path($csv_file_name);
                $file = fopen($file_path, 'w');
                foreach ($csv_data as $line) {
                    fputcsv($file, $line);
                }
                fclose($file);

                $headers = array(
                    'Content-Type' => 'text/csv',
                );

                return response()->download($file_path, $csv_file_name, $headers);
            }

        } else {

            $data = $user_query->orderBy('created_at' , 'Desc')->paginate(10)->appends(request()->query());
        }

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
            'from_date',
            'to_date',
            'secondary_user'
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

        $update_contact = Contact::where('user_id', $id)->update([
            'email' => $request->email,
        ]);

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
        $request->validate([
            'email' => 'required|email'
        ]);
        
        $user = User::where('email', $request->email)->first();

        if (empty($user)) {
            return redirect()->back()->with('error', 'Email not found! Please re-register or try to checkout again. Alternatively you can send us a message via the contact us form. <a href="contact-us">Contact Us</a>');
        }

        $plain_password = Str::random(10) . date('YmdHis');
        $encrypted_password = bcrypt($plain_password);
        $hash = Str::random(10000) . $user->first_name . date('YmdHis');
        $hash = md5($hash);

        $user->password = $encrypted_password;
        $user->hash = $hash;
        $user->hash_date = Carbon::now();
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
        $session_contact_id = Session::get('contact_id');
        $email_user = session::put('user', $user);
        $cart = [];
        if (auth()->attempt($credentials)) {
            if (auth()->user()->allow_access == 0) {
                Session::flush();
                Auth::logout();
                session()->flash('message', 'Your account has been disabled.');
                return redirect()->back();
            } 
            else {
                $user_id = auth()->user()->id;
                if ($user->hasRole(['Newsletter']) || $user->hasRole(['Sale Payments']) || $user->hasRole(['Payouts'])) {
                    session()->flash('message', 'Successfully Logged in');
                    return redirect()->route('newsletter_dashboard');
                }
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
                    if ($companies->count() > 1) {
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
                    if ($companies->count() > 1) {
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
                            if ($companies->count() == 1) {
                                if ($companies[0]->contact_id == null) {
                                    UserHelper::switch_company($companies[0]->secondary_id);
                                } else {
                                    UserHelper::switch_company($companies[0]->contact_id);
                                }
                            }
                            if ($companies->count() > 1) {
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
                            $previousUrl = session('previous_url', '/');
                            return redirect('/');
                        } else {
                            $companies = Contact::where('user_id', auth()->user()->id)->get();
                            if ($companies->count() == 1) {
                                if ($companies[0]->contact_id == null) {
                                    UserHelper::switch_company($companies[0]->secondary_id);
                                } else {
                                    UserHelper::switch_company($companies[0]->contact_id);
                                }
                            }
                            if ($companies->count() > 1) {
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
        // $validatedData = $request->validate([
        //     'email' => 'required|email'
            
        // ]);
        $request->validate([
            'email' => [
                'required',
                'email',
                'regex:/^(?!\.)(?!.*\.\.)[a-zA-Z0-9.!#$%&\'*+\/=?^_`{|}~-]+(?<!\.)@[a-zA-Z0-9](?!.*\.\.)[a-zA-Z0-9-]*(?<!-)\.[a-zA-Z]{2,}$/',
            ],
        ], [
            'email.required' => 'The email field is required.',
            'email.regex' => 'Please provide a valid email address.',
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
            // 'company_name' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
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
        Session::forget('re_order_id');
        Session::flush();

        Auth::logout();

        return redirect()->route('user');
    }

    
    public function save_contact(CompanyInfoRequest $request)
    {
        
        $price_column = null;
        $content = null;
        $registration_status = false;
        $auto_approved = false;
        $created_contact = null;
        $already_in_cin7 = false;
        $address_validation_flag = true;
        $success = true;   
        $toggle_registration = AdminSetting::where('option_name', 'toggle_registration_approval')->first();
        $default_price_column = AdminSetting::where('option_name', 'default_price_column')->first();
        if (!empty($default_price_column)) {
            $price_column = ucfirst($default_price_column->option_value);
        }
        else {
            $price_column = 'SacramentoUSD';
        }

        if (empty($price_column)) {
            $price_column = 'RetailUSD';
        }

        $validatedData = $request->validate(
            [
                'street_address' => [
                    'required',
                    // function ($attribute, $value, $fail) {
                    //     if (preg_match('/^(P\.?\s*O\.?\s*Box)/i', trim($value))) {
                    //         $fail('Invalid address: PO Boxes are not allowed at the start.');
                    //     }
                    // },
                ],
                'state_id' => 'required',
                'city_id' => 'required',
                'zip' => ['required', 'regex:/^\d{5}(-\d{4})?$/'],
            ],
            [
                'state_id.required' => 'The state field is required.',
                'city_id.required' => 'The city field is required.',
            ] 
                
        );


        $states = UsState::where('id', $request->state_id)->first();
        $state_name = $states->state_name;



        $validate_street_address_1 = $request->input('street_address');
        $validate_street_address_2 = $request->input('suit_apartment');
        $validate_city = $request->city_id;
        $validate_state = $state_name;
        $validate_zip = $request->input('zip');
        $selectedChoice = $request->input('user_choice') ?? null;


        if (!$selectedChoice || $selectedChoice !== 'entered') {
            $validate_address =  UserHelper::validateFullAddress($validate_street_address_1 , $validate_street_address_2 , $validate_city , $validate_state , $validate_zip, $country = 'USA');

            if ($validate_address['valid'] == false) {
                return response()->json([
                    'status' => 'address_error',
                    'address_validator' => false,
                    'validator_message' => $validate_address['message'] ?? 'Address validation failed.',
                    'suggested_address' => $validate_address['suggested_address'] ?? '',
                    'formatted_address' => $validate_address['formatted_address'] ?? '',
                ], 400);
            }
        }
        
        
        $contacts = Contact::where('email', $request->email)->first();
        if (!empty($contacts)) {
            $api_contact = $contacts->toArray();
            $client = new \GuzzleHttp\Client();
            $url = "https://api.cin7.com/api/v1/Contacts/";
            $cin7_auth_username = SettingHelper::getSetting('cin7_auth_username');
            $cin7_auth_password = SettingHelper::getSetting('cin7_auth_password_2');

            $cin7api_key_for_other_jobs =  ApiKeys::where('password', $cin7_auth_password)
            ->where('is_active', 1)
            ->where('is_stop', 0)
            ->first();

            $api_key_id = null;
            
            if (!empty($cin7api_key_for_other_jobs)) {
                $cin7_auth_username = $cin7api_key_for_other_jobs->username;
                $cin7_auth_password = $cin7api_key_for_other_jobs->password;
                $threshold = $cin7api_key_for_other_jobs->threshold;
                $request_count = !empty($cin7api_key_for_other_jobs->request_count) ? $cin7api_key_for_other_jobs->request_count : 0;
                $api_key_id = $cin7api_key_for_other_jobs->id;
            } else {
                Log::info('No active api key found');
                return false;
            }

            if ($request_count >= $threshold) {
                Log::info('Request count exceeded');
                return false;
            }

            $response = $client->post($url, [
                'headers' => ['Content-type' => 'application/json'],
                'auth' => [
                    $cin7_auth_username,
                    $cin7_auth_password
                ],
                'json' => [
                    $api_contact
                ],
            ]);
            UtilHelper::saveEndpointRequestLog('Sync Contacts' ,$url , $api_key_id);
            $response = json_decode($response->getBody()->getContents());
            $registration_status = false;
            if ($response[0]->success == false) {
                $content = 'User already exists in Cin7 . Please contact support.';
                $already_in_cin7 = true;
                $created = false;
                $auth_user = Auth::loginUsingId($contacts->user_id);
                $companies = Contact::where('user_id', $auth_user->id)->get();

                if ($request->session()->has('cart_hash')) {
                    $cart_hash = $request->session()->get('cart_hash');
                    $cart_items = Cart::where('cart_hash', $cart_hash)->where('is_active', 1)->where('user_id', 0)->get();
                    foreach ($cart_items as $cart_item) {
                        $cart_item->user_id = auth()->user()->id;
                        $cart_item->save();
                    }
                }

                if (count($companies) > 0 ) {
                    if ($companies[0]->contact_id == null) {
                        UserHelper::switch_company($companies[0]->secondary_id);
                        
                    } else {
                        UserHelper::switch_company($companies[0]->contact_id);
                       
                    }
                }

                
                
            }
        }
        else {
            DB::beginTransaction();
            try {
                
                // $cities = UsCity::where('id', $request->city_id)->first();
                // $city_name = $cities->city;
                $city_name =  $request->city_id;
                $user = User::create([
                    'email' => strtolower($request->get('email')),
                    "first_name" => $request->get('first_name'),
                    "last_name" => $request->get('last_name'),
                    "password" => bcrypt($request->get('password'))
                ]);
                
                $user_id = $user->id;
                // $newsletter_subscriber = NewsletterSubscription::where('email', $request->email)->first();
                // if (empty($newsletter_subscriber)) {
                //     $newsletter_subscriber->email = $user->email;
                //     $newsletter_subscriber->save();
                // }
                $contact = new Contact([
                    'website' => $request->input('company_website'),
                    'company' => !empty($request->input('company_name')) ? $request->input('company_name') : '',
                    'phone' => $request->input('phone'),
                    'status' => !empty($toggle_registration) && strtolower($toggle_registration->option_value) == 'yes' ? 1 : 0,
                    'priceColumn' => $price_column,
                    'user_id' => $user_id,
                    'firstName' => $user->first_name,
                    'type' => 'Customer',
                    'lastName' => $user->last_name,
                    'email' => $user->email,
                    'is_parent' => 1,
                    'tax_class' => strtolower($state_name) == strtolower('California') ? '8.75%' : 'Out of State',
                    'paymentTerms' => $request->paymentTerms,
                    'charge_shipping' => 1,
                    'accountsFirstName' => $user->first_name,
                    'accountsLastName' => $user->last_name,
                    'billingEmail' => SettingHelper::getSetting('noreply_email_address'),
                    'postalAddress1' => $request->input('street_address'),
                    'postalAddress2' => $request->input('suit_apartment'),
                    'postalState' => $state_name,
                    'postalCity' => $city_name,
                    'postalPostCode' => $request->input('zip'),
                    'address1' => $request->input('street_address'),
                    'address2' => $request->input('suit_apartment'),
                    'state' => $state_name,
                    'city' => $city_name,
                    'postCode' => $request->input('zip'),
                ]);
                if (!empty($toggle_registration) && strtolower($toggle_registration->option_value) == 'yes') {
                    $auto_approved = true;
                    $api_contact = $contact->toArray();
                    $client = new \GuzzleHttp\Client();
                    $url = "https://api.cin7.com/api/v1/Contacts/";

                    $cin7_auth_username = SettingHelper::getSetting('cin7_auth_username');
                    $cin7_auth_password = SettingHelper::getSetting('cin7_auth_password_2');

                    $cin7api_key_for_other_jobs =  ApiKeys::where('password', $cin7_auth_password)
                    ->where('is_active', 1)
                    ->where('is_stop', 0)
                    ->first();

                    $api_key_id = null;
                    
                    if (!empty($cin7api_key_for_other_jobs)) {
                        $cin7_auth_username = $cin7api_key_for_other_jobs->username;
                        $cin7_auth_password = $cin7api_key_for_other_jobs->password;
                        $threshold = $cin7api_key_for_other_jobs->threshold;
                        $request_count = !empty($cin7api_key_for_other_jobs->request_count) ? $cin7api_key_for_other_jobs->request_count : 0;
                        $api_key_id = $cin7api_key_for_other_jobs->id;
                    } else {
                        Log::info('No active api key found');
                        return false;
                    }

                    if ($request_count >= $threshold) {
                        Log::info('Request count exceeded');
                        return false;
                    }


                    $response = $client->post($url, [
                        'headers' => ['Content-type' => 'application/json'],
                        'auth' => [
                            $cin7_auth_username,
                            $cin7_auth_password
                        ],
                        'json' => [
                            $api_contact
                        ],
                    ]);

                    UtilHelper::saveEndpointRequestLog('Sync Contacts',$url , $api_key_id);
                    
                    $response = json_decode($response->getBody()->getContents());
                    if ($response[0]->success == true) {
                        $created = true;
                        $contact->contact_id = $response[0]->id;
                        $contact->save();
                        $created_contact = Contact::where('id', $contact->id)->first();
                        $registration_status = true;
                        $admin_users =  DB::table('model_has_roles')->where('role_id', 1)->pluck('model_id');
                        $admin_users = $admin_users->toArray();
        
                        $users_with_role_admin = User::select("email")
                            ->whereIn('id', $admin_users)
                            ->get();
                        
                        $user_log = UserLog::create([
                            'user_id' => $user->id,
                            'action' => 'Signup',
                            'user_notes' => $content.' '. Carbon::now()->toDateTimeString()
                        ]);
        
                        $contact = Contact::where('user_id', $user->id)->first()->update(
                            [
                                'postalAddress1' => $request->input('street_address'),
                                'postalAddress2' => $request->input('suit_apartment'),
                                'postalState' => $state_name,
                                'postalCity' => $city_name,
                                'postalPostCode' => $request->input('zip'),
                                'address1' => $request->input('street_address'),
                                'address2' => $request->input('suit_apartment'),
                                'state' => $state_name,
                                'city' => $city_name,
                                'postCode' => $request->input('zip'),
                            ]
                        );
                        $auth_user = Auth::loginUsingId($created_contact->user_id);

                        if ($request->session()->has('cart_hash')) {
                            $cart_hash = $request->session()->get('cart_hash');
                            $cart_items = Cart::where('cart_hash', $cart_hash)->where('is_active', 1)->where('user_id', 0)->get();
                            foreach ($cart_items as $cart_item) {
                                $cart_item->user_id = auth()->user()->id;
                                $cart_item->save();
                            }
                        }

                        $companies = Contact::where('user_id', $auth_user->id)->get();
                        if (count($companies) > 0 ) {
                            if ($companies[0]->contact_id == null) {
                                UserHelper::switch_company($companies[0]->secondary_id);
                                
                            } else {
                                UserHelper::switch_company($companies[0]->contact_id);
                               
                            }
                            Session::put('companies', $companies);
                        }

                        
                        
                        
                    } 
                    $content = 'Your account has been created successfully and approved by admin.';
                } else {
                    $auto_approved = false;
                    $created = true;
                    $contact->save();
                    $created_contact = Contact::where('id', $contact->id)->first();
                    $registration_status = true;
                    $admin_users =  DB::table('model_has_roles')->where('role_id', 1)->pluck('model_id');
                    $admin_users = $admin_users->toArray();

                    $users_with_role_admin = User::select("email")
                        ->whereIn('id', $admin_users)
                        ->get();
                    
                    $user_log = UserLog::create([
                        'user_id' => $user->id,
                        'action' => 'Signup',
                        'user_notes' => $content.' '. Carbon::now()->toDateTimeString()
                    ]);

                    $contact = Contact::where('user_id', $user->id)->first()->update(
                        [
                            'postalAddress1' => $request->input('street_address'),
                            'postalAddress2' => $request->input('suit_apartment'),
                            'postalState' => $state_name,
                            'postalCity' => $city_name,
                            'postalPostCode' => $request->input('zip'),
                            'address1' => $request->input('street_address'),
                            'address2' => $request->input('suit_apartment'),
                            'state' => $state_name,
                            'city' => $city_name,
                            'postCode' => $request->input('zip'),
                        ]
                    );
                    $auth_user = Auth::loginUsingId($created_contact->user_id);
                    $session_contact_id = null;

                    if ($request->session()->has('cart_hash')) {
                        $cart_hash = $request->session()->get('cart_hash');
                        $cart_items = Cart::where('cart_hash', $cart_hash)->where('is_active', 1)->where('user_id', 0)->get();
                        foreach ($cart_items as $cart_item) {
                            $cart_item->user_id = auth()->user()->id;
                            $cart_item->save();
                        }
                    }

                    $companies = Contact::where('user_id', $auth_user->id)->get();
                    if (count($companies) > 0 ) {
                        if ($companies[0]->contact_id == null) {
                            UserHelper::switch_company($companies[0]->secondary_id);
                            
                        } else {
                            UserHelper::switch_company($companies[0]->contact_id);
                           
                        }
                        Session::put('companies', $companies);
                    }

                    

                    $content = 'Your account registration request has been submitted. You will receive an email once your account has been approved.';
                }
                
                $data = [
                    'user' => $user,
                    'subject' => 'New Register User',
                    'name' => $user->first_name . ' ' . $user->last_name,
                    'content' => $content,
                    'email' => $user->email,
                    'subject' => 'Your account registration request ',
                    'from' => SettingHelper::getSetting('noreply_email_address')
                ];
                if ($registration_status == true) {
                    
                    // $specific_admin_notifications = SpecificAdminNotification::all();
                    // if (count($specific_admin_notifications) > 0) {
                    //     foreach ($specific_admin_notifications as $specific_admin_notification) {
                    //         $subject = 'New Register User';
                    //         $data['email'] = $specific_admin_notification->email;
                    //         MailHelper::sendMailNotification('emails.admin_notification', $data);
                    //     }
                    // }

                    $specific_admin_notifications = SpecificAdminNotification::all();
                    if ($specific_admin_notifications->isNotEmpty()) {
                        foreach ($specific_admin_notifications as $specific_admin_notification) {
                            // Check if this admin should receive order notifications
                            if (!$specific_admin_notification->recieve_order_notification) {
                                continue;
                            }

                            $subject = 'New Register User';
                            
                            $data['email'] = $specific_admin_notification->email;

                            MailHelper::sendMailNotification('emails.admin_notification', $data);
                        }
                    }
                    
                    if (!empty($created_contact)) {
                        if ($auto_approved == true) {
                            $data['contact_name'] = $created_contact->firstName . ' ' . $created_contact->lastName;
                            $data['contact_email'] = $created_contact->email;
                            $data['content'] = $content;
                            $data['subject'] = 'Your account registration request ';
                            MailHelper::sendMailNotification('emails.approval-notifications', $data);
                        } else {
                            $data['content'] = $content;
                            $data['subject'] = 'Your account registration request ';
                            MailHelper::sendMailNotification('emails.user_registration_notification', $data);
                        }
                    }
                } else {
                    $data['content'] = 'User already exists in Cin7 . Please contact support.';
                    $data['subject'] = 'User already exists in Cin7 . Please contact support.';
                    MailHelper::sendMailNotification('emails.approval-notifications', $data);
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                $created = false;
                $success = false;
                $content = 'Something went wrong. Please contact admin.';
            }
        }

        return response()->json([
            'success' => $success,
            'created' => $created,
            'msg' => $content,
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
            $selected_company = Session::get('contact_id');
            $all_ids = UserHelper::getAllMemberIds($user);
            $contact_ids = Contact::whereIn('id', $all_ids)
                ->pluck('contact_id')
                ->toArray();

            $frequent_products = $this->buy_again_products($request);
            
            $user_orders_query = ApiOrder::with(['createdby'])
                // ->whereIn('memberId', $contact_ids)
                ->with('contact' , function($query) {
                    $query->orderBy('company');
                })
                ->with('apiOrderItem.product');
           
            if (!empty($request->sort_by)) {
                $sort_by = $request->sort_by;
                if ($sort_by == 'recent') {

                    $user_orders = $user_orders_query->whereIn('memberId', $contact_ids)->orderBy('created_at' , 'Desc')->paginate(10);
                }
                if ($sort_by == 'amount') {

                    $user_orders = $user_orders_query->whereIn('memberId', $contact_ids)->orderBy('total' , 'Desc')->paginate(10);
                }

            } else {
                $user_orders = $user_orders_query->whereIn('memberId', $contact_ids)->orderBy('created_at' , 'Desc')->paginate(10);
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
                ->whereIn('memberId', $contact_ids)
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
                    ->whereIn('memberId', $contact_ids)
                    ->whereBetween('created_at', $last_month)
                    ->orderBy('created_at' , 'Desc')
                    ->paginate(10);
                }

                if ($date_filter == 'last-3-months') {
                    $user_orders = $user_orders_query
                    ->whereIn('memberId', $contact_ids)
                    ->whereBetween('created_at', $last_3_months)
                    ->orderBy('created_at' , 'Desc')
                    ->paginate(10);
                }
                
                if ($date_filter == 'last-5-months') {
                    $user_orders = $user_orders_query
                    ->whereIn('memberId', $contact_ids)
                    ->whereBetween('created_at', $last_5_months)
                    ->orderBy('created_at' , 'Desc')
                    ->paginate(10);
                }
                
                if($date_filter == 'last-year') {
                    $user_orders = $user_orders_query
                    ->whereIn('memberId', $contact_ids)
                    ->whereBetween('created_at', $past_year)
                    ->orderBy('created_at' , 'Desc')
                    ->paginate(10);
                }
            }   else {
                $user_orders = $user_orders_query
                ->whereIn('memberId', $contact_ids)
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
                ->orWhere('secondaryId' , $submitter_filter)
                ->whereIn('memberId', $contact_ids)
                ->paginate(10);
            }
            if (empty($submitter_filter)) {
                $submitter_filter = 'all';
            }

            // $custom_roles_with_company = DB::table('custom_roles')
            //     ->where('user_id', $user_id)
            //     ->where('contact_id', $selected_company)
            //     ->orWhere('secondary_id', $selected_company)
            //     ->first();
                
            // if (!empty($custom_roles_with_company) && ($custom_roles_with_company->contact_id == $selected_company  || $custom_roles_with_company->secondary_id == $selected_company)) {
            //     $order_approver_for_company = true;
            // } else {
            //     $order_approver_for_company = false;
            // }
            $find_order_approver = Contact::where('user_id', $user_id)
                ->where('contact_id', $selected_company)
                ->orWhere('secondary_id', $selected_company)
                ->first();

            if (empty($find_order_approver)) {
                $order_approver_for_company = false;
            } else {
                $custom_roles_with_company = DB::table('custom_roles')
                ->where('user_id', $user_id)
                ->first();
                if (!empty($custom_roles_with_company) && $custom_roles_with_company->user_id == $find_order_approver->user_id) {
                    $order_approver_for_company = true;
                } else {
                    $order_approver_for_company = false;
                }
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
            ->where('contact_id', $selected_company)
            ->orWhere('secondary_id', $selected_company)
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

            $products_to_hide = BuyList::with('list_products')->where('title' , 'Products_to_hide')->first();
        
            if (!empty($products_to_hide)) {
                $products_to_hide = $products_to_hide->list_products->pluck('option_id')->toArray();
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
                'order_submitters','submitter_filter','address_user','products_to_hide'
            ));
        }
    }

    // buy again products 
    public function buy_again_products(Request $request) {
        $user_id = auth()->id();
        $get_wholesale_contact_id = null;
        $get_wholesale_terms = null;
        $session_contact = Session::get('contact_id') != null ? Session::get('contact_id') : null;
            
        // Get wholesale_contact
        if (!empty($user_id)) {
            $wholesale_contact = Contact::where('user_id', auth()->user()->id)
            ->where('contact_id', $session_contact)
            ->orWhere('secondary_id', $session_contact)
            ->first();

            if (!empty($wholesale_contact)) {
                if ($wholesale_contact->is_parent == 1 && !empty($wholesale_contact->contact_id)) {
                    $get_wholesale_contact_id = $wholesale_contact->contact_id;
                    $get_wholesale_terms = $wholesale_contact->paymentTerms;
                } else {
                    $wholesale_contact_child = Contact::where('user_id', $user_id)
                        ->whereNull('contact_id')
                        ->where('is_parent', 0)
                        ->where('secondary_id', $session_contact)
                        ->first();
                    
                    // Ensure $wholesale_contact_child is not null before accessing parent_id
                    $get_wholesale_contact_id = $wholesale_contact_child ? $wholesale_contact_child->parent_id : null;
                    $get_wholesale_terms = $wholesale_contact_child->paymentTerms;
                }
            }
        } else {
            $wholesale_contact = null;
        }

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
        $option_ids = ProductOption::whereIn('product_id', $product_ids)
        ->where('stockAvailable', '!=', null)
        ->where('stockAvailable', '>', 0)
        ->pluck('option_id')
        ->toArray();
        $price_ids = Pricingnew::whereIn('option_id', $option_ids)->where($user_price_column, '>' , 0)->pluck('option_id')->toArray();
        $product_options_ids = ProductOption::whereIn('option_id', $price_ids)->pluck('product_id')->toArray();
        $category_ids = Product::whereIn('product_id', $product_options_ids)->pluck('category_id')->toArray();
        $active_category_ids = Category::whereIn('category_id', $category_ids)->where('is_active' , '1')->pluck('category_id')->toArray();
        $active_products_ids = Product::whereIn('category_id' ,$active_category_ids)->where('status', '!=', 'Inactive')->pluck('product_id')->toArray();
        

        $buy_again_query = Product::with('categories' , 'options' , 'options.defaultPrice')
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

        return [
            'frequent_products' => $frequent_products,
            'get_wholesale_terms' => $get_wholesale_terms
        ];
        // return $frequent_products;
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
        $selected_company = Session::get('contact_id');
        $get_contact = Contact::where('user_id', $user_id)
            ->where('status', 1)
            ->where('contact_id', $selected_company)
            ->orWhere('secondary_id', $selected_company)
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
        $products_to_hide = BuyList::with('list_products')->where('title' , 'Products_to_hide')->first();
    
        if (!empty($products_to_hide)) {
            $products_to_hide = $products_to_hide->list_products->pluck('option_id')->toArray();
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
            'address_user',
            'products_to_hide'
        ));
        // return $images;
    }
    
    public function getAllFavorites()
    {
        $contact_id = session()->get('contact_id');
        $user_id = auth()->id(); // Ensure this is defined

        // Get all BuyList entries titled "My Favorites" for this user/contact
        $lists = BuyList::where('user_id', $user_id)
            ->where('contact_id', $contact_id)
            ->where('title', 'My Favorites')
            ->get();

        if ($lists->isEmpty()) {
            return response()->json([]);
        }

        // Collect all matching ProductBuyList entries across those lists
        $list_ids = $lists->pluck('id');
        $favorites = ProductBuyList::with('buylist','product.options.price')
            ->whereIn('list_id', $list_ids)
            ->get();

        // Format the result
        $data = $favorites->map(function ($fav) {
            return [
                'product_id' => $fav->product_id,
                'option_id' => $fav->option_id
            ];
        });

        return response()->json($data);
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
        $selected_company = Session::get('contact_id');
        $find_order_approver = Contact::where('user_id', $user_id)
            ->where('contact_id', $selected_company)
            ->orWhere('secondary_id', $selected_company)
            ->first();

        if (empty($find_order_approver)) {
            $order_approver_for_company = false;
        } else {
            $custom_roles_with_company = DB::table('custom_roles')
            ->where('user_id', $user_id)
            ->first();
            if (!empty($custom_roles_with_company) && $custom_roles_with_company->user_id == $find_order_approver->user_id) {
                $order_approver_for_company = true;
            } else {
                $order_approver_for_company = false;
            }
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
        $discount_variation_value = 0;
        $discount_variation = null;
        $enable_discount_setting = AdminSetting::where('option_name', 'enable_discount')->first();
        if (!empty($enable_discount_setting) && strtolower($enable_discount_setting->option_value) == 'yes') {
            if (!empty($user_order->discount)) {
                $discount_variation_value = $user_order->discount->discount_variation_value;
                $discount_variation = $user_order->discount->discount_variation;
            }
        }
        $tax=0;
        $tax_rate = 0;
        $subtotal = 0;
        $tax_without_discount = 0;
        $subtotal = $user_order->total;
        $tax_class = TaxClass::where('name', $order_detail->contact->tax_class)->first();
        $discount_amount = $user_order->discount_amount;
        if (isset($discount_variation_value) && !empty($discount_variation_value) && $discount_amount > 0) {
            $discount_variation_value = $discount_variation_value;
            if (!empty($tax_class)) {
                $tax_rate = $tax_class->rate;
                $tax_without_discount = $subtotal * ($tax_rate / 100);
                if (!empty($discount_variation) && $discount_variation == 'percentage') {
                    $tax = $tax_without_discount - ($tax_without_discount * ($discount_variation_value / 100));
                } else {
                    $tax = $tax_without_discount - $discount_variation_value;
                }
            }

        } else {
            if (!empty($tax_class)) {
                $tax_rate = $tax_class->rate;
                $tax = $subtotal * ($tax_rate / 100);
            }
        } 
        return view('my-account.order-detail', compact(
            'lists',
            'user',
            'user_address',
            'secondary_contacts',
            'parent',
            'companies',
            'user_order',
            'orderdetails',
            'order_detail',
            'tax',
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
        $selected_company = Session::get('contact_id');
        $get_contact = Contact::where('user_id', $user_id)
        ->where('status', 1)
        ->where('contact_id', $selected_company)
        ->orWhere('secondary_id', $selected_company)
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
        $selected_company = Session::get('contact_id');
        $get_contact = Contact::where('user_id', $user_id)
        ->where('status', 1)
        ->where('contact_id', $selected_company)
        ->orWhere('secondary_id', $selected_company)
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

        $cin7api_key_for_other_jobs =  ApiKeys::where('password', $cin7_auth_password)
        ->where('is_active', 1)
        ->where('is_stop' , 0)
        ->first();

        $api_key_id = null;
        
        if (!empty($cin7api_key_for_other_jobs)) {
            $cin7_auth_username = $cin7api_key_for_other_jobs->username;
            $cin7_auth_password = $cin7api_key_for_other_jobs->password;
            $thresold = $cin7api_key_for_other_jobs->threshold;
            $request_count = !empty($cin7api_key_for_other_jobs->request_count) ? $cin7api_key_for_other_jobs->request_count : 0;
            $api_key_id = $cin7api_key_for_other_jobs->id;
        } else {
            Log::error('Cin7 API Key not found or inactive');
            return false;
        }

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

                            UtilHelper::saveEndpointRequestLog('Sync Contacts' , $cin7_get_contact_url , $api_key_id);
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
        $selected_company = Session::get('contact_id');
        $get_contact = Contact::where('user_id', $user_id)
        ->where('status', 1)
        ->where('contact_id', $selected_company)
        ->orWhere('secondary_id', $selected_company)
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
        // ticket creating on upaating address
        // $subdomain = env('ZENDESK_SUBDOMAIN'); 
        // $username = env('ZENDESK_USERNAME'); 
        // $token =  env('ZENDESK_TOKEN'); 
        // $auth = [
        //     'token' => $token, 
        // ];
        
        // $client = new ZendeskClient($subdomain);
        // $client->setAuth('basic', ['username' => $username, 'token' => $token]);

        // $subject = $request->type;
        // $requesterName = $request->first_name . ' ' . $request->last_name;
        // $requesterEmail = $request->email;
        
        // $company_name = !empty($request->company_name) ? $request->company_name : '';
        // $address1 = $request->address;
        // $address2 = $request->address2;
        // $city = $request->town_city;
        // $state = $request->state;
        // $zip = $request->zip;

        // $user_message = $requesterName . ' ' . 'requested to change his/her profile information.';
        // $description = $user_message  . "\n" .  "Request Type : " . $subject . "\n" . "Company : " . $company_name . "\n" . "Address 1: " . $address1 . "\n" . "Address 2: " . $address2 . "\n" . "City: " . $city . "\n" . "State: " . $state . "\n" . "Zip: " . $zip . "\n";
        
        // $ticketData = [
        //     'subject' => $subject,
        //     'description' => $description,
        //     'requester' => [
        //         'email' => $requesterEmail,
        //         'name' => $requesterName,
        //     ],
        // ];

        // $response = $client->tickets()->create($ticketData);
        $request->validate(
            [
                'first_name' => 'required',
                'address' => [
                    'required',
                    // function ($attribute, $value, $fail) {
                    //     if (preg_match('/^(P\.?\s*O\.?\s*Box)/i', trim($value))) {
                    //         $fail('Invalid address: PO Boxes are not allowed at the start.');
                    //     }
                    // },
                ],
                'state' => 'required',
                'phone' => 'required',
                'town_city' => 'required',
                'zip' => ['required', 'regex:/^\d{5}(-\d{4})?$/'],
            ], 
            [
                'town_city.required' => 'City is required.',
            ]
        );

        $response  = null;
        $get_contact = null;
        $contact_type = null;
        $address_type  = null;
        $response_status = false;
        $contact_synced = false;
        $contact_id = $request->contact_id;
        $secondary_id = $request->secondary_id;
        $response_status = null;
        $responseBody = null;
        $cin7_status = null;

        if (!empty($request->type) && $request->type == 'update shipping address') {
            $address_type = 'shipping';
        }
        
        if (!empty($request->type) && $request->type == 'update billing address') {
            $address_type = 'billing';
        }

        if (!empty($request->contact_id)) {
            $get_contact = Contact::where('contact_id', $contact_id)
            ->where('is_parent', 1)
            ->first();
            $contact_type = 'primary';
        }

        if (!empty($secondary_id) && empty($contact_id)) {
            $secondary_contact = Contact::where('secondary_id', $secondary_id)
            ->where('is_parent', 0)
            ->first();
            $contact_type = 'secondary';
            $get_contact = Contact::where('contact_id', $secondary_contact->parent_id)->first();
        }


        $user_choice_address = $request->user_choice_address ?? null;

        


        if (!empty($get_contact) && !empty($address_type)) {
            if ($address_type === 'shipping') {

                if (!$user_choice_address || $user_choice_address !== 'entered') {
                    $validate_address_shipping =  UserHelper::validateFullAddress($request->address , $request->address2 , $request->town_city , $request->state , $request->zip, $country = 'USA');
                    if ($validate_address_shipping['valid'] == false) {
                        return response()->json([
                            'status' => 'address_error',
                            'address_validator' => false,
                            'validator_message' => $validate_address_shipping['message'] ?? 'Address validation failed.',
                            'suggested_address' => $validate_address_shipping['suggested_address'] ?? '',
                            'formatted_address' => $validate_address_shipping['formatted_address'] ?? '',
                        ], 400);
                    }
                }


                $get_contact->firstName = $request->first_name;
                $get_contact->lastName = $request->last_name;
                
                if (!empty($request->check_company_count) && $request->check_company_count == 1) {
                    $get_contact->company = $request->company_name;
                }


                $get_contact->address1 = $request->address;
                $get_contact->address2 = $request->address2;
                $get_contact->state = $request->state;
                $get_contact->city = $request->town_city;
                $get_contact->postCode = $request->zip;
                $get_contact->phone = $request->phone;
                $get_contact->tax_class = strtolower($request->state) == strtolower('California') ? '8.75%' : 'Out of State';
                $get_contact->save();
                $response = $get_contact;
                $response_status = true;       

            }

            if ($address_type === 'billing') {

                if (!$user_choice_address || $user_choice_address !== 'entered') {
                    $validate_address_shipping =  UserHelper::validateFullAddress($request->address , $request->address2 , $request->town_city , $request->state , $request->zip, $country = 'USA');
                    if ($validate_address_shipping['valid'] == false) {
                        return response()->json([
                            'status' => 'address_error',
                            'address_validator' => false,
                            'validator_message' => $validate_address_shipping['message'] ?? 'Address validation failed.',
                            'suggested_address' => $validate_address_shipping['suggested_address'] ?? '',
                            'formatted_address' => $validate_address_shipping['formatted_address'] ?? '',
                        ], 400);
                    }
                }

                $get_contact->firstName = $request->first_name;
                $get_contact->lastName = $request->last_name;

                if (!empty($request->check_company_count) && $request->check_company_count == 1) {
                    $get_contact->company = $request->company_name;
                }

                $get_contact->postalAddress1 = $request->address;
                $get_contact->postalAddress2 = $request->address2;
                $get_contact->postalState = $request->state;
                $get_contact->postalCity = $request->town_city;
                $get_contact->postalPostCode = $request->zip;
                $get_contact->phone = $request->phone;
                $get_contact->tax_class = strtolower($request->state) == strtolower('California') ? '8.75%' : 'Out of State';
                $get_contact->save();
                $response = $get_contact;
                $response_status = true;


                $check_billing_address = ContactsAddress::where('contact_id', $get_contact->contact_id)
                ->where('address_type', 'Billing')
                ->get();

                if (count($check_billing_address) > 0) {
                    foreach ($check_billing_address as $address) {
                        $address->is_default = 0;
                        $address->save();
                    }
                }

                $billing_address = new ContactsAddress();
                $billing_address->contact_id = $get_contact->contact_id;
                $billing_address->address_type = 'Billing';
                $billing_address->BillingFirstName = $get_contact->firstName;
                $billing_address->BillingLastName = $get_contact->lastName;
                $billing_address->BillingCompany = $get_contact->company;
                $billing_address->BillingPhone = $get_contact->phone;
                $billing_address->BillingAddress1 = $get_contact->postalAddress1;  
                $billing_address->BillingAddress2 = $get_contact->postalAddress2;
                $billing_address->BillingCity = $get_contact->postalCity;
                $billing_address->BillingState = $get_contact->postalState;
                $billing_address->BillingZip = $get_contact->postalPostCode;
                $billing_address->BillingCountry = 'US';
                $billing_address->is_default = 1;
                $billing_address->save();
            }
        } else {
            $response_status = false;
        }


        if ($response_status == true) {
            $contact = Contact::where('id', $get_contact->id)->first();
            
            $companies = Contact::where('user_id', auth()->user()->id)->get();

            if ($companies->count() == 1) {
                if ($companies[0]->contact_id == null) {
                    UserHelper::switch_company($companies[0]->secondary_id);
                } else {
                    UserHelper::switch_company($companies[0]->contact_id);
                }
            }

            Session::put('companies', $companies);

            $body = [
                'id'=> $contact->contact_id,
                'type' => $contact->type,
                'firstName' => $contact->firstName,
                'lastName' => $contact->lastName,
                'address1' => $contact->address1,
                'address2' => $contact->address2,
                'city' => $contact->city,
                'state' => $contact->state,
                'postCode' => $contact->postCode,
                'postalAddress1' => $contact->postalAddress1,
                'postalAddress2' => $contact->postalAddress2,
                'postalCity' => $contact->postalCity,
                'postalState' => $contact->postalState,
                'postalPostCode' => $contact->postalPostCode,
                'phone' => $contact->phone,
                'company' => $companies->count() == 1 ? $contact->company : '',
            ];

            
            $cin7_auth_username = SettingHelper::getSetting('cin7_auth_username');
            $cin7_auth_password = SettingHelper::getSetting('cin7_auth_password');

            $cin7api_key_for_other_jobs =  ApiKeys::where('password', $cin7_auth_password)
            ->where('is_active', 1)
            ->where('is_stop' , 0)
            ->first();

            $api_key_id = null;
            
            if (!empty($cin7api_key_for_other_jobs)) {
                $cin7_auth_username = $cin7api_key_for_other_jobs->username;
                $cin7_auth_password = $cin7api_key_for_other_jobs->password;
                $thresold = $cin7api_key_for_other_jobs->threshold;
                $request_count = !empty($cin7api_key_for_other_jobs->request_count) ? $cin7api_key_for_other_jobs->request_count : 0;
                $api_key_id = $cin7api_key_for_other_jobs->id;
            } else {
                Log::error('Cin7 API Key not found or inactive');
                return false;
            }

            $client = new \GuzzleHttp\Client();
            $response = $client->request('PUT', 'https://api.cin7.com/api/v1/Contacts', [
                'auth' => [$cin7_auth_username, $cin7_auth_password], // Authenticate with Cin7 API credentials
                'json' =>  [
                    $body
                ],
                'headers' => [
                    'Content-Type' => 'application/json' // Specify Content-Type header
                ]
            ]);

            UtilHelper::saveEndpointRequestLog('Sync Contacts' , "https://api.cin7.com/api/v1/Contacts/" , $api_key_id);
            
            $responseBody = $response->getBody()->getContents();
            $cin7_status = $response->getStatusCode();


            if ($cin7_status == 200) {
                // $contact_log = new ContactLogs();
                // $contact_log->user_id = $contact->user_id;
                // $contact_log->action_by = auth()->user()->id;
                // $contact_log->action = 'Updated';
                // $contact_log->description = !empty($contact->email) ? $contact->email . ' ' . 'is ' . 'updated by ' . auth()->user()->email . ' ' .'at'. ' '. now() : $contact->firstName .' '. $contact->lastName  . ' ' . 'is ' . 'updated by ' . auth()->user()->email . ' ' .'at'. ' '. now();
                // $contact_log->save();

                $contact = Contact::where('id' , $contact->id)->first();
                $user_log = new UserLog();
                $user_log->user_id = auth()->user()->id;
                $user_log->contact_id = !empty($contact->contact_id) ? $contact->contact_id : $contact->id;
                $user_log->secondary_id = !empty($contact->secondary_id) ? $contact->secondary_id : $contact->id;
                $user_log->action = 'Updation';
                $user_log->user_notes = !empty($contact->email) ? $contact->email . ' ' . 'is ' . 'updated by ' . auth()->user()->email . ' ' .'at'. ' '. now() : $contact->firstName .' '. $contact->lastName  . 'is ' . 'updated by ' . auth()->user()->email .' ' .'at'. ' '. now();
                $user_log->save();
            }
        }


        return response()->json(
            [
                'cin7_status' => $cin7_status,
                'status' => $response_status,
                'success' => true, 
                'data' => $responseBody ,
                'msg' => 'Address updated and synced Successfully'
            ]
        );
    }

    // add new address 
    public function add_new_address(Request $request) {
        $request->validate([
                'contact_id' => 'required',
                'first_name' => 'required',
                'phone' => 'required',
                'zip' => 'required',
                // 'address' => 'required',
                'state' => 'required',
                'city' => 'required',
                'address' => [
                    'required',
                    // function ($attribute, $value, $fail) {
                    //     if (preg_match('/^(P\.?\s*O\.?\s*Box)/i', trim($value))) {
                    //         $fail('Invalid address: PO Boxes are not allowed at the start.');
                    //     }
                    // },
                ],
                
            ],
            [
                'contact_id.required' => 'Primary Company is required',
                'first_name.required' => 'First name is required',
                // 'company_name.required' => 'Company is required',
                'phone.required' => 'Phone is required',
                'zip.required' => 'Postal code is required',
                'address.required' => 'Address is required',
                // 'city.required' => 'City is required',
                'state.required' => 'State is required',
                // 'country.required' => 'Country is required',
            ] 
        );

        

        $contact_id = $request->contact_id;
        $address_type = $request->type;
        $first_name = $request->first_name;
        $last_name = $request->last_name;
        $company = $request->company_name;
        $email = $request->email;
        $phone = $request->phone;
        $postal_code  = $request->zip;
        $address1 = $request->address;
        $address2 = $request->address2;
        $city = $request->city;
        $state = $request->state;
        $country = $request->country;


        $contact = Contact::where('contact_id', $contact_id)->first();
        if (empty($contact)) {
            return response()->json([
                'status' => '400',
                'msg' => 'Contact not found'
            ]);
        }



        $selectedChoice = $request->input('selected_shipping_choice_shipping') ?? null;
        if (!$selectedChoice || $selectedChoice !== 'entered') {
            $validate_address =  UserHelper::validateFullAddress($address1 , $address2 , $city , $state , $postal_code, $country = 'USA');

            if ($validate_address['valid'] == false) {
                return response()->json([
                    'status' => 'address_error',
                    'address_validator' => false,
                    'validator_message' => $validate_address['message'] ?? 'Address validation failed.',
                    'suggested_address' => $validate_address['suggested_address'] ?? '',
                    'formatted_address' => $validate_address['formatted_address'] ?? '',
                ], 400);
            }
        }


        $update_default_address = ContactsAddress::where('contact_id', $contact_id)
        ->where('address_type', $address_type)
        ->get();

        if (count($update_default_address) > 0) {
            foreach ($update_default_address as $update_default) {
                $update_default->is_default = 0;
                $update_default->save();
            }
        }



        $add_new_address = new ContactsAddress();
        $add_new_address->contact_id = $contact_id;
        $add_new_address->address_type = $address_type;
        $add_new_address->DeliveryFirstName = $first_name;
        $add_new_address->DeliveryLastName = $last_name;
        $add_new_address->DeliveryCompany = $company;
        $add_new_address->DeliveryPhone = $phone;
        $add_new_address->DeliveryZip = $postal_code;
        $add_new_address->DeliveryAddress1 = $address1;
        $add_new_address->DeliveryAddress2 = $address2;
        $add_new_address->DeliveryCity = $city;
        $add_new_address->DeliveryState = $state;
        $add_new_address->DeliveryCountry = $country;
        $add_new_address->is_default = 1;
        $add_new_address->save();


        return response()->json([
            'status' => '200',
            'msg' => 'Address added successfully'
        ]);
          
    }

    public function adminUsers(Request $request)
    {
        $data = User::role(['Admin'])->get();
        $count = $data->count();
        return view('admin/users/admin-users', compact('data', 'count'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function switch_user($id, $contactId , $admin_id)
    {
        
        Session::forget('contact_id');
        Session::forget('company');
        Session::forget('companies');
        Session::forget('cart');
        $switch_user = Auth::loginUsingId($id);
        $auth_user_email = $switch_user->email;
        if (!empty($admin_id)) {
            session()->put('admin_id_from_switching', $admin_id);
        }

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
            $active_company = !empty($contact->company) ? $contact->company : $contact->firstName . ' ' . $contact->lastName;

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
                $active_company = !empty($contact->company) ? $contact->company : $contact->firstName . ' ' . $contact->lastName;;
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
        
        $get_admin_id_from_session = session()->get('admin_id_from_switching');
        if (!empty($get_admin_id_from_session)) {
            $admin = User::role('Admin')->where('id' , $get_admin_id_from_session)->first();
            Auth::loginUsingId($admin->id);
            return redirect('admin/dashboard');
        }

        $admin = User::role('Admin')->first();
        Auth::loginUsingId($admin->id);
        session()->flash('logged_in_as_another_user', '');
        session()->forget('admin_id_from_switching');
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
        session()->put('admin_id_from_switching', $admin->id);
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
        $user_id = auth()->user()->id;
        $contact_id = $request->companyId;

        // Fetch the primary contact by contact_id
        $contact = Contact::where('contact_id', $contact_id)->first();
        $company_type = null;
        $active_contact_id = null;
        $active_company = null;
        $get_company_address = null;
        $cartItems = [];
        $cart_data = [];

        // Determine active contact (primary or secondary)
        if (!empty($contact)) {
            $active_contact_id = $contact->contact_id;
            $active_company = !empty($contact->company) ? $contact->company : $contact->firstName . ' ' . $contact->lastName;
            $company_type = 'primary';
        } else {
            $contact = Contact::where('secondary_id', $contact_id)->first();
            $active_contact_id = $contact->secondary_id;
            $active_company = !empty($contact->company) ? $contact->company : $contact->firstName . ' ' . $contact->lastName;
            $company_type = 'secondary';
        }

        // Store company details in session
        Session::put([
            'contact_id' => $active_contact_id,
            'company' => $active_company,
        ]);

        // Update all cart items with null contact_id to the selected company (active contact_id)
        Cart::where('user_id', $user_id)
            ->whereNull('contact_id')
            ->update(['contact_id' => $active_contact_id]);

        // Fetch the selected contact for the user
        $getSelectedContact = Contact::where('user_id', $user_id)
            ->where(function ($query) use ($active_contact_id) {
                $query->where('contact_id', $active_contact_id)
                    ->orWhere('secondary_id', $active_contact_id);
            })
            ->first();

        // Retrieve cart items, now with updated contact_id
        $cartItems = Cart::where('user_id', $getSelectedContact->user_id)
            ->where('contact_id', $active_contact_id) // Fetch only items with the current contact_id
            ->get();

        // Group the cart items by product and option_id for merging quantities
        $cartItemsGrouped = $cartItems->groupBy(function ($item) {
            return $item->product_id . '-' . $item->option_id;
        });

        $getPriceColumn = UserHelper::getUserPriceColumn(false, $getSelectedContact->user_id);

        // Process the cart items
        foreach ($cartItemsGrouped as $groupKey => $groupItems) {
            $cartItem = $groupItems->first();

            // Fetch the product pricing
            $productPricing = Pricingnew::where('option_id', $cartItem['option_id'])->first();
            $productPrice = $productPricing->$getPriceColumn ?? $productPricing['sacramentoUSD'] ?? $productPricing['retailUSD'] ?? 0;

            // Merge the quantities of grouped items
            $totalQuantity = $groupItems->sum('quantity');

            // Update or create cart item with the current active contact_id and price
            Cart::updateOrCreate(
                ['user_id' => $user_id, 'product_id' => $cartItem['product_id'], 'option_id' => $cartItem['option_id'], 'contact_id' => $active_contact_id],
                ['quantity' => $totalQuantity, 'price' => $productPrice]
            );

            // Store updated cart data for session
            $cart_data[$cartItem['qoute_id']] = [
                "product_id" => $cartItem['product_id'],
                "name" => $cartItem['name'],
                "quantity" => $totalQuantity,
                "price" => $productPrice,
                "code" => $cartItem['code'],
                "image" => $cartItem['image'],
                'option_id' => $cartItem['option_id'],
                "slug" => $cartItem['slug'],
                "qoute_id" => $cartItem['qoute_id'],
                "user_id" => $cartItem['user_id'],
                "contact_id" => $cartItem['contact_id'],
            ];

            // Delete the duplicate items (those after the first one in the group)
            Cart::where('user_id', $user_id)
                ->where('product_id', $cartItem['product_id'])
                ->where('option_id', $cartItem['option_id'])
                ->where('contact_id', $active_contact_id)
                ->where('id', '!=', $cartItem['id']) // Exclude the updated one
                ->delete();
        }

        // Store updated cart in session
        Session::put('cart', $cart_data);

        // Get company address based on primary or secondary contact
        if ($company_type == 'primary') {
            $get_company_address = Contact::where('contact_id', $active_contact_id)->first();
        } elseif ($company_type == 'secondary') {
            $secondary_contact_user = Contact::where('secondary_id', $active_contact_id)->first();
            $get_company_address = Contact::where('contact_id', $secondary_contact_user->parent_id)->where('is_parent', 1)->first();
        }

        return response()->json([
            'status' => '204',
            'message' => 'Company Switch Successfully!',
            'success' => true,
            'update_address' => $get_company_address,
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
        $active_company = !empty($contact->company) ? $contact->company : $contact->firstName . ' ' . $contact->lastName;
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
        // Retrieve the user first
        $user = User::with('guest_contact')
        ->where('email', $request->email)->first();



        if (!$user) {
            return redirect()->back()->withErrors(['email' => 'User not found.']);
        }

        $request->validate([
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password'
        ]);

        // Update user fields
        $user->update([
            'password' => bcrypt($request->password),
            'is_updated' => 1,
            'hash' => null,
            // 'updated_at' => Carbon::now()
        ]);


        if (!empty($user->guest_contact)) {
            $update_guest_contact = Contact::where('user_id', $user->id)
            ->where('is_guest', 1)
            ->first();

            if (!empty($update_guest_contact)) {
                $update_guest_contact->user_id = $user->id;
                $update_guest_contact->is_guest = 0;
                $update_guest_contact->save();
            }
        }

        // Log in the user
        Auth::loginUsingId($user->id);

        // if ($request->session()->has('cart_hash')) {
        //     $cart_hash = $request->session()->get('cart_hash');
        //     $cart_items = Cart::where('cart_hash', $cart_hash)->where('is_active', 1)->where('user_id', 0)->get();
        //     foreach ($cart_items as $cart_item) {
        //         $cart_item->user_id = auth()->user()->id;
        //         $cart_item->save();
        //     }
        // }

        // // Handle company switch logic
        // $companies = Contact::where('user_id', $user->id)->get();
        // if (count($companies) > 0) {
        //     if ($companies[0]->contact_id == null) {
        //         UserHelper::switch_company($companies[0]->secondary_id);
        //     } else {
        //         UserHelper::switch_company($companies[0]->contact_id);
        //     }
        //     Session::put('companies', $companies);
        // }



        $user_id = auth()->user()->id;
        
        if ($user->hasRole(['Newsletter']) || $user->hasRole(['Sale Payments']) || $user->hasRole(['Payouts'])) {
            session()->flash('message', 'Successfully Logged in');
            return redirect()->route('newsletter_dashboard');
        }


        if ($request->session()->has('cart_hash')) {
            $cart_hash = $request->session()->get('cart_hash');
            $cart_items = Cart::where('cart_hash', $cart_hash)->where('is_active', 1)->where('user_id', 0)->get();
            foreach ($cart_items as $cart_item) {
                $cart_item->user_id = $user_id;
                $cart_item->save();
            }
        }

        
        if ($user->hasRole(['Admin'])) {
            // session()->flash('message', 'Successfully Logged in');
            $companies = Contact::where('user_id', auth()->user()->id)->get();

            if ($companies->count() == 1) {
                if ($companies[0]->contact_id == null) {
                    UserHelper::switch_company($companies[0]->secondary_id);
                } else {
                    UserHelper::switch_company($companies[0]->contact_id);
                }
            }
            if ($companies->count() > 1) {
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
            $admin = true;
        } 
        else {
            $companies = Contact::where('user_id', auth()->user()->id)->get();
            if ($companies->count() == 1) {
                if ($companies[0]->contact_id == null) {
                    UserHelper::switch_company($companies[0]->secondary_id);
                } else {
                    UserHelper::switch_company($companies[0]->contact_id);
                }
            }
            if ($companies->count() > 1) {
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

            
            if ($user->is_updated == 1) {

                $companies = Contact::where('user_id', auth()->user()->id)->get();
                if ($companies->count() == 1) {
                    if ($companies[0]->contact_id == null) {
                        UserHelper::switch_company($companies[0]->secondary_id);
                    } else {
                        UserHelper::switch_company($companies[0]->contact_id);
                    }
                }

                if ($companies->count() > 1) {
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

                $previousUrl = session('previous_url', '/'); 
            } 
            else {
                $session_contact_id = null;
                $companies = Contact::where('user_id', auth()->user()->id)->get();
                if ($companies->count() == 1) {
                    if ($companies[0]->contact_id == null) {
                        UserHelper::switch_company($companies[0]->secondary_id);
                    } else {
                        UserHelper::switch_company($companies[0]->contact_id);
                    }
                }
                if ($companies->count() > 1) {
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
            }

            
            Session::put('companies', $companies);
        }

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
        if (empty($user)) {
            Auth::logout();
            return redirect()->route('user')->with('error', 'Invalid Link ! Please try again. '  );
        } 
        elseif (!empty($user)) {
            $expire_date = !empty($user->hash_date) ? $user->hash_date : $user->updated_at;
            $expiration = config('auth.passwords.users.expire');
            $tokenCreationTime = Carbon::parse($expire_date);
            $tokenExpirationTime = $tokenCreationTime->addMinutes($expiration);
            if (Carbon::now()->greaterThan($tokenExpirationTime)) {
                return redirect()->route('lost.password')->with('error', 'Your link has been expired . Please try again.');
            } else {
                // Auth::login($user);
                return view('reset-password', compact('user'));
            }
    
        } 

    }

    public function account_profile_update(Request $request)
    {
        $Validated = $request->validate([
            // 'email' => 'required|email|unique:users,email,' . $request->id,
            'firstName' => 'required',
            'phone' => 'required',
        ]);

        if ($request->password) {
            $Validated = $request->validate([
                // 'password' => 'required|min:6',
                'password_confirmation' => 'required_with:password|same:password'
            ]);
        }

        if ($request->password_confirmation) {
            $Validated = $request->validate(
                [
                'password' => 'required|min:6',
                // 'password_confirmation' => 'required_with:password|same:password'
                ], 
                [
                    'password.required' => 'Password is required',
                ]
            );
        }

        $user_id = $request->id;
        $user_profile = User::find($user_id);
        // $user_profile->email = $request->input('email');
        $user_profile->password = Hash::make($request->input('password'));
        $user_profile->save();
        $user_profile_contact = Contact::where('user_id', $user_id)->first();
        $user_profile_contact->firstName = $request->input('firstName');
        $user_profile_contact->lastName = $request->input('lastName');
        $user_profile_contact->phone = $request->input('phone');
        $user_profile_contact->save();

        $user_log = new UserLog();
        $user_log->user_id = auth()->user()->id;
        $user_log->contact_id = !empty($user_profile_contact->contact_id) ? $user_profile_contact->contact_id : $user_profile_contact->id;
        $user_log->secondary_id = !empty($user_profile_contact->secondary_id) ? $user_profile_contact->secondary_id : $user_profile_contact->id;
        $user_log->action = 'Updation';
        $user_log->user_notes = !empty($user_profile_contact->email) ? $user_profile_contact->email . ' ' . 'is ' . 'updated by ' . auth()->user()->email . ' ' .'at'. ' '. now() : $user_profile_contact->firstName .' '. $user_profile_contact->lastName  . 'is ' . 'updated by ' . auth()->user()->email .' ' .'at'. ' '. now();
        $user_log->save();

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

    public function thankyou_for_creating_account() {
        return view('thankyou_for_creating_account');
    }
}
