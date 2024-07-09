<?php

namespace App\Http\Controllers;

use App\Models\AdminSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\MobileNumber;
use App\Models\MobileNumberCampaign;
use App\Models\MobileNumberList;
use App\Models\NumberList;
use App\Models\SmsTemplate;
use Illuminate\Support\Facades\Log;
use Twilio\TwiML\Voice\Sms;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use GuzzleHttp\Client;

class SmsController extends Controller
{ 
    function __construct()
    {
        $this->middleware(['role:Newsletter']);
    }

    public function sms_list()
    {
        $number_lists = NumberList::orderBy('id', 'desc')->paginate(10);
        return view('newsletter_layout.number_list.index' , compact('number_lists'));
    }
    public function sms_list_create()
    {
        return view('newsletter_layout.number_list.create');
    }

    public function sms_list_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            // 'url' => 'required',
            // 'description' => 'required',
        ]);

        $subscribers_list = new NumberList();
        $subscribers_list->name = $request->name;
        $subscribers_list->url = $request->url;
        $subscribers_list->description = $request->description;
        $subscribers_list->save();

        return redirect()->route('sms_list')->with('success', 'Numbers list created successfully!');
    }

    public function sms_list_edit($id)
    {
        $number_list = NumberList::find($id);
        return view('newsletter_layout.number_list.edit' , compact('number_list'));
    }

    public function sms_list_update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            // 'url' => 'required',
            // 'description' => 'required',
        ]);

        $subscribers_list = NumberList::find($id);
        $subscribers_list->name = $request->name;
        $subscribers_list->url = $request->url;
        $subscribers_list->description = $request->description;
        $subscribers_list->save();

        return redirect()->route('sms_list')->with('success', 'Numbers list updated successfully!');
    }

    
    public function sms_list_delete($id) {
        $subscribers_list = NumberList::findOrFail($id);
        $mobile_number_list = MobileNumberList::where('number_list_id', $id)->get();
        if (count($mobile_number_list) > 0) {
            foreach ($mobile_number_list as $subscriber_email) {
                $subscriber_email->delete();
            }
        }
        
        $subscribers_list->delete();

        return redirect()->back()->with('success', 'Subscribers list deleted successfully!');
    }


    // sms campaigns

    // public function sms_campaigns()
    // {
    //     $templates = MobileNumberCampaign::with('sent_newsletter' , 'sent_newsletter.subscriber_email_list')->orderBy('created_at' , 'DESC')->get();
    //     return view('newsletter_layout.sms_campaigns.index' , compact('mobile_numbers'));
    // }


    // Sms templates

    public function list_sms_templates() {

        $templates = SmsTemplate::with('sent_sms' , 'sent_sms.mobile_number_list' , 'sent_sms.sms_template')
        ->orderBy('created_at' , 'DESC')
        ->get();
        return view('newsletter_layout.sms_template.index', compact('templates'));

    }

    public function create_sms_templates() {
        $number_lists = NumberList::all();
        return view('newsletter_layout.sms_template.create', compact('number_lists'));
    }

    public function store_sms_templates(Request $request) {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);
        $sms_template = new SmsTemplate();
        $sms_template->name = $request->name;
        $sms_template->description = $request->description;
        $sms_template->save();

        $check_newsletter_list_entry = MobileNumberCampaign::where('mobile_number_list_id', $request->number_list_id)->where('sms_template_id', $sms_template->id)->first();
        if (!empty($check_newsletter_list_entry)) {
            $delete_sms_template = SmsTemplate::find($sms_template->id);
            $delete_sms_template->delete();
            return redirect()->back()->with('error', 'This sms template is already assigned to the selected numbers list!');
        } 
        else {
            $newsletter_subscriber_template = new MobileNumberCampaign();
            $newsletter_subscriber_template->mobile_number_list_id = $request->number_list_id;
            $newsletter_subscriber_template->sms_template_id = $sms_template->id;
            $newsletter_subscriber_template->save();
        }

        return redirect()->route('list_sms_templates')->with('success', 'Sms template created successfully!');
    }

    public function edit_sms_templates($id) {
        $smsTemplate = SmsTemplate::find($id);
        $number_lists = NumberList::all();
        $selected_id  = null;
        if (count($number_lists) > 0) {
            foreach ($number_lists as $mobile_number_list) {
                $check_newsletter_list_entry = MobileNumberCampaign::where('mobile_number_list_id', $mobile_number_list->id)->where('sms_template_id', $smsTemplate->id)->first();
                if (!empty($check_newsletter_list_entry)) {
                    $selected_id = $mobile_number_list->id;
                    break;
                }
            }
        }
        return view('newsletter_layout.sms_template.edit', compact('smsTemplate' , 'number_lists' , 'selected_id'));
    }

    public function update_sms_templates(Request $request , $id) {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        $sms_template = SmsTemplate::find($id);
        $sms_template->name = $request->name;
        $sms_template->description = $request->description;
        $sms_template->save();


        if (!empty($request->mobile_number_list_id)) {
            $check_newsletter_list_entry = MobileNumberCampaign::where('mobile_number_list_id', $request->mobile_number_list_id)->where('sms_template_id', $sms_template->id)->first();
        
            if (!empty($check_newsletter_list_entry)) {
                return redirect()->back()->with('error', 'This sms template is already assigned to the selected subscriber list!');
            } 
            else {
                $mobile_number_list = new MobileNumberCampaign();
                $mobile_number_list->mobile_number_list_id = $request->mobile_number_list_id;
                $mobile_number_list->newsletter_template_id = $sms_template->id;
                $mobile_number_list->save();
            }
        }

        return redirect()->route('list_sms_templates')->with('success', 'Sms template updated successfully!');
    }

    public function delete_sms_templates($id) {
        $sms_template = SmsTemplate::find($id);
        $delete_sms_template = MobileNumberCampaign::where('sms_template_id', $sms_template->id)->first();
        $delete_sms_template->delete();
        $sms_template->delete();
        return redirect()->route('list_sms_templates')->with('success', 'Sms template deleted successfully!');
    }


    public function upload_sms_templateImage(Request $request)
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('/theme/bootstrap5/sms_template_images');
            
            // Create the directory if it doesn't exist
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            
            $file->move($destinationPath, $filename);

            $url = asset('theme/bootstrap5/sms_template_images/' . $filename);

            return response()->json([
                'uploaded' => true,
                'url' => $url,
            ]);
        }

        return response()->json([
            'uploaded' => false,
            'error' => [
                'message' => 'No file uploaded.'
            ]
        ]);
    }


    //add mobile number to list 

    public function add_mobile_numbers_to_list (Request $request) {
        try {
            $request->validate([
                'mobile_number' => 'required',
                'tags' => 'nullable|string',
            ]);
    
            $mobile_number = $request->input('mobile_number');
            $tags = $request->input('tags', '');
            $listId = $request->input('list_id');

            $check_mobile_number = MobileNumber::where('mobile_number', $mobile_number)->first();
            // if (!empty($check_mobile_number)) {
            //     return response()->json(['success' => false, 'message' => 'Mobile Number already exists.'], 400);
            // }
    
            


            $mobile_numbers_list = MobileNumberList::where('mobile_number', $mobile_number)->where('number_list_id', $listId)->first();
            if (!$mobile_numbers_list) {
                MobileNumberList::create([
                    'mobile_number' => $mobile_number,
                    'number_list_id' => $listId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            $check_mobile_number = MobileNumber::where('mobile_number', $mobile_number)->first();
            if (!$check_mobile_number) {
                MobileNumber::create([
                    'mobile_number' => $mobile_number,
                    'tags' => $tags,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
    
            return response()->json(['success' => true, 'message' => 'Mobile Number added successfully.']);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Validation error. Please check your input.'], 400);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Something went wrong !'], 500);
        }
    }

    public function show_numbers_from_list($id) {
        $mobile_number_lists = MobileNumberList::where('number_list_id', $id)->paginate(10);
        return view('newsletter_layout.number_list.show_numbers', compact('mobile_number_lists' , 'id'));
    }


    public function delete_number_from_list($id) {
        $mobile_number = MobileNumberList::findOrFail($id);
        $mobile_number->delete();

        return redirect()->back()->with('success', 'Mobile Number deleted from list successfully!');
    }


    // addign and importing bulk and import 
    // bulk upload to list 
    public function bulk_upload_numbers_to_list(Request $request)
    {
        try {
            // Validate the request data
            $validator = Validator::make($request->all(), [
                'bulk_upload_numbers' => 'required|string',
                'tags' => 'nullable|string',
                // 'list_id' => 'required|exists:newsletter_lists,id'
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()], 400);
            }

            $listId = $request->input('list_id');
            $tags = $request->input('tags', '');
            $bulkNumbers = explode("\n", $request->input('bulk_upload_numbers'));

            $validEmails = [];
            foreach ($bulkNumbers as $number) {
                $number = trim($number);
                if (!empty($number) && filter_var($number, FILTER_VALIDATE_EMAIL)) {
                    // Check if email already exists in NewsletterSubscription
                    $existingNumber = MobileNumber::where('mobile_number', $number)->first();

                    if (!$existingNumber) {
                        // Email does not exist in MobileNumber, so insert it
                        MobileNumber::create([
                            'mobile_number' => $number,
                            'tags' => $tags,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }

                    // Check if email exists in SubscriberEmailList for the current list_id
                    $existingMobileNumberList = MobileNumberList::where('mobile_number', $number)
                        ->where('number_list_id', $listId)
                        ->first();

                    if (!$existingMobileNumberList) {
                        // Email does not exist in SubscriberEmailList for the current list_id, so add it
                        $validEmails[] = [
                            'mobile_number' => $number,
                            'number_list_id' => $listId,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    }
                }
            }

            // Insert valid emails into database
            if (!empty($validEmails)) {
                MobileNumberList::insert($validEmails);

                return response()->json(['success' => true, 'message' => 'Bulk upload successful.']);
            }

            return response()->json(['success' => false, 'message' => 'No valid numbers to upload.'], 400);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while processing the request.'], 500);
        }
    }

    // import subscribers to list
    public function import_numbers_to_list(Request $request)
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
            $importedNumbers = $this->processImportedFile($file);

            // Initialize arrays for emails to insert and skipped emails
            $numbersToInsert = [];
            $skippedNumbers = [];

            // Iterate over imported emails
            foreach ($importedNumbers as $number) {
                if (!empty($number) && filter_var($number, FILTER_VALIDATE_EMAIL)) {
                    // Check if email exists in NewsletterSubscription
                    $existingNumber = MobileNumber::where('mobile_number', $number)->first();

                    if (!$existingNumber) {
                        // Email does not exist in NewsletterSubscription, so insert it
                        MobileNumber::create([
                            'mobile_number' => $number,
                            'tags' => $tags,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }

                    // Check if email exists in SubscriberEmailList for the current list_id
                    $existingNumberList = MobileNumberList::where('mobile_number', $number)
                        ->where('number_list_id', $listId)
                        ->first();

                    if (!$existingNumberList) {
                        // Email does not exist in SubscriberEmailList for the current list_id, so add it
                        $numbersToInsert[] = [
                            'mobile_number' => $number,
                            'number_list_id' => $listId,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    } else {
                        // Email exists in SubscriberEmailList for the current list_id, skip it
                        $skippedNumbers[] = $number;
                    }
                } else {
                    // Invalid email format, skip it
                    $skippedNumbers[] = $number;
                }
            }

            // Insert emails into SubscriberEmailList
            if (!empty($numbersToInsert)) {
                MobileNumberList::insert($numbersToInsert);
            }

            // Prepare response messages
            $successMessage = count($numbersToInsert) . ' numbers imported successfully.';
            $skippedMessage = count($skippedNumbers) . ' numbers skipped due to invalid format or already existing.';

            // Return response
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'skipped_emails' => $skippedMessage,
            ]);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            Log::error('File processing error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred while processing the file.'], 500);
        }
    }

    private function processImportedFile($file)
    {
        $importedNumbers = [];

        try {
            $path = $file->getRealPath();
            $extension = $file->getClientOriginalExtension();

            // Define possible variations of email column headers
            $numberHeaders = ['numbers', 'Numbers', 'Mobile Number', 'Mobile Numbers'];

            // Initialize variables
            $header = null;
            $numberIndex = null;

            // Read file based on extension
            if ($extension === 'csv') {
                $file = fopen($path, 'r');
                $header = fgetcsv($file);
            } else {
                // Use Laravel Excel package to get headers from Excel file
                $header = (new HeadingRowImport())->toArray($file)[0][0];
            }

            // Find the correct email column index based on header case insensitivity
            if ($header) {
                foreach ($numberHeaders as $numberlHeader) {
                    $numberIndex = array_search($numberlHeader, $header);
                    if ($numberIndex !== false) {
                        break;
                    }
                }
            }

            // Process the file based on the found email index
            if ($numberIndex !== false) {
                if ($extension === 'csv') {
                    while ($columns = fgetcsv($file)) {
                        if (isset($columns[$numberIndex])) {
                            $number = trim($columns[$numberIndex]);
                            if (filter_var($number, FILTER_VALIDATE_EMAIL)) {
                                $importedNumbers[] = $number;
                            }
                        }
                    }
                    fclose($file);
                } else {
                    $rows = Excel::toArray([], $file)[0];
                    foreach ($rows as $key => $row) {
                        if ($key > 0 && isset($row[$numberIndex])) {
                            $number = trim($row[$numberIndex]);
                            if (filter_var($number, FILTER_VALIDATE_EMAIL)) {
                                $importedNumbers[] = $number;
                            }
                        }
                    }
                }

                $importedNumbers = array_unique($importedNumbers);
            } else {
                throw new \Exception('File does not contain a recognized number column header.');
            }
        } catch (\Exception $e) {
            Log::error('Error processing file: ' . $e->getMessage());
            throw new \Exception('An error occurred while processing the file.');
        }

        return $importedNumbers;
    }


    public function send_sms(Request $request , $id)
    {
        $mobile_number_list_id = $request->mobile_number_list_id;
        $template_id = $request->sms_template_id;

        // Fetch template
        $template = SmsTemplate::findOrFail($template_id);
        $number_list = MobileNumberList::with('number_list')->where('number_list_id' , $mobile_number_list_id)->get();
        if (count($number_list) == 0 ) {
            return redirect()->back()->with('error', 'No Numbers found in the selected list!');
        }

        $twilio_sid = AdminSetting::where('option_name', 'twilio_sid')->first();
        $twilio_token = AdminSetting::where('option_name', 'twilio_token')->first();
        $twilio_number = AdminSetting::where('option_name', 'twilio_number')->first();

        $client = new Client([
            'base_uri' => 'https://api.twilio.com',
            'auth' => [$twilio_sid->option_value, $twilio_token->option_value],
        ]);

        $errors = [];
        $success_count = 0;

        foreach ($number_list as $list_number) {
            try {
                $response = $client->post("/2010-04-01/Accounts/{$twilio_sid->option_value}/Messages.json", [
                    'form_params' => [
                        'From' => $twilio_number->option_value,
                        'To' => $list_number->mobile_number,
                        'Body' => $template->description,
                    ],
                ]);

                if ($response->getStatusCode() == 201 || $response->getStatusCode() == 200) {
                    $success_count++;
                    MobileNumberCampaign::updateOrCreate(
                        [
                            'mobile_number_list_id' => $mobile_number_list_id, 
                            'sms_template_id' => $template->id
                        ],
                        [
                            'sent' => true, 
                            'sent_date' => now()
                        ]
                    );
                } else {
                    $errors[] = "Failed to send SMS to {$list_number->mobile_number}.";
                }
            } catch (\Exception $e) {
                Log::error('Twilio SMS Error: ' . $e->getMessage());
                $errors[] = "Failed to send SMS to {$list_number->mobile_number}.";
            }
        }

        if ($success_count > 0) {
            return redirect()->back()->with('success', "$success_count SMS sent successfully.");
        } else {
            return redirect()->back()->with('error', 'Failed to send SMS.')->with('detailed_errors', $errors);
        }
    }




}