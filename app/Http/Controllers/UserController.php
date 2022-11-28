<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Contact;
use App\Models\ApiOrder;
use App\Models\ApiOrderItem;
use App\Models\State;
use App\Http\Requests\Users\UserSignUpRequest;
use App\Http\Requests\Users\CompanyInfoRequest;
use App\Http\Requests\Users\UserAddressRequest;
use Session;
use Auth;



class UserController extends Controller
{
    public function userRegistration() {
        $states = State::all();
        //dd($states);
        return view('user-registration-second', compact('states'));
    }

    public function process_login(Request $request) {
           $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $credentials = $request->except(['_token']);
        $user = User::where('email',$request->email)->first();

        if (auth()->attempt($credentials)) {
            if ($user->isAdmin) {
                session()->flash('message', 'Successfully Logged in');
                return redirect()->route('admin.view');
            }
            else {
                // dd(session()->get('cart'));
                if (!empty(session()->get('cart'))) {
                    return redirect()->route('cart');
                }
                else {
                    return redirect()->route('my_account');
                } 
            }

        }else{
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
            return response()->json(['success' => true, 'created'=> true, 'msg' => 'Welcome, new player.']);
        }
        else {
            $user = User::latest()->first();
            $user_id = $user->id;
            $user_Update = User::where("id", $user_id)->update
                        ([
                            "first_name" => $request->get('first_name'),
                            "last_name" => $request->get('last_name'),
                            "password" => bcrypt($request->get('password'))
                        ]);
        }
        
        return response()->json(['success' => true, 'created'=> true, 'msg' => 'Welcome, new player.']);
  
    }

    public function logout()
    {
        \Auth::logout();

        return redirect()->route('user');
    }

    public function save_contact(CompanyInfoRequest $request) {
        $user = User::latest()->first();
        $user_id = $user->id;
        Auth::loginUsingId($user_id);
        if (!empty($request->input('company_website'))) {
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
            $contact->save();
        }
        else {
            $contact = Contact::where('user_id', $user_id)->first()->update(
                [
                    'postalAddress1' => $request->input('street_address'),
                    'postalAddress2' => $request->input('suit_apartment'),
                    'postalCity' => $request->input('town_city'),
                    'postalState' => $request->input('state'),
                    'postalPostCode' => $request->input('zip')
                ]
            );
        }
        return response()->json(['success' => true, 'created'=> true, 'msg' => 'Welcome, new player.']);
    }

    public function my_account(Request $request) {
        // $user = User::latest()->first();
        // $user_id = $user->id;
        // Auth::loginUsingId($user_id);
      
        $user_id = auth()->id();
        if (!$user_id) {
            return redirect('/user/');
        }
        $user = User::where('id', $user_id)->first();
        $user_address = Contact::where('user_id', $user_id)->first();
        $states = State::all();
        if ($request->ajax()) {
            $user_orders = ApiOrder::where('user_id', $user_id)->with('apiOrderItem')->get();
            foreach ($user_orders as $user_order) {
                $createdDate = $user_order->created_at;
                $user_order->createdDate = $createdDate->format('F \  j, Y');
            }
            
            return $user_orders;
        }
        return view('my-account', compact('user', 'user_address', 'states'));
    }

    public function user_order_detail($id) {
        $user_id = auth()->id();
        // $user_info = User::where('user_')
        $user_order = ApiOrder::where('id', $id )->first();
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

    public function user_addresses(Request $request) {
           $request->validate([
            'first_name' => 'required|regex:/^[a-zA-Z ]*$/',
            'last_name' => 'required|regex:/^[a-zA-Z ]*$/',
            'company_name' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            'address' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            'address2' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            'town_city'=> 'required|alpha',
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
        return response()->json(['success' => true, 'created'=> true, 'msg' => 'Address updated Successfully']);
        // echo $user_id;exit;
        // //dd
        // //$user_data = Contact::where('user_id', $user_id)->first();
        // dd($user_data);
        // $orderitems = ApiOrderItem::where('order_id', $id)->with('product')->get();
        // $data = [
        //     'user_order'  =>  $user_order,
        //     'order_items' =>  $orderitems
        // ];
        // return $data;
    }
}
