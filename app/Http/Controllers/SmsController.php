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

    public function sms_detail($id) {
        $smsTemplate = SmsTemplate::find($id);
        return view('newsletter_layout.sms_template.detail', compact('smsTemplate'));
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

    public function add_mobile_numbers_to_list(Request $request)
    {
        try {
            $request->validate([
                'mobile_number' => [
                    'required',
                    'string',
                    'regex:/^[^a-zA-Z]*$/'
                ],
                'tags' => 'nullable|string',
                'list_id' => 'required|exists:number_lists,id' // Validate list_id exists in number_lists table
            ]);

            $mobileNumber = $request->input('mobile_number');
            $tags = $request->input('tags', '');
            $listId = $request->input('list_id');

            // Check if the mobile number already exists in MobileNumberList for the given list_id
            $existingNumberList = MobileNumberList::where('mobile_number', $mobileNumber)
                ->where('number_list_id', $listId)
                ->first();

            // If mobile number does not exist in MobileNumberList for the list_id, add it
            if (!$existingNumberList) {
                MobileNumberList::create([
                    'mobile_number' => $mobileNumber,
                    'number_list_id' => $listId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Check if the mobile number already exists in MobileNumber
            $existingMobileNumber = MobileNumber::where('mobile_number', $mobileNumber)->first();

            // If mobile number does not exist in MobileNumber, add it
            if (!$existingMobileNumber) {
                MobileNumber::create([
                    'mobile_number' => $mobileNumber,
                    'tags' => $tags,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Mobile Number added successfully.']);
        } catch (ValidationException $e) {
            return response()->json(['error' => true, 'message' => 'Validation error. Please check your input.'], 400);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Something went wrong!'], 500);
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
            ]);

            // Return validation errors if validation fails
            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()], 400);
            }

            // Retrieve data from the request
            $listId = $request->input('list_id');
            $tags = $request->input('tags', '');
            $bulkNumbers = explode("\n", $request->input('bulk_upload_numbers'));

            $validNumbers = [];
            foreach ($bulkNumbers as $number) {
                $number = trim($number);
                if (!empty($number) && (preg_match('/^[^a-zA-Z]*$/', $number))) { // Adjust validation as per your requirements
                    // Check if number already exists in MobileNumber
                    $existingNumber = MobileNumber::where('mobile_number', $number)->first();

                    if (!$existingNumber) {
                        // Number does not exist in MobileNumber, so insert it
                        MobileNumber::create([
                            'mobile_number' => $number,
                            'tags' => $tags,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }

                    // Check if number exists in MobileNumberList for the current list_id
                    $existingListNumber = MobileNumberList::where('mobile_number', $number)
                        ->where('number_list_id', $listId)
                        ->first();

                    if (!$existingListNumber) {
                        // Number does not exist in MobileNumberList for the current list_id, so add it
                        $validNumbers[] = [
                            'mobile_number' => $number,
                            'number_list_id' => $listId,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    }
                }
            }

            // Insert valid numbers into database
            if (!empty($validNumbers)) {
                MobileNumberList::insert($validNumbers);

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
                // 'list_id' => 'required|exists:number_list,id' // Validate list_id exists in number_lists table
            ]);

            // Return validation errors if validation fails
            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()], 400);
            }

            $listId = $request->input('list_id');
            $tags = $request->input('tags', '');
            $file = $request->file('file');

            // Process the uploaded file to retrieve valid numbers
            $importedNumbers = $this->processImportedFile($file);

            // Initialize arrays for numbers to insert and skipped numbers
            $numbersToInsert = [];
            $skippedNumbers = [];

            // Iterate over imported numbers
            foreach ($importedNumbers as $number) {
                if (!empty($number)) {
                    // Check if number exists in MobileNumber
                    $existingNumber = MobileNumber::where('mobile_number', $number)->first();

                    if (!$existingNumber) {
                        // Number does not exist in MobileNumber, so insert it
                        MobileNumber::create([
                            'mobile_number' => $number,
                            'tags' => $tags,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }

                    // Check if number exists in MobileNumberList for the current list_id
                    $existingNumberList = MobileNumberList::where('mobile_number', $number)
                        ->where('number_list_id', $listId)
                        ->first();

                    if (!$existingNumberList) {
                        // Number does not exist in MobileNumberList for the current list_id, so add it
                        $numbersToInsert[] = [
                            'mobile_number' => $number,
                            'number_list_id' => $listId,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    } else {
                        // Number exists in MobileNumberList for the current list_id, skip it
                        $skippedNumbers[] = $number;
                    }
                } else {
                    // Invalid number format, skip it
                    $skippedNumbers[] = $number;
                }
            }

            // Insert valid numbers into MobileNumberList
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
                'skipped_numbers' => $skippedMessage,
            ]);
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

            // Define possible variations of number column headers
            $numberHeaders = ['mobile_number', 'numbers', 'Numbers', 'Mobile Number', 'Mobile Numbers', 'mobile numbers' ,'mobile number'];

            // Initialize variables
            $header = null;
            $numberIndex = null;

            // Read file based on extension
            if ($extension === 'csv') {
                $fileHandle = fopen($path, 'r'); // Open file handle
                if (!$fileHandle) {
                    throw new \Exception('Failed to open CSV file.');
                }
                $header = fgetcsv($fileHandle); // Read headers

                // Reset file pointer to beginning for Excel processing
                fseek($fileHandle, 0);

                // Process CSV file
                while ($columns = fgetcsv($fileHandle)) {
                    if ($header === null) {
                        $header = $columns;
                        continue; // Skip header line
                    }

                    // Find the correct number column index based on header case insensitivity
                    foreach ($numberHeaders as $numberHeader) {
                        $numberIndex = array_search($numberHeader, $header, true);
                        if ($numberIndex !== false) {
                            break;
                        }
                    }

                    if ($numberIndex !== false && isset($columns[$numberIndex])) {
                        $number = trim($columns[$numberIndex]);
                        // Validate number format and disallow both lowercase and uppercase alphabetic characters
                        if (preg_match('/^[^a-zA-Z]*$/', $number)) {
                            $importedNumbers[] = $number;
                        }
                    }
                }
                fclose($fileHandle);
            } 
            else {
                $header = (new HeadingRowImport())->toArray($file)[0][0];
                // Process Excel file   
                $rows = Excel::toArray([], $file)[0];
                foreach ($rows as $key => $row) {
                    if ($key === 0) {
                        continue; // Skip header row
                    }

                    // Find the correct number column index based on header case insensitivity
                    foreach ($numberHeaders as $numberHeader) {
                        $numberIndex = array_search($numberHeader, $header);
                        if ($numberIndex !== false) {
                            break;
                        }
                    }

                    if ($numberIndex !== false && isset($row[$numberIndex])) {
                        $number = trim($row[$numberIndex]);
                        // Validate number format and disallow both lowercase and uppercase alphabetic characters
                        if (preg_match('/^[^a-zA-Z]*$/', $number)) {
                            $importedNumbers[] = $number;
                        }
                    }
                }
            }

            // Remove duplicates from imported numbers
            $importedNumbers = array_unique($importedNumbers);


            if (empty($importedNumbers)) {
                throw new \Exception('No valid numbers found in the file.');
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
        // $twilio_number ='+12512921422';

        $client = new \Twilio\Rest\Client($twilio_sid->option_value, $twilio_token->option_value);
            

        $errors = [];
        $success_count = 0;

        foreach ($number_list as $list_number) {
            try {
                $response =  $client->messages->create($list_number->mobile_number, [
                    'from' =>$twilio_number->option_value,
                    'body' => strip_tags($template->description),
                ]);

                MobileNumberCampaign::updateOrCreate(
                    ['mobile_number_list_id' => $mobile_number_list_id, 'sms_template_id' => $template_id],
                    [   
                        'sent' => true, 
                        'sent_date' => now()
                    ]
                );

                $success_count++;
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


    public function sms_template_duplicate (Request $request , $id) {
        $sms_template = SmsTemplate::find($id);
        $sms_template = $sms_template->replicate();
        $sms_template->name = $sms_template->name . ' (Copy)';
        $sms_template->save();


        if (!empty($request->subscriber_list_id)) {
            $sms_template_campaign = new MobileNumberCampaign();
            $sms_template_campaign->mobile_number_list_id = $request->subscriber_list_id;
            $sms_template_campaign->sms_template_id = $sms_template->id;
            $sms_template_campaign->save();
        }

        return redirect()->route('list_sms_templates')->with('success', 'Sms template duplicated successfully!');
    }




}