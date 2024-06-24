<?php
namespace App\Http\Controllers;

use App\Mail\NewsletterMail;
use App\Models\NewsletterSubsciberTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\NewsletterSubscription;
use App\Models\NewsletterTemplate;
use Illuminate\Support\Facades\Mail;

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


    public function showAssignForm()
    {
        $subscribers = NewsletterSubscription::all();
        $templates = NewsletterTemplate::all();
        
        return view('newsletter_layout.newsletter_templates.assign_template', compact('subscribers', 'templates'));
    }

    public function assignTemplates(Request $request)
    {
        $request->validate([
            'subscriber_id' => 'required|exists:newsletter_subscriptions,id',
            'template_id' => 'required|exists:newsletter_templates,id',
        ]);

        $subscriber = NewsletterSubscription::findOrFail($request->subscriber_id);
        $subscriber->templates()->attach($request->template_id);

        return redirect()->back()->with('success', 'Template assigned to subscriber successfully!');
    }

    public function view_assigned_templates()
    {
        $assigned_templates = NewsletterSubsciberTemplate::with('subscriber', 'template')->get();
        return view('newsletter_layout.newsletter_templates.view_assigned_templates', compact('assigned_templates'));
    }

    public function delete_assigned_template($id)
    {
        $assigned_template = NewsletterSubsciberTemplate::findOrFail($id);
        $assigned_template->delete();

        return redirect()->back()->with('success', 'Assigned template removed successfully!');
    }

    public function edit_assigned_template($id) {
        $assigned_template = NewsletterSubsciberTemplate::findOrFail($id);
        $subscribers = NewsletterSubscription::all();
        $templates = NewsletterTemplate::all();

        return view('newsletter_layout.newsletter_templates.edit_assigned_template', compact('assigned_template', 'subscribers', 'templates'));
    }

    public function update_assigned_template(Request $request, $id)
    {
        $request->validate([
            'subscriber_id' => 'required|exists:newsletter_subscriptions,id',
            'template_id' => 'required|exists:newsletter_templates,id',
        ]);

        $assigned_template = NewsletterSubsciberTemplate::findOrFail($id);
        $assigned_template->update([
            'newsletter_subscription_id' => $request->subscriber_id,
            'newsletter_template_id' => $request->template_id,
        ]);

        return redirect()->route('view_assigned_templates')->with('success', 'Assigned template updated successfully!');
    }

    public function send_newspaper(Request $request, $id)
    {
        $subscriber_id = $request->subscriber_id;
        $template_id = $request->template_id;

        // Fetch subscriber and template
        $subscriber = NewsletterSubscription::findOrFail($subscriber_id);
        $template = NewsletterTemplate::findOrFail($template_id);

        // Send newsletter via email using Mailable class
        Mail::to($subscriber->email)->send(new NewsletterMail($template));

        // Optionally, update sent status in database (if using pivot table)
        $subscriber->templates()->updateExistingPivot($template->id, ['sent' => true]);

        return redirect()->back()->with('success', 'Newsletter sent successfully!');
    }
}