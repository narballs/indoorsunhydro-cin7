<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Contact;
use App\Models\ApiOrder;
use App\Models\ApiOrderItem;
use App\Models\UsState;
use App\Models\UsCity;
use App\Models\BuyList;
use App\Models\Product;
use App\Models\SecondaryContact;
use App\Http\Requests\Users\UserSignUpRequest;
use App\Http\Requests\Users\CompanyInfoRequest;
use App\Http\Requests\Users\UserAddressRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\Subscribe;
use App\Helpers\MailHelper;
use App\Jobs\SyncContacts;
use Session;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use URL;
use Carbon\Carbon;
use App\Helpers\UtilHelper;



class UserController extends Controller
{
    function __construct()

    {
        $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:user-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
        $this->middleware('permission:user-show', ['only' => ['show']]);

        //$this->middleware(['role:Admin','permission:user-list']);

        // $this->middleware(['role:Admin','permission:user-list|user-list']);
        // $this->middleware(['role:users','permission:user-list|user-list']);
        //$this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index','show']]);
        //$this->middleware('permission:user-list', ['only' => ['index']]);
        // $this->middleware('permission:users-edit', ['only' => ['edit','update']]);
        // $this->middleware('permission:users-delete', ['only' => ['destroy']]);
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
        $usersData = $request->usersData;
        $secondaryUser = $request->secondaryUser;
        // $cin7Merged = $request->get('cin7-merged');

        $user_query = User::orderBy('id', 'DESC')->with('contact.secondary_contact');
        if (!empty($usersData)) {
            if ($usersData == 'admin-user') {
                $user_query = $user_query->role(['admin']);
            } elseif ($usersData == 'cin7-merged') {
                $user_query = $user_query;
                $user_query = $user_query->whereHas('contact', function ($query) {
                    $query = $query->whereNotNull('contact_id');
                })->with('contact');
            } elseif ($usersData == 'not-merged') {
                $user_query = $user_query;
                $user_query = $user_query->whereHas('contact', function ($query) {
                    $query = $query->whereNull('contact_id');
                })->with('contact');
            }
        }

        if (!empty($secondaryUser)) {
            if ($secondaryUser == 'secondary-user') {
                $user_query = SecondaryContact::where('parent_id', '!=', NULL);
                $data =  $user_query->paginate(10);
                return view('admin.users.admin_secondary_contact', compact('data', 'secondaryUser'))
                    ->with('i', ($request->input('page', 1) - 1) * 10);
            }
            if ($secondaryUser == 'primary-user') {
                $user_query = $user_query->whereHas('contact', function ($query) {
                    $query = $query->where('is_parent', 1);
                });
            }
        }

        if (!empty($search)) {
            $user_query = $user_query->where('first_name', 'LIKE', '%' . $search . '%')
                ->orWhere('last_name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhereHas('contact', function ($query) use ($search) {
                    $query->where('company', 'LIKE', '%' . $search . '%')
                        ->orWhere('contact_id', 'LIKE', '%' . $search . '%');
                });

        }

        $data = $user_query->paginate(10);
        $users = User::role(['Admin'])->get();
        $count = $users->count();
        return view('admin.users.index', compact('data', 'count', 'search', 'usersData', 'secondaryUser'))
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
        return view('admin.users.create', compact('roles'));
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

        return redirect()->route('users.index')
            ->with('success', 'User created successfully');
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
        $user = User::find($id);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();

        return view('admin.users.edit', compact('user', 'roles', 'userRole'));
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
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }

        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id', $id)->delete();

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
        // dd(auth()->attempt($credentials));

        if (auth()->attempt($credentials)) {
            if ($user->hasRole(['Admin'])) {
                session()->flash('message', 'Successfully Logged in');
                return redirect()->route('admin.view');
            } else {
                // dd(session()->get('cart'));
                if (!empty(session()->get('cart'))) {
                    return redirect()->route('cart');
                } else {
                    return redirect()->route('my_account');
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
            return response()->json(['success' => true, 'created' => true, 'msg' => 'Welcome, new player.']);
        } else {
            $user = User::latest()->first();
            $user_id = $user->id;
            $user_Update = User::where("id", $user_id)->update([
                "first_name" => $request->get('first_name'),
                "last_name" => $request->get('last_name'),
                "password" => bcrypt($request->get('password'))
            ]);
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


            // $validatedData['password'] = bcrypt($validatedData['password']);
            // $validateData['first_name'] = $contact->firstName;
            // $validateData['last_name'] = $contact->lastName;


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

        return redirect()->route('user');
    }

    public function save_contact(CompanyInfoRequest $request)
    {
        $user = User::latest()->first();
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
            $data = [
                'contact_name' => $user->first_name,
                'name' =>  'Admin',
                'contact_email' => $user->email,
                'subject' => 'New User Registration Request',
                'from' => 'noreply@indoorsunhydro.com',
                'content' => 'New user registration request received, please review.'
            ];


            if (!empty($users_with_role_admin)) {
                foreach ($users_with_role_admin as $role_admin) {
                    $data['email'] = $role_admin->email;
                    $adminTemplate = 'emails.approval-notifications';
                    MailHelper::sendMailNotification('emails.new-registration-notification', $data);
                }
            }
            $contact->save();
        } else {

            $states = UsState::where('id', $request->state_id)->first();
            $state_name = $states->state_name;
            $cities = UsCity::where('id', $request->city_id)->first();
            $city_name = $cities->city;

            $contact = Contact::where('user_id', $user_id)->first()->update(
                [
                    'postalAddress1' => $request->input('street_address'),
                    // 'postalAddress2' => $request->input('suit_apartment'),
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

    public function my_account(Request $request)
    {

        $user_id = auth()->id();
        if (!$user_id) {
            return redirect('/user/');
        }

        $user = User::where('id', $user_id)->first();
      
        $user_address = Contact::where('user_id', $user_id)->first();
        $childerens = Contact::where('user_id', $user_id)->with('secondary_contact')->first();
        $list = BuyList::where('id', 20)->with('list_products.product.options')->first();

        $contact = SecondaryContact::where('email', $user_address->email)->first();
        
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

        return view('my-account', compact('user', 'user_address', 'states', 'childerens', 'parent'));
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
        $list = BuyList::where('user_id', $user_id)->where('id', $id)->where('type', 'qoute')->with('list_products.product.options')->first();

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
        $switch_user = Auth::loginUsingId($id);
        $auth_user_email = $switch_user->email;
        session()->put('logged_in_as_another_user', $auth_user_email);
        Auth::loginUsingId($id);
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
        $user_id = auth()->user()->id;
        $contact = Contact::where('user_id', $user_id)->first();
        $contactId = $contact->contact_id;

        $request->validate([
            'email' => 'required|email|unique:secondary_contacts,email',
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

        SecondaryContact::create($secondary_contact_data);

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
        $secondary_contact = SecondaryContact::where('email', $request->email)->update(
            [
                'hashKey' => $sig,
                'hashUsed' => 0,
            ]
        );
        $data = [
            'email' => $request->email,
            'subject' => 'Customer Registration Invitation',
            'from' => env('MAIL_FROM_ADDRESS'),
            'content' => 'Customer Registration Invitation',
            'url' => $url
        ];

        MailHelper::sendMailNotification('emails.invitaion-emails', $data);
        SyncContacts::dispatch('update_contact', $contact)->onQueue(env('QUEUE_NAME'));

        $secondary_contacts = SecondaryContact::where('parent_id', $contactId)->orderBy('id', 'desc')->get();
        return view('secondary-user', compact('secondary_contacts'));
    }
}