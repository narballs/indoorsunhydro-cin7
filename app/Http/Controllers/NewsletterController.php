<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\NewsletterSubscription;
class NewsletterController extends Controller
{
    public function newsletter_dashboard (Request $request)
    {
        $user_id = Auth::id();
        
        $admin_users = DB::table('model_has_roles')->where('role_id', 1)->pluck('model_id')->toArray();
        $newsletter_users = DB::table('model_has_roles')->where('role_id', 5)->pluck('model_id')->toArray();
        
        if (in_array($user_id, $admin_users) || in_array($user_id, $newsletter_users)) {
            return view('newsletter_layout.dashboard');
        } else {
            return redirect()->route('home');
        }
    }
    public function newsletter_subscriptions (Request $request)
    {
        $user_id = Auth::id();
        
        $admin_users = DB::table('model_has_roles')->where('role_id', 1)->pluck('model_id')->toArray();
        $newsletter_users = DB::table('model_has_roles')->where('role_id', 5)->pluck('model_id')->toArray();
        
        if (in_array($user_id, $admin_users) || in_array($user_id, $newsletter_users)) {
            $newsletter_subscriptions = NewsletterSubscription::orderBY('created_at', 'DESC')->paginate(3);
            return view('newsletter_layout.newsletter_subscribers.index', compact('newsletter_subscriptions'));
        } else {
            return redirect()->route('home');
        }
    }
}