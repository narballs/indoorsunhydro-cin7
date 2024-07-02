<?php
namespace App\Http\Controllers;

use App\Mail\NewsletterMail;
use App\Models\NewsletterSubscriberTemplate;
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
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;

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
            $templates = NewsletterTemplate::all();
            return view('newsletter_layout.newsletter_dashboard' , compact('templates'));
        } else {
            return redirect()->route('home');
        }
    }
    public function newsletter_subscriptions (Request $request)
    {
        
        $search = $request->search;
        $newsletter_subscriptions = NewsletterSubscription::orderBy('id', 'desc');
        if (!empty($search)) {
            $newsletter_subscriptions = $newsletter_subscriptions->where('email', 'like', '%' . $search . '%')->orWhere('tags', 'like', '%' . $search . '%')->paginate(10);
        } else {
            $newsletter_subscriptions = $newsletter_subscriptions->paginate(10);
        }
        $subscribers_list = SubscriberList::orderBy('id', 'desc')->get();
        return view('newsletter_layout.newsletter_subscribers.index', compact('newsletter_subscriptions' , 'subscribers_list' , 'search'));
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

        if (NewsletterSubscriberTemplate::where('list_id', $request->subscriber_email_list_id)->where('newsletter_template_id', $request->template_id)->exists()) {
            return redirect()->back()->with('error', 'Template already assigned to list!');
        }

        $subscriber_email_lists = SubscriberList::findOrFail($request->subscriber_email_list_id);
        $subscriber_email_lists->templates()->attach($request->template_id);

        return redirect()->back()->with('success', 'Template assigned to list successfully!');
    }

    public function view_assigned_templates()
    {
        $assigned_templates = NewsletterSubscriberTemplate::with('subscriber_email_list', 'template')->get();
        return view('newsletter_layout.newsletter_templates.view_assigned_templates', compact('assigned_templates'));
    }

    public function delete_assigned_template($id)
    {
        $assigned_template = NewsletterSubscriberTemplate::findOrFail($id);
        $assigned_template->delete();

        return redirect()->back()->with('success', 'Assigned template removed successfully!');
    }

    public function edit_assigned_template($id) {
        $assigned_template = NewsletterSubscriberTemplate::findOrFail($id);
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

        $assigned_template = NewsletterSubscriberTemplate::findOrFail($id);
        $assigned_template->update([
            'list_id' => $request->list_id,
            'newsletter_template_id' => $request->template_id,
        ]);

        return redirect()->route('view_assigned_templates')->with('success', 'Assigned template updated successfully!');
    }

    public function send_newspaper(Request $request, $id) {
        $subscriber_email_list_id = $request->subscriber_email_list_id;
        $template_id = $request->template_id;

        // Fetch template
        $template = NewsletterTemplate::findOrFail($template_id);

        // Fetch subscriber emails associated with the selected list
        $subscriber_emails = SubscriberEmailList::where('subscriber_lists_id', $subscriber_email_list_id)->get();

        if ($subscriber_emails->isEmpty()) {
            return redirect()->back()->with('error', 'No subscribers found in the selected list!');
        }

        foreach ($subscriber_emails as $subscriber_email) {
            // Send email using Laravel Mail
            Mail::to($subscriber_email->email)->send(new NewsletterMail($template));

            // Update or create record in the pivot table (newsletter_subscriber_template)
            NewsletterSubscriberTemplate::updateOrCreate(
                ['list_id' => $subscriber_email_list_id, 'newsletter_template_id' => $template->id],
                ['sent' => true]
            );
        }

        return redirect()->back()->with('success', 'Newsletter sent successfully!');
    }


    public function all_contacts(Request $request) {
        $search = $request->search;
        $all_contacts_query = User::orderBy('id', 'desc');

        if (!empty($search)) {
            $all_contacts_query->where('email', 'like', '%' . $search . '%');
        }

        $all_contacts = $all_contacts_query->paginate(10);
        $subscribers_list = SubscriberList::orderBy('id', 'desc')->get();
        $newsletter_subscriptions = NewsletterSubscription::orderBy('id', 'desc')->get();
        return view('newsletter_layout.newsletter_subscribers.all_contacts', compact('all_contacts' , 'subscribers_list' , 'search' , 'newsletter_subscriptions'));
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


    
    // bulk upload emails

    public function bulk_upload(Request $request) {
        try {
            // Validate the request input
            $validator = Validator::make($request->all(), [
                'bulk_upload_emails' => 'required|string',
            ]);
    
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
    
            // Extract emails from input, trim whitespace
            $tags = $request->tags;
            $emails = explode("\n", $request->input('bulk_upload_emails'));
            $emails = array_map('trim', $emails);
    
            // Get existing emails from the database
            $existingEmails = NewsletterSubscription::pluck('email')->toArray();
    
            $data = [];
            foreach ($emails as $email) {
                // Validate email format
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    // Check if email already exists in database
                    if (!in_array($email, $existingEmails)) {
                        $data[] = ['email' => $email, 'tags' => $tags, 'created_at' => now(), 'updated_at' => now()];
                    }
                }
                // Invalid emails are silently skipped
            }
    
            // Insert new emails into the database
            if (!empty($data)) {
                NewsletterSubscription::insert($data);
            }
    
            return response()->json(['success' => true, 'message' => 'Emails uploaded successfully.']);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Failed to upload emails. Your data is invalid.'], 400);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while processing the request.'], 500);
        }
    }
    
    // import subscribers
    public function importSubscribers(Request $request) {
        try {
            // Validate the file and tags input
            $request->validate([
                'file' => 'required|mimes:csv,xlsx,xls,txt',
                // 'tags' => 'nullable|string'
            ]);
    
            $path = $request->file('file')->getRealPath();
            $extension = $request->file('file')->getClientOriginalExtension();
    
            // Define possible variations of email column headers
            $emailHeaders = ['Email', 'email', 'E-mail', 'e-mail'];
    
            // Initialize variables
            $header = null;
            $emailIndex = null;
            $validEmails = [];
            $tags = $request->input('tags', ''); // Retrieve tags input
    
            // Read file based on extension
            if (in_array($extension, ['csv', 'txt'])) {
                $file = fopen($path, 'r');
                $header = fgetcsv($file);
            } else {
                $header = (new HeadingRowImport)->toArray($path)[0][0];
            }
    
            // Find the correct email column index based on header case insensitivity
            if ($header) {
                foreach ($emailHeaders as $emailHeader) {
                    $emailIndex = array_search($emailHeader, $header);
                    if ($emailIndex !== false) {
                        break;
                    }
                }
            }
    
            // Process the file based on the found email index
            if ($emailIndex !== false) {
                if (in_array($extension, ['csv', 'txt'])) {
                    while ($columns = fgetcsv($file)) {
                        if (isset($columns[$emailIndex])) {
                            $email = trim($columns[$emailIndex]);
                            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                $validEmails[] = $email;
                            }
                        }
                    }
                    fclose($file);
                } else {
                    $rows = Excel::toArray([], $path)[0];
                    foreach ($rows as $key => $row) {
                        if ($key > 0 && isset($row[$emailIndex])) {
                            $email = trim($row[$emailIndex]);
                            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                $validEmails[] = $email;
                            }
                        }
                    }
                }
    
                $validEmails = array_unique($validEmails);
    
                $existingEmails = NewsletterSubscription::whereIn('email', $validEmails)->pluck('email')->toArray();
                $newEmails = array_diff($validEmails, $existingEmails);
    
                $data = [];
                foreach ($newEmails as $email) {
                    $data[] = [
                        'email' => $email, 
                        'tags' => $tags, // Add tags to each new email
                        'created_at' => now(), 
                        'updated_at' => now()
                    ];
                }
    
                if (!empty($data)) {
                    NewsletterSubscription::insert($data);
                }
    
                return response()->json(['success' => true, 'message' => 'Emails uploaded successfully.']);
            } else {
                return response()->json(['success' => false, 'message' => 'File does not contain a recognized email column header.'], 400);
            }
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Failed to upload emails. Your data is invalid.'], 400);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while processing the file.'], 500);
        }
    }

    // add specific user to list 
    public function add_subscriber_to_list(Request $request) {
        try {
            $request->validate([
                'email' => 'required|email|unique:newsletter_subscriptions,email',
                'tags' => 'nullable|string',
                // 'list_id' => 'required|exists:newsletter_lists,id'
            ]);
    
            $email = $request->input('email');
            $tags = $request->input('tags', '');
            $listId = $request->input('list_id');
    
            NewsletterSubscription::create([
                'email' => $email,
                'tags' => $tags,
                // 'list_id' => $listId, // Ensure your table has this column
                'created_at' => now(),
                'updated_at' => now()
            ]);


            $subscriber_email_list = SubscriberEmailList::where('email', $email)->where('subscriber_lists_id', $listId)->first();
            if (!$subscriber_email_list) {
                SubscriberEmailList::create([
                    'email' => $email,
                    'subscriber_lists_id' => $listId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
    
            return response()->json(['success' => true, 'message' => 'Subscriber added successfully.']);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Validation error. Please check your input.'], 400);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while adding the subscriber.'], 500);
        }
    }

    // bulk upload to list 
    public function bulk_upload_to_list(Request $request)
    {
        try {
            // Validate the request data
            $validator = Validator::make($request->all(), [
                'bulk_upload_emails' => 'required|string',
                'tags' => 'nullable|string',
                // 'list_id' => 'required|exists:newsletter_lists,id'
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()], 400);
            }

            $listId = $request->input('list_id');
            $tags = $request->input('tags', '');
            $bulkEmails = explode("\n", $request->input('bulk_upload_emails'));

            $validEmails = [];
            foreach ($bulkEmails as $email) {
                $email = trim($email);
                if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    // Check if email already exists in NewsletterSubscription
                    $existingSubscription = NewsletterSubscription::where('email', $email)->first();

                    if (!$existingSubscription) {
                        // Email does not exist in NewsletterSubscription, so insert it
                        NewsletterSubscription::create([
                            'email' => $email,
                            'tags' => $tags,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }

                    // Check if email exists in SubscriberEmailList for the current list_id
                    $existingEmailList = SubscriberEmailList::where('email', $email)
                        ->where('subscriber_lists_id', $listId)
                        ->first();

                    if (!$existingEmailList) {
                        // Email does not exist in SubscriberEmailList for the current list_id, so add it
                        $validEmails[] = [
                            'email' => $email,
                            'subscriber_lists_id' => $listId,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    }
                }
            }

            // Insert valid emails into database
            if (!empty($validEmails)) {
                SubscriberEmailList::insert($validEmails);

                return response()->json(['success' => true, 'message' => 'Bulk upload successful.']);
            }

            return response()->json(['success' => false, 'message' => 'No valid emails to upload.'], 400);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while processing the request.'], 500);
        }
    }

    // import subscribers to list
    public function importUsersToList(Request $request)
    {
        try {
            // Validate the request data
            $validator = Validator::make($request->all(), [
                'file' => 'required|mimes:csv,xls,xlsx', // Validate file type
                'tags' => 'nullable|string',
                // 'list_id' => 'required|exists:subscriber_email_lists,id' // Validate list_id exists in newsletter_lists table
            ]);
    
            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()], 400);
            }
    
            $listId = $request->input('list_id');
            $tags = $request->input('tags', '');
            $file = $request->file('file');
    
            // Process the uploaded file
            $importedEmails = $this->processImportedFile($file);
    
            // Initialize arrays for emails to insert and skipped emails
            $emailsToInsert = [];
            $skippedEmails = [];
    
            // Iterate over imported emails
            foreach ($importedEmails as $email) {
                if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    // Check if email exists in NewsletterSubscription
                    $existingSubscription = NewsletterSubscription::where('email', $email)->first();
    
                    if (!$existingSubscription) {
                        // Email does not exist in NewsletterSubscription, so insert it
                        NewsletterSubscription::create([
                            'email' => $email,
                            'tags' => $tags,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
    
                    // Check if email exists in SubscriberEmailList for the current list_id
                    $existingEmailList = SubscriberEmailList::where('email', $email)
                        ->where('subscriber_lists_id', $listId)
                        ->first();
    
                    if (!$existingEmailList) {
                        // Email does not exist in SubscriberEmailList for the current list_id, so add it
                        $emailsToInsert[] = [
                            'email' => $email,
                            'subscriber_lists_id' => $listId,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    } else {
                        // Email exists in SubscriberEmailList for the current list_id, skip it
                        $skippedEmails[] = $email;
                    }
                } else {
                    // Invalid email format, skip it
                    $skippedEmails[] = $email;
                }
            }
    
            // Insert emails into SubscriberEmailList
            if (!empty($emailsToInsert)) {
                SubscriberEmailList::insert($emailsToInsert);
            }
    
            // Prepare response messages
            $successMessage = count($emailsToInsert) . ' emails imported successfully.';
            $skippedMessage = count($skippedEmails) . ' emails skipped due to invalid format or already existing.';
    
            // Return response
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'skipped_emails' => $skippedMessage,
            ]);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e], 500);
        }
    }

    private function processImportedFile($file)
    {
        $importedEmails = [];

        try {
            $path = $file->getRealPath();
            $extension = $file->getClientOriginalExtension();

            // Define possible variations of email column headers
            $emailHeaders = ['Email', 'email', 'E-mail', 'e-mail'];

            // Initialize variables
            $header = null;
            $emailIndex = null;

            // Read file based on extension
            if (in_array($extension, ['csv', 'txt'])) {
                $file = fopen($path, 'r');
                $header = fgetcsv($file);
            } else {
                // Use Laravel Excel package to get headers from Excel file
                $header = (new HeadingRowImport)->toArray($path)[0][0];
            }

            // Find the correct email column index based on header case insensitivity
            if ($header) {
                foreach ($emailHeaders as $emailHeader) {
                    $emailIndex = array_search($emailHeader, $header);
                    if ($emailIndex !== false) {
                        break;
                    }
                }
            }

            // Process the file based on the found email index
            if ($emailIndex !== false) {
                if (in_array($extension, ['csv', 'txt'])) {
                    while ($columns = fgetcsv($file)) {
                        if (isset($columns[$emailIndex])) {
                            $email = trim($columns[$emailIndex]);
                            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                $importedEmails[] = $email;
                            }
                        }
                    }
                    fclose($file);
                } else {
                    $rows = Excel::toArray([], $path)[0];
                    foreach ($rows as $key => $row) {
                        if ($key > 0 && isset($row[$emailIndex])) {
                            $email = trim($row[$emailIndex]);
                            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                $importedEmails[] = $email;
                            }
                        }
                    }
                }

                $importedEmails = array_unique($importedEmails);
            } else {
                throw new \Exception('File does not contain a recognized email column header.');
            }
        } catch (\Exception $e) {
            throw new \Exception('An error occurred while processing the file.');
        }

        return $importedEmails;
    }
                    
}