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
use \Illuminate\Support\Str;
use \Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SalesOrders;

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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page = $request->page;
        $search = $request->search;
        $primaryUserSearch = $request->primaryUserSearch;
        $secondaryUserSearch = $request->secondaryUserSearch;
        $usersData = $request->usersData;
        $secondaryUser = $request->secondaryUser;
        $user_query = User::orderBy('id', 'DESC')->with('contact');
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
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhereHas('contact', function ($query) use ($search) {
                    $query->where('contact_id', 'like', '%' . $search . '%')
                        ->orWhere('company', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('firstName', 'like', '%' . $search . '%')
                        ->orWhere('lastName', 'like', '%' . $search . '%');
                });
            // dd($user_query->get());
        }

        $data = $user_query->paginate(10);
        $users = User::role(['Admin'])->get();
        $count = $users->count();
        return view('admin.users.index', compact(
            'data',
            'count',
            'search',
            'usersData',
            'secondaryUser'
        ))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();
        return view('admin.users.create', compact(
            'roles'
        ));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->middleware('permission:user-edit', ['only' => ['edit', 'update']]);
        $user = User::where('id', $id)->with('contact')->first();
        //dd($user);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();
        return view('admin.users.edit', compact(
            'user',
            'roles',
            'userRole'
        ));
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
        $this->validate($request, [
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'same:confirm-password',
            'roles' => 'required',
            'companies' => 'required'
        ]);
        $companies = $request->companies;
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
        
        foreach($companies as $company) {
            DB::table('model_has_roles')->insert(
                array(
                    'role_id' => 5,
                    'model_type' => 'App\Models\User', 
                    'model_id' => $id,
                    'company' => $company
                )
            );
          
        }
        $user->assignRole($request->input('roles'));
        return redirect()->route('users.index')
            ->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully');
    }
    public function userRegistration()
    {
        $data['states'] = UsState::get(["state_name", "id"]);
        return view('user-registration-second',  $data);
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
        if (auth()->attempt($credentials)) {
            if ($user->hasRole(['Admin'])) {
                session()->flash('message', 'Successfully Logged in');
                $companies = Contact::where('user_id', auth()->user()->id)->get();
                Session::put('companies', $companies);
                return redirect()->route('admin.view');
            } else {
                if (!empty(session()->get('cart'))) {
                    return redirect()->route('cart');
                } else {
                    if ($user->is_updated == 1) {
                        $companies = Contact::where('user_id', auth()->user()->id)->get();
                        Session::put('companies', $companies);
                        return redirect()->route('my_account');
                    } else {
                        $companies = Contact::where('user_id', auth()->user()->id)->get();
                        Session::put('companies', $companies);
                        return view('reset-password', compact('user'));
                    }
                }
            }
        } else {
            session()->flash('message', 'Invalid credentials');
            return redirect()->back();
        }
    }

    public function process_signup(UserSignUpRequest $request)
    {
        if ($request->get('email')) {
            $user = User::create([
                'email' => strtolower($request->get('email'))
            ]);
            return response()->json([
                'success' => true,
                'created' => true,
                'msg' => 'Welcome, new player.'
            ]);
        } else {
            $user = User::latest()->first();
            $user_id = $user->id;
            $registering_email = $user->email;
            $existing_contacts = Contact::where('email', $registering_email)->get();
            if ($existing_contacts->isNotEmpty()) {
                $user_Update = User::where("id", $user_id)->update([
                    "password" => bcrypt($request->get('password'))
                ]);
            } else {
                $user_Update = User::where("id", $user_id)->update([
                    "first_name" => $request->get('first_name'),
                    "last_name" => $request->get('last_name'),
                    "password" => bcrypt($request->get('password'))
                ]);
            }

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
                }
                return response()->json([
                    'code' => 201,
                    'success' => true,
                    'updated' => true,
                    'msg' => 'Existing contact updated'
                ]);
            }
        }

        return response()->json(['success' => true, 'created' => true, 'msg' => 'Welcome, new player.']);
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
        Auth::logout();
        Session::forget('contact_id');
        Session::forget('company');
        Session::forget('companies');
        Session::forget('logged_in_as_another_user');
        return redirect()->route('user');
    }

    public function save_contact(CompanyInfoRequest $request)
    {
        $user = User::latest()->first();
        $registering_email = $user->email;
        // serach weather contact already exist;
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
            }
            return response()->json([
                'code' => 201,
                'success' => true,
                'updated' => true,
                'msg' => 'Existing contact updated'
            ]);
        } else {
            $user_id = $user->id;
            Auth::loginUsingId($user_id);
            if (!empty($request->input('phone'))) {
                $contact = new Contact;
                $contact->website = $request->input('company_website');
                $contact->company = $request->input('company_name');
                $contact->phone = $request->input('phone');
                $contact->status = 0;
                $contact->priceColumn = 'RetailUSD';
                $contact->user_id = $user_id;
                $contact->firstName = $user->first_name;
                $contact->type = 'Customer';
                $contact->lastName = $user->last_name;
                $contact->email = $user->email;

                $admin_users =  DB::table('model_has_roles')->where('role_id', 1)->pluck('model_id');
                $admin_users = $admin_users->toArray();

                $users_with_role_admin = User::select("email")
                    ->whereIn('id', $admin_users)
                    ->get();

                $user_log = UserLog::create([
                    'user_id' => $user->id,
                    'action' => 'Singup',
                    'user_notes' => 'Contact do not exist in Cin7. Awaiting approval from admin to assign role ' . Carbon::now()->toDateTimeString()
                ]);
                $contact->save();
            } else {
                $states = UsState::where('id', $request->state_id)->first();
                $state_name = $states->state_name;
                $cities = UsCity::where('id', $request->city_id)->first();
                $city_name = $cities->city;
                $contact = Contact::where('user_id', $user_id)->first()->update(
                    [
                        'postalAddress1' => $request->input('street_address'),
                        'postalState' => $state_name,
                        'postalCity' => $city_name,
                        'postalPostCode' => $request->input('zip')
                    ]
                );
            }
            return response()->json([
                'success' => true,
                'created' => true,
                'msg' => 'Welcome, new player.'
            ]);
        }
    }

    public function my_account(Request $request)
    {
        if ($request->ajax()) {
            $user_id = auth()->id();
               $companies = Contact::where('user_id', auth()->user()->id)->get();
               $user = User::where('id', $user_id)->first();
               $can_approve_order = $user->hasRole('Order Approver');

               $all_ids = UserHelper::getAllMemberIds($user);
               $contact_ids = Contact::whereIn('id', $all_ids)
               ->pluck('contact_id')
               ->toArray();
              

                // $user_orders = ApiOrder::where('user_id', $user_id)
                //     ->with('contact')
                //     ->with('apiOrderItem')
                //     ->orderBy('id', 'desc')
                //     ->get();

                $user_orders = ApiOrder::whereIn('memberId', $contact_ids)
                    ->with('contact')
                    ->with('apiOrderItem')
                    ->orderBy('id', 'desc')
                    ->get();    
                

                foreach ($user_orders as $user_order) {
                    $createdDate = $user_order->created_at;
                    $user_order->createdDate = $createdDate->format('F \  j, Y');
                }

                $response = [
                    'can_approve_order' => $can_approve_order,
                    'user_orders' => $user_orders
                ];

                return $response;

                return $user_orders;

            
        }
        if ($request->ajax()) {
            $companies = Contact::where('user_id', auth()->user()->id)->get();
            return response()->json([
                'message' => 'success',
                'companies' => $companies
            ]);
        } else {
            $user_id = auth()->id();
            if (!$user_id) {
                return redirect('/user/');
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

                // return response()->json([
                //     'data' => $user_order 

                // ]);
            }

            return view('my-account', compact(
                'user',
                'user_address',
                'states',
                'secondary_contacts',
                'parent',
                'companies'
            ));
        }
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

    public function user_addresses(Request $request)
    {
        $request->validate([
            'first_name' => 'required|regex:/^[a-zA-Z ]*$/',
            'last_name' => 'required|regex:/^[a-zA-Z ]*$/',
            'company_name' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            'address' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            'address2' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            'town_city' => 'required|alpha',
            'state' => 'required|alpha',
            'zip' => 'required|regex:/^\d{5}(?:[- ]?\d{4})?$/s',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10'
        ]);

        $user_id = auth()->id();
        $contact = Contact::where('user_id', $user_id)->first();
        if ($contact) {
            $contact->update(
                [
                    'firstName' => request('first_name'),
                    'lastName' => request('last_name'),
                    'postalAddress1' => request('address'),
                    'postalAddress2' => request('address2'),
                    'company' => request('company_name'),
                    'postalState' => request('state'),
                    'phone' => request('phone'),
                    'postalCity' => request('town_city'),
                    'postalPostCode' => request('zip'),
                    'email' => request('email')
                ]
            );
        }
        return response()->json(['success' => true, 'created' => true, 'msg' => 'Address updated Successfully']);
    }

    public function adminUsers(Request $request)
    {
        $data = User::role(['Admin'])->get();
        $count = $data->count();
        return view('admin/users/admin-users', compact('data', 'count'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function switch_user($id)
    {
        Session::forget('contact_id');
        Session::forget('company');

        $switch_user = Auth::loginUsingId($id);
        $auth_user_email = $switch_user->email;
        session()->put('logged_in_as_another_user', $auth_user_email);
        Auth::loginUsingId($id);
        $contact_id = auth()->user()->id;
        $contact = Contact::where('user_id', $contact_id)->first();
        $companies = Contact::where('user_id', auth()->user()->id)->get();
        if (!empty($contact)) {
            $active_contact_id = $contact->contact_id;
            $active_company = $contact->company;
            Session::put([
                'contact_id' => $active_contact_id,
                'company' => $active_company
            ]);
            Session::put('companies', $companies);
            return redirect('/');
        } else {
            $contact = Contact::where('secondary_id', $contact_id)->first();
            $active_contact_id = $contact->secondary_id;
            $active_company = $contact->company;
            Session::put([
                'contact_id' => $active_contact_id,
                'company' => $active_company
            ]);
            Session::put('companies', $companies);
            return redirect('/');
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
        // $secondary_contact = Contact::where('email', $request->email)->update(
        //     [
        //         'hashKey' => $sig,
        //         'hashUsed' => 0,
        //     ]
        // );
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
        // $secondary_contacts = Contact::where('parent_id', $contactId)->orderBy('id', 'desc')->get();

        // return view('secondary-user', compact('secondary_contacts', 'url'));
    }
    public function delete_secondary_user(Request $request)
    {
        $id = $request->id;
        $secondary_contact = Contact::find($id);
        $secondary_contact->delete();
    }

    public function switch_company(Request $request)
    {
        $contact_id = $request->companyId;
        $contact = Contact::where('contact_id', $contact_id)->first();
        if (!empty($contact)) {
            $active_contact_id = $contact->contact_id;
            $active_company = $contact->company;
            Session::put([
                'contact_id' => $active_contact_id,
                'company' => $active_company
            ]);
            return response()->json([
                'status' => '204',
                'message' => 'Company Switch Successfully !'
            ]);
        } else {
            $contact = Contact::where('secondary_id', $contact_id)->first();
            $active_contact_id = $contact->secondary_id;
            $active_company = $contact->company;
            Session::put([
                'contact_id' => $active_contact_id,
                'company' => $active_company
            ]);
            return response()->json([
                'status' => '204',
                'message' => 'Company Switch Successfully !'
            ]);
        }
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

        $data['email'] = $user->email;
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

    public function user_order_approve(Request $request) {
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
                "logisticsCarrier" => "",
                "logisticsStatus" => 1,
                "distributionBranchId" => 0,
                "lineItems" => $lineItems

            ],
        ];
        SalesOrders::dispatch('create_order', $order)->onQueue(env('QUEUE_NAME'));
        //$currentOrder = ApiOrder::where('id', $order_id)->first();

        return response()->json(['success' => true]);
    }

    public function verify_order(Request $request) {
        $order_id = $request->order_id;
        $order = ApiOrder::where('id', $order_id)->first();
        return $data = $order;
    }

    public function send_order_approval_email(Request $request) {
        $order = ApiOrder::where('id', $request->order_id)->with('contact')->with('apiOrderItem')->first();
        $data['email'] = $order->contact->email;
        // $data['addresses'] = $addresses;
        $data['order'] =  $order;
        $data['content'] = 'Order Approved';
        $data['subject'] = 'Order Approved';
        $data['from'] = env('MAIL_FROM_ADDRESS');
        MailHelper::sendMailNotification('emails.order-approver-email', $data);
       
    }

    public function chooise_companie(Request $request) {
        if ($request->ajax()) {
              $companies = Contact::where('user_id', auth()->user()->id)->get();
                return response()->json([
                    'message' => 'success',
                    'companies' => $companies
                ]);
     }      }
}
