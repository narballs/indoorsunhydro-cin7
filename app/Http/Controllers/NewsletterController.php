<?php
namespace App\Http\Controllers;

use App\Mail\NewsletterMail;
use App\Models\NewsletterSubsciberTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\NewsletterSubscription;
use App\Models\NewsletterTemplate;
use App\Models\SubscriberEmailList;
use App\Models\SubscriberList;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Twilio\Rest\Events\V1\SubscriptionList;

class NewsletterController extends Controller
{
    function __construct()
    {
        $this->middleware(['role:Newsletter']);
    }
    public function newsletter_dashboard (Request $request)
    {
        $user_id = Auth::id();
        $model_has_roles = DB::table('model_has_roles')->where('model_id', $user_id)->first();
        $role_id = $model_has_roles->role_id;
        $role = DB::table('roles')->where('id', $role_id)->first();
        $role_name = $role->name;
        // if ($user->hasRole(['Newsletter'])) {
        //     return view('newsletter_layout.dashboard');
        // } else {
        //     return redirect()->route('home');
        // }

        if ($role_name == 'Newsletter') {
            return view('newsletter_layout.dashboard');
        } else {
            return redirect()->route('home');
        }
    }
    public function newsletter_subscriptions (Request $request)
    {
        $newsletter_subscriptions = NewsletterSubscription::orderBy('id', 'desc')->paginate(10);
        $subscribers_list = SubscriberList::orderBy('id', 'desc')->get();
        return view('newsletter_layout.newsletter_subscribers.index', compact('newsletter_subscriptions' , 'subscribers_list'));
    }


    public function showAssignForm() {
        $templates = NewsletterTemplate::all();
        $subscriber_email_lists = SubscriberList::all();

        
        return view('newsletter_layout.newsletter_templates.assign_template', compact('subscriber_email_lists', 'templates'));
    }

    public function assignTemplates(Request $request)
    {
        $request->validate([
            'subscriber_email_list_id' => 'required|exists:subscriber_lists,id',
            'template_id' => 'required|exists:newsletter_templates,id',
        ]);

        if (NewsletterSubsciberTemplate::where('list_id', $request->subscriber_email_list_id)->where('newsletter_template_id', $request->template_id)->exists()) {
            return redirect()->back()->with('error', 'Template already assigned to list!');
        }

        $subscriber_email_lists = SubscriberList::findOrFail($request->subscriber_email_list_id);
        $subscriber_email_lists->templates()->attach($request->template_id);

        return redirect()->back()->with('success', 'Template assigned to list successfully!');
    }

    public function view_assigned_templates()
    {
        $assigned_templates = NewsletterSubsciberTemplate::with('subscriber_email_list', 'template')->get();
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
        $lists = SubscriberList::all();
        $templates = NewsletterTemplate::all();

        return view('newsletter_layout.newsletter_templates.edit_assigned_template', compact('assigned_template', 'lists', 'templates'));
    }

    public function update_assigned_template(Request $request, $id)
    {
        $request->validate([
            'list_id' => 'required|exists:subscriber_lists,id',
            'template_id' => 'required|exists:newsletter_templates,id',
        ]);

        $assigned_template = NewsletterSubsciberTemplate::findOrFail($id);
        $assigned_template->update([
            'list_id' => $request->list_id,
            'newsletter_template_id' => $request->template_id,
        ]);

        return redirect()->route('view_assigned_templates')->with('success', 'Assigned template updated successfully!');
    }

    public function send_newspaper(Request $request, $id)
    {
        $subscriber_email_list_id = $request->subscriber_email_list_id;
        $template_id = $request->template_id;

        // Fetch subscriber and template
        $subscriber_emails = SubscriberEmailList::where('subscriber_lists_id', $subscriber_email_list_id)->get();
        $template = NewsletterTemplate::findOrFail($template_id);

        if (count($subscriber_emails) === 0) {
            return redirect()->back()->with('error', 'No subscribers found in the selected list!');
        }
        
        foreach ($subscriber_emails as $subscriber_email) {
            Mail::to($subscriber_email->email)->send(new NewsletterMail($template));
            $subscriber_email->templates()->attach($template_id);
            $subscriber_email->templates()->updateExistingPivot($template_id, ['sent' => true]);
        }

        return redirect()->back()->with('success', 'Newsletter sent successfully!');
    }


    public function all_contacts() {
        $all_contacts = User::orderBy('id', 'desc')->paginate(10);
        $subscribers_list = SubscriberList::orderBy('id', 'desc')->get();
        return view('newsletter_layout.newsletter_subscribers.all_contacts', compact('all_contacts' , 'subscribers_list'));
    }

    public function subscribers_list() {
        $subscribers_list = SubscriberList::orderBy('id', 'desc')->paginate(10);
        return view('newsletter_layout.subscribers_list.index', compact('subscribers_list'));
    }
    public function subscribers_list_create() {
        return view('newsletter_layout.subscribers_list.create');
    }

    public function subscribers_list_store(Request $request) {
        $request->validate([
            'name' => 'required',
            // 'url' => 'required',
            // 'description' => 'required',
        ]);

        $subscribers_list = new SubscriberList();
        $subscribers_list->name = $request->name;
        $subscribers_list->url = $request->url;
        $subscribers_list->description = $request->description;
        $subscribers_list->save();

        return redirect()->route('subscribers_list')->with('success', 'Subscribers list created successfully!');
    }

    public function subscribers_list_edit($id) {
        $subscribers_list = SubscriberList::findOrFail($id);
        return view('newsletter_layout.subscribers_list.edit', compact('subscribers_list'));
    }

    public function subscribers_list_update(Request $request, $id) {
        $request->validate([
            'name' => 'required',
            // 'url' => 'required',
            // 'description' => 'required',
        ]);

        $subscribers_list = SubscriberList::findOrFail($id);
        $subscribers_list->name = $request->name;
        $subscribers_list->url = $request->url;
        $subscribers_list->description = $request->description;
        $subscribers_list->save();

        return redirect()->route('subscribers_list')->with('success', 'Subscribers list updated successfully!');
    }

    public function subscribers_list_delete($id) {
        $subscribers_list = SubscriberList::findOrFail($id);
        $subscriber_email_list = SubscriberEmailList::where('subscriber_lists_id', $id)->get();
        if (count($subscriber_email_list) > 0) {
            foreach ($subscriber_email_list as $subscriber_email) {
                $subscriber_email->delete();
            }
        }
        
        $subscribers_list->delete();

        return redirect()->back()->with('success', 'Subscribers list deleted successfully!');
    }

    public function save_users_to_list(Request $request) {
        // Convert list_ids to an array
        $list_ids = is_array($request->list_ids) ? $request->list_ids : explode(',', $request->list_ids);
    
        // Convert user_emails to an array
        $user_emails = is_array($request->user_emails) ? $request->user_emails : explode(',', $request->user_emails);
    
        // Array to keep track of existing emails
        $existingEmails = [];
        // Array to keep track of successfully added emails
        $addedEmails = [];
    
        // Check if user_email already exists
        foreach ($user_emails as $user_email) {
            $existingSubscriber = SubscriberEmailList::where('email', trim($user_email))->first();
            if ($existingSubscriber) {
                // If exists, add to existingEmails array
                $existingEmails[] = $user_email;
            }
        }
    
        // If only one email is selected, check its existence in the list(s)
        if (count($user_emails) === 1) {
            foreach ($list_ids as $list_id) {
                $exists = SubscriberEmailList::where('email', trim($user_emails[0]))
                    ->where('subscriber_lists_id', $list_id)
                    ->exists();
    
                if ($exists) {
                    // If email already exists in any list, return error response
                    return response()->json([
                        'message' => "User email '{$user_emails[0]}' already exists in one of the selected lists.",
                        'status' => 'error'
                    ], 400);
                }
            }
        }
    
        // Process each list id and user email
        foreach ($list_ids as $list_id) {
            foreach ($user_emails as $user_email) {
                // Check if email already exists in the current list
                $exists = SubscriberEmailList::where('email', trim($user_email))
                    ->where('subscriber_lists_id', $list_id)
                    ->exists();
    
                if ($exists) {
                    // Skip if email already exists in the current list
                    continue;
                }
    
                // Create a new instance of SubscriberEmailList model
                $subscriberEmailList = new SubscriberEmailList();
                $subscriberEmailList->subscriber_lists_id = $list_id;
                $subscriberEmailList->email = trim($user_email); // Trim to remove any whitespace
    
                // Save the record
                $subscriberEmailList->save();
    
                // Track successfully added emails
                $addedEmails[] = $user_email;
            }
        }
    
        // Prepare response messages based on the results
        $successMessage = '';
        $errorMessage = '';
        $status = 'success';
    
        // If any emails were successfully added
        if (!empty($addedEmails)) {
            $successMessage = 'Users added to list successfully.';
        } else {
            $errorMessage = 'Users already exist in the selected list(s).';
            $status = 'error';
        }
    
        // Optionally, you can return a response or redirect to a success page
        return response()->json([
            'message' => $successMessage,
            'error' => $errorMessage,
            'status' => $status
        ]);
    }


    public function subscribers_list_show_users($id) {
        $subscribers_emails_list = SubscriberEmailList::where('subscriber_lists_id', $id)->paginate(10);
        return view('newsletter_layout.subscribers_list.show_users', compact('subscribers_emails_list' , 'id'));
    }

    public function delete_user_from_list($id) {
        $subscriber_email = SubscriberEmailList::findOrFail($id);
        $subscriber_email->delete();

        return redirect()->back()->with('success', 'User deleted from list successfully!');
    }
    
  
}