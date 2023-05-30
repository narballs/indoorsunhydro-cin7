<?php
   
namespace App\Http\Controllers;
   
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Contact;
  
class ChangePasswordController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
   
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){

    }
    // {
    //     return view('changePassword');
    // } 
   
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function store(Request $request)
    {

        if (Auth::check())
        {
        $userId = Auth::id();
        }
        $user = User::findOrFail($userId);
        $contact = Contact::where('user_id', $user->id)->first();
        /*
        * Validate all input fields
        */
        //dd($request->input('current_password'));
        if ($request->input('current_password') == null) {
            $this->validate($request, [
                'current_password' => 'required',
                'new_password' => 'required',
                'new_confirm_password' => 'same:new_password',
            ]);
        }
        else {
            $this->validate($request, [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required'
             ]);
        }
       
        $old_password = $request->current_password;
        if ($request->current_password) {
            if (Hash::check($old_password, $user->password)) { 
               $user->fill([
                'password' => Hash::make($request->new_password)
                ])->save();
               return response()->json(['success' => true, 'created'=> true, 'msg' => 'Password change successfully.']);
            } else {
                $request->session()->flash('error', 'Password does not match');
                return response()->json(['success' => false, 'updated'=> false, 'msg' => 'Password does not match']);
            }
        }
        else {
            $user->first_name = $request->first_name;
            $user->last_name  = $request->last_name;
            $user->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email
                ]);
            $contact->update([
                'firstName' => $request->first_name,
                'lastName' => $request->last_name,
                'email' => $request->email
                ]);
            return response()->json(['success' => true, 'created'=> true, 'msg' => 'User updated successfully.']);
        }
    }
}