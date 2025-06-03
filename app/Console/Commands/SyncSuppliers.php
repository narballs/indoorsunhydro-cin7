<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Contact;
use App\Models\SecondaryContact;
use App\Models\UserLog;
use Carbon\Carbon;
use App\Models\ApiErrorLog;
use App\Models\ApiSyncLog;
use Illuminate\Http\Request;

use App\Helpers\UtilHelper;
use App\Helpers\SettingHelper;
use App\Helpers\UserHelper;
use App\Models\ApiKeys;
use App\Models\InvalidAddressUser;
use Illuminate\Support\Facades\Log;

class SyncSuppliers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:supplier';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $current_date = Carbon::now()->setTimezone('UTC')->format('Y-m-d H:i:s');
        $sync_log = ApiSyncLog::where('end_point', 'https://api.cin7.com/api/v1/Contacts')->first();
        if (empty($sync_log)) {
            $sync_log = new ApiSyncLog();
            $sync_log->end_point = 'https://api.cin7.com/api/v1/Contacts';
            $sync_log->description = 'Contacts Sync';
            $sync_log->record_count = 0;
            $sync_log->last_synced = $current_date;
            $sync_log->save();
        }

        $last_synced_date = $sync_log->last_synced;

        $total_record_count = 0;
        $email_array = [];
        $new_email_array = [];
        
        $this->info('Last updated time#--------------------------' . $last_synced_date);
        $this->info('Current time#--------------------------' . $current_date);

        $rawDate = Carbon::parse($last_synced_date);
        
        $getdate = $rawDate->format('Y-m-d');
        $getTime = $rawDate->format('H:i:s');


        $formattedDateSting = $getdate . 'T' . $getTime . 'Z';
        $client2 = new \GuzzleHttp\Client();
        $total_contact_pages = 150;
        $api_contact_ids = [];

        $cin7_auth_username = SettingHelper::getSetting('cin7_auth_username');
        $cin7_auth_password = SettingHelper::getSetting('cin7_auth_password');


        $cin7api_key_for_other_jobs =  ApiKeys::where('password', $cin7_auth_password)
        ->where('is_active', 1)
        ->where('is_stop', 0)
        ->first();

        $api_key_id = null;
        
        if (!empty($cin7api_key_for_other_jobs)) {
            $cin7_auth_username = $cin7api_key_for_other_jobs->username;
            $cin7_auth_password = $cin7api_key_for_other_jobs->password;
            $threshold = $cin7api_key_for_other_jobs->threshold;
            $request_count = !empty($cin7api_key_for_other_jobs->request_count) ? $cin7api_key_for_other_jobs->request_count : 0;
            $api_key_id = $cin7api_key_for_other_jobs->id;
        } else {
            Log::info('Cin7 API Key not found or inactive');
            return false;
        }


        if ($request_count >= $threshold) {
            Log::info('Request count exceeded');
            UtilHelper::send_threshold_alert_email($request_count , $threshold , $cin7api_key_for_other_jobs);
            return false;
        }


        $contacts_api_url = "https://api.cin7.com/api/v1/Contacts/";
        
        
        for ($i = 1; $i <= $total_contact_pages; $i++) {

            $this->info('Processing page#--------------------------' . $i);
            sleep(5);
            try {
                $res = $client2->request(
                    'GET',
                    'https://api.cin7.com/api/v1/Contacts?rows=250&where=modifieddate>=' . $formattedDateSting . '&page=' . $i,

                    [
                        'auth' => [
                            $cin7_auth_username,
                            $cin7_auth_password
                        ]
                    ]
                );

                UtilHelper::saveEndpointRequestLog('Sync Contacts' , $contacts_api_url , $api_key_id);
                UtilHelper::saveDailyApiLog('sync_contacts');

                $api_contacts = $res->getBody()->getContents();
                $api_contacts = json_decode($api_contacts);
                $record_count = count($api_contacts);
                $total_record_count += $record_count; 
                $this->info('Record Count per page #--------------------------' .$record_count);


                $this->info('Record Count => ' . $record_count);
                    
                if ($record_count < 1 || empty($record_count)) {
                    $this->info('----------------break-----------------');
                    break;
                }
            } catch (\Exception $e) {
                $msg = $e->getMessage();
                $errorlog = new ApiErrorLog();
                $errorlog->payload = $e->getMessage();
                $errorlog->exception = $e->getCode();
                $errorlog->save();
            }

            if ($api_contacts) {

                foreach ($api_contacts as $api_contact) {
                    $this->info($api_contact->id);
                    $this->info('Processing contacts ' . $api_contact->firstName);
                    $contact = Contact::where('contact_id', $api_contact->id)->first();
                    array_push($api_contact_ids, $api_contact->id);


                    $billing_contact_address_1 = !empty($contact) && !empty($contact->postalAddress1) ? $contact->postalAddress1 : '';
                    $billing_contact_address_2 = !empty($contact) && !empty($contact->postalAddress2) ? $contact->postalAddress2 : '';
                    $billing_contact_city = !empty($contact) && !empty($contact->postalCity) ? $contact->postalCity : '';
                    $billing_contact_state = !empty($contact) && !empty($contact->postalState) ? $contact->postalState : '';
                    $billing_contact_postal_code = !empty($contact) && !empty($contact->postalPostCode) ? $contact->postalPostCode : '';


                    $delivery_contact_address_1 = !empty($contact) && !empty($contact->address1) ? $contact->address1 : '';
                    $delivery_contact_address_2 = !empty($contact) && !empty($contact->address2) ? $contact->address2 : '';
                    $delivery_contact_city = !empty($contact) && !empty($contact->city) ? $contact->city : '';
                    $delivery_contact_state = !empty($contact) && !empty($contact->state) ? $contact->state : '';
                    $delivery_contact_postal_code = !empty($contact) && !empty($contact->postCode) ? $contact->postCode : '';


                    $billing_address_1 = !empty($api_contact->postalAddress1) ? $api_contact->postalAddress1 : $billing_contact_address_1;
                    $billing_address_2 = !empty($api_contact->postalAddress2) ? $api_contact->postalAddress2 : $billing_contact_address_2;
                    $billing_city = !empty($api_contact->postalCity) ? $api_contact->postalCity : $billing_contact_city;
                    $billing_state = !empty($api_contact->postalState) ? $api_contact->postalState : $billing_contact_state;
                    $billing_postal_code = !empty($api_contact->postalPostCode) ? $api_contact->postalPostCode : $billing_contact_postal_code;

                    $delivery_address_1 = !empty($api_contact->address1) ? $api_contact->address1 : $delivery_contact_address_1;
                    $delivery_address_2 = !empty($api_contact->address2) ? $api_contact->address2 : $delivery_contact_address_2;
                    $delivery_city = !empty($api_contact->city) ? $api_contact->city : $delivery_contact_city;
                    $delivery_state = !empty($api_contact->state) ? $api_contact->state : $delivery_contact_state;
                    $delivery_postal_code = !empty($api_contact->postCode) ? $api_contact->postCode : $delivery_contact_postal_code;


                    $validate_billing_address =  UserHelper::validateFullAddress($billing_address_1 , $billing_address_2 , $billing_city , $billing_state , $billing_postal_code, $country = 'USA');
                    $validate_delivery_address =  UserHelper::validateFullAddress($delivery_address_1 , $delivery_address_2 , $delivery_city , $delivery_state , $delivery_postal_code, $country = 'USA');
                    
                    if ($validate_billing_address['valid'] == false || $validate_delivery_address['valid'] == false) {
                        // $this->info('Invalid address for contact id: ' . $api_contact->id);
                        // $errorlog = new ApiErrorLog();
                        // $errorlog->payload = 'Invalid address for contact id: ' . $api_contact->id;
                        // $errorlog->exception = 'Invalid address';
                        // $errorlog->save();

                        $check_invalid_address_user = InvalidAddressUser::where('email', $api_contact->email)->first();
                        
                        if (empty($check_invalid_address_user)) {
                            $invalid_address_user = new InvalidAddressUser();
                            $invalid_address_user->email = $api_contact->email;
                            $invalid_address_user->firstName = $api_contact->firstName;
                            $invalid_address_user->lastName = $api_contact->lastName;
                            $invalid_address_user->billing_address_1 = $billing_address_1;
                            $invalid_address_user->billing_address_2 = $billing_address_2;
                            $invalid_address_user->billing_city = $billing_city;
                            $invalid_address_user->billing_state = $billing_state;
                            $invalid_address_user->billing_postal_code = $billing_postal_code;
                            $invalid_address_user->shipping_address_1 = $delivery_address_1;
                            $invalid_address_user->shipping_address_2 = $delivery_address_2;
                            $invalid_address_user->shipping_city = $delivery_city;
                            $invalid_address_user->shipping_state = $delivery_state;
                            $invalid_address_user->shipping_postal_code = $delivery_postal_code;
                            $invalid_address_user->save();
                        } else {
                            $check_invalid_address_user->email = $api_contact->email;
                            $check_invalid_address_user->firstName = $api_contact->firstName;
                            $check_invalid_address_user->lastName = $api_contact->lastName;
                            $check_invalid_address_user->billing_address_1 = $billing_address_1;
                            $check_invalid_address_user->billing_address_2 = $billing_address_2;
                            $check_invalid_address_user->billing_city = $billing_city;
                            $check_invalid_address_user->billing_state = $billing_state;
                            $check_invalid_address_user->billing_postal_code = $billing_postal_code;
                            $check_invalid_address_user->shipping_address_1 = $delivery_address_1;
                            $check_invalid_address_user->shipping_address_2 = $delivery_address_2;
                            $check_invalid_address_user->shipping_city = $delivery_city;
                            $check_invalid_address_user->shipping_state = $delivery_state;
                            $check_invalid_address_user->shipping_postal_code = $delivery_postal_code;
                            $check_invalid_address_user->save();
                        }


                        continue;
                    }
                    
                    
                    if (!empty($contact)) {
                        $this->info($api_contact->id);
                        $this->info('---------------------------------------');
                        $this->info('Processing contacts ' . $api_contact->firstName);
                        $this->info('---------------------------------------');
                        $contact->contact_id = $api_contact->id;
                        $contact->is_parent = 1;
                        $contact->status = $api_contact->isActive;
                        $contact->type = $api_contact->type;
                        $contact->company = $api_contact->company;
                        $contact->firstName = $api_contact->firstName;
                        $contact->lastName = $api_contact->lastName;
                        $contact->priceColumn = $api_contact->priceColumn;
                        $contact->jobTitle  = $api_contact->jobTitle;
                        $contact->mobile = $api_contact->mobile;
                        $contact->phone = $api_contact->phone;
                        $contact->address1 = $api_contact->address1;
                        $contact->address2 = $api_contact->address2;
                        $contact->city = $api_contact->city;
                        $contact->state = $api_contact->state;
                        $contact->postCode = $api_contact->postCode;
                        $contact->postalAddress1 = $api_contact->postalAddress1;
                        $contact->postalAddress2 = $api_contact->postalAddress2;
                        $contact->postalCity = $api_contact->postalCity;
                        $contact->postalState = $api_contact->postalState;
                        $contact->postalPostCode = $api_contact->postalPostCode;
                        $contact->fax = $api_contact->fax;
                        $contact->website = $api_contact->website;
                        $contact->email = $api_contact->email;
                        $contact->credit_limit = $api_contact->creditLimit;
                        $contact->balance_owing = $api_contact->balanceOwing;
                        $contact->paymentTerms = $api_contact->paymentTerms;
                        if (!empty($api_contact->taxStatus)) {
                            $contact->tax_class = $api_contact->taxStatus;
                        } else {
                            $contact->tax_class = strtolower($api_contact->postalState) == strtolower('California') ? '8.75%' : 'Out of State';
                        }
                        $contact->notes = $api_contact->notes;
                        $contact->save();

                        if (!empty($api_contact->secondaryContacts)) {
                            foreach ($api_contact->secondaryContacts as $apiSecondaryContact) {
                                $secondary_contact = Contact::where('secondary_id', $apiSecondaryContact->id)
                                    ->where('parent_id', $contact->contact_id)->first();
                                $email_array[] = $apiSecondaryContact->email;
                                // $deleteing_secondary_contact = Contact::where('parent_id' , $contact->contact_id)->where('is_parent' , 0)->get();
                                // foreach($deleteing_secondary_contact as $deleteing_secondary_contact){
                                //     if ($deleteing_secondary_contact->secondary_id != $apiSecondaryContact->id) {
                                //         $deleteing_secondary_contact->update([
                                //             'is_deleted' => now()
                                //         ]);

                                //         if (!empty($deleteing_secondary_contact->is_deleted)) {
                                //             $user_log = new UserLog();
                                //             // $user_log->user_id = auth()->user()->id;
                                //             $user_log->contact_id = !empty($deleteing_secondary_contact->contact_id) ? $deleteing_secondary_contact->contact_id : $deleteing_secondary_contact->id;
                                //             $user_log->secondary_id = !empty($deleteing_secondary_contact->secondary_id) ? $deleteing_secondary_contact->secondary_id : $deleteing_secondary_contact->id;
                                //             $user_log->action = 'Deletion';
                                //             $user_log->user_notes = !empty($deleteing_secondary_contact->email) ? $deleteing_secondary_contact->email . ' ' . 'is ' . 'deleted in qcom at' .' '. now() . '  because it was deleted in cin7' : $deleteing_secondary_contact->firstName .' '. $deleteing_secondary_contact->lastName  . ' ' . 'is ' . 'deleted in qcom at' .' '. now() . '  because it was deleted in cin7';
                                //             $user_log->save();
                                //         }

                                //         $deleteing_secondary_contact->delete();
                                //     }
                                // }
                                if ($secondary_contact) {

                                    $secondary_contact->secondary_id = $apiSecondaryContact->id;
                                    $secondary_contact->is_parent = 0;
                                    $secondary_contact->company = $contact->company;
                                    $secondary_contact->firstName = $apiSecondaryContact->firstName;
                                    $secondary_contact->lastName = $apiSecondaryContact->lastName;
                                    $secondary_contact->jobTitle  = $apiSecondaryContact->jobTitle;
                                    $secondary_contact->email = $apiSecondaryContact->email;
                                    $secondary_contact->mobile = $apiSecondaryContact->mobile;
                                    $secondary_contact->phone = $apiSecondaryContact->phone;
                                    $secondary_contact->priceColumn = $api_contact->priceColumn;
                                    $secondary_contact->credit_limit = $api_contact->creditLimit;
                                    $secondary_contact->balance_owing = $api_contact->balanceOwing;
                                    $secondary_contact->paymentTerms = $api_contact->paymentTerms;
                                    if (!empty($api_contact->taxStatus)) {
                                        $secondary_contact->tax_class = $api_contact->taxStatus;
                                    } else {
                                        $secondary_contact->tax_class = strtolower($api_contact->postalState) == strtolower('California') ? '8.75%' : 'Out of State';
                                    }
                                    if ($secondary_contact->status == 0) {
                                        $secondary_contact->status = 0;
                                    } else {
                                        $secondary_contact->status = 1;
                                    }
                                    $secondary_contact->save();
                                } else {
                                    $secondary_contact = new Contact();

                                    // parent_id
                                    $secondary_contact->parent_id = $contact->contact_id;

                                    $secondary_contact->secondary_id = $apiSecondaryContact->id;
                                    $secondary_contact->is_parent = 0;
                                    $secondary_contact->company = $contact->company;
                                    $secondary_contact->firstName = $apiSecondaryContact->firstName;
                                    $secondary_contact->lastName = $apiSecondaryContact->lastName;
                                    $secondary_contact->jobTitle  = $apiSecondaryContact->jobTitle;
                                    $secondary_contact->email = $apiSecondaryContact->email;
                                    $secondary_contact->mobile = $apiSecondaryContact->mobile;
                                    $secondary_contact->phone = $apiSecondaryContact->phone;
                                    $secondary_contact->priceColumn = $api_contact->priceColumn;
                                    $secondary_contact->credit_limit = $api_contact->creditLimit;
                                    $secondary_contact->balance_owing = $api_contact->balanceOwing;
                                    $secondary_contact->paymentTerms = $api_contact->paymentTerms;
                                    if (!empty($api_contact->taxStatus)) {
                                        $secondary_contact->tax_class = $api_contact->taxStatus;
                                    } else {
                                        $secondary_contact->tax_class = strtolower($api_contact->postalState) == strtolower('California') ? '8.75%' : 'Out of State';
                                    }
                                    $secondary_contact->status = 1;
                                    $secondary_contact->save();
                                }

                                $UserLog = new UserLog([
                                    'action' => 'Sync',
                                    'user_notes' => 'Sync from Cin7 at ' . Carbon::now()->toDateTimeString() . 'is Secondary Contacts ' . 'and primary account is ' . $api_contact->email,
                                ]);
                                $UserLog->save();
                            }

                            $deleting_secondary_by_emails = Contact::where('parent_id' , $api_contact->id)
                            ->where('is_parent' , 0)
                            ->whereNotIn('email', $email_array)
                            ->get();

                            if (count($deleting_secondary_by_emails) > 0) {
                                foreach($deleting_secondary_by_emails as $deleting_secondary_by_email){
                                    $deleting_secondary_by_email->update([
                                        'is_deleted' => now()
                                    ]);

                                    $user_log = new UserLog();
                                        // $user_log->user_id = auth()->user()->id;
                                    $user_log->contact_id = !empty($deleting_secondary_by_email->contact_id) ? $deleting_secondary_by_email->contact_id : $deleting_secondary_by_email->id;
                                    $user_log->secondary_id = !empty($deleting_secondary_by_email->secondary_id) ? $deleting_secondary_by_email->secondary_id : $deleting_secondary_by_email->id;
                                    $user_log->action = 'Deletion';
                                    $user_log->user_notes = !empty($deleting_secondary_by_email->email) ? $deleting_secondary_by_email->email . ' ' . 'is ' . 'deleted in qcom at' .' '. now() . '  because it was deleted in cin7' : $deleting_secondary_by_email->firstName .' '. $deleting_secondary_by_email->lastName  . ' ' . 'is ' . 'deleted in qcom at' .' '. now() . '  because it was deleted in cin7';
                                    $user_log->save();
                                    $deleting_secondary_by_email->delete();
                                }
                            }
                                
                                        
                        } 
                        else {
                            $deleteing_secondary_contact = Contact::where('parent_id' , $contact->contact_id)->where('is_parent' , 0)->get();
                            foreach($deleteing_secondary_contact as $deleteing_secondary_contact){
                                $deleteing_secondary_contact->update([
                                    'is_deleted' => now()
                                ]);
                                if (!empty($deleteing_secondary_contact->is_deleted)) {
                                    $user_log = new UserLog();
                                    // $user_log->user_id = auth()->user()->id;
                                    $user_log->contact_id = !empty($deleteing_secondary_contact->contact_id) ? $deleteing_secondary_contact->contact_id : $deleteing_secondary_contact->id;
                                    $user_log->secondary_id = !empty($deleteing_secondary_contact->secondary_id) ? $deleteing_secondary_contact->secondary_id : $deleteing_secondary_contact->id;
                                    $user_log->action = 'Deletion';
                                    $user_log->user_notes = !empty($deleteing_secondary_contact->email) ? $deleteing_secondary_contact->email . ' ' . 'is ' . 'deleted in qcom at' .' '. now() . '  because it was deleted in cin7' : $deleteing_secondary_contact->firstName .' '. $deleteing_secondary_contact->lastName  . ' ' . 'is ' . 'deleted in qcom at' .' '. now() . '  because it was deleted in cin7';
                                    $user_log->save();
                                    $deleteing_secondary_contact->delete();
                                }


                            }
                        }
                    } else {
                        foreach ($api_contact->secondaryContacts as $secondaryContact) {
                            echo $secondaryContact->id . '---' . $secondaryContact->firstName;
                        }
                        if (!empty($api_contact->taxStatus)) {
                            $tax_class = $api_contact->taxStatus;
                        } else {
                            $tax_class = strtolower($api_contact->postalState) == strtolower('California') ? '8.75%' : 'Out of State';
                        }
                        $contact = new Contact([
                            'contact_id' => $api_contact->id,
                            'is_parent' => 1,
                            'status' => $api_contact->isActive,
                            'type' => $api_contact->type,
                            'company' => $api_contact->company,
                            'firstName' => $api_contact->firstName,
                            'lastName' => $api_contact->lastName,
                            'priceColumn' => $api_contact->priceColumn,
                            'jobTitle' => $api_contact->jobTitle,
                            'mobile' => $api_contact->mobile,
                            'phone' => $api_contact->phone,
                            'address1' => $api_contact->address1,
                            'address2' => $api_contact->address2,
                            'state' => $api_contact->state,
                            'postCode' => $api_contact->postCode,
                            'postalAddress1' => $api_contact->postalAddress1,
                            'postalAddress2' => $api_contact->postalAddress2,
                            'postalCity' => $api_contact->postalCity,
                            'postalState' => $api_contact->postalState,
                            'postalPostCode' => $api_contact->postalPostCode,
                            'fax' => $api_contact->fax,
                            'website' => $api_contact->website,
                            'email' => $api_contact->email,
                            'credit_limit' => $api_contact->creditLimit,
                            'balance_owing' => $api_contact->balanceOwing,
                            'tax_class' => $tax_class,
                            'paymentTerms' => $api_contact->paymentTerms,
                            'notes' => $api_contact->notes
                        ]);
                        if ($api_contact->secondaryContacts) {
                            foreach ($api_contact->secondaryContacts as $secondaryContact) {
                                $new_email_array[] = $secondaryContact->email;
                                // $deleteing_secondary_contact = Contact::where('parent_id' , $api_contact->id)->get();
                                // foreach($deleteing_secondary_contact as $deleteing_secondary_contact){
                                //     if ($deleteing_secondary_contact->secondary_id != $secondaryContact->id) {
                                //         $deleteing_secondary_contact->update([
                                //             'is_deleted' => now()
                                //         ]);
                                //         if (!empty($deleteing_secondary_contact->is_deleted)) {
                                //             $user_log = new UserLog();
                                //             // $user_log->user_id = auth()->user()->id;
                                //             $user_log->contact_id = !empty($deleteing_secondary_contact->contact_id) ? $deleteing_secondary_contact->contact_id : $deleteing_secondary_contact->id;
                                //             $user_log->secondary_id = !empty($deleteing_secondary_contact->secondary_id) ? $deleteing_secondary_contact->secondary_id : $deleteing_secondary_contact->id;
                                //             $user_log->action = 'Deletion';
                                //             $user_log->user_notes = !empty($deleteing_secondary_contact->email) ? $deleteing_secondary_contact->email . ' ' . 'is ' . 'deleted in qcom at' .' '. now() . '  because it was deleted in cin7' : $deleteing_secondary_contact->firstName .' '. $deleteing_secondary_contact->lastName . ' ' . 'is ' . 'deleted in qcom at' .' '. now() . '  because it was deleted in cin7';
                                //             $user_log->save();
                                //         }


                                //         $deleteing_secondary_contact->delete();
                                //     }
                                // }
                                $secondaryContact = new Contact([
                                    'secondary_id' => $secondaryContact->id,
                                    'parent_id'  => $api_contact->id,
                                    'is_parent' => 0,
                                    'status' => 1,
                                    'firstName' => $secondaryContact->firstName,
                                    'lastName' => $secondaryContact->lastName,
                                    'jobTitle' => $secondaryContact->jobTitle,
                                    'company' => $secondaryContact->company,
                                    'priceColumn' => $api_contact->priceColumn,
                                    'phone' => $secondaryContact->phone,
                                    'mobile' => $secondaryContact->mobile,
                                    'email' => $secondaryContact->email,
                                    'credit_limit' => $api_contact->creditLimit,
                                    'paymentTerms' => $api_contact->paymentTerms,
                                    'tax_class' => $tax_class,

                                ]);
                                $secondaryContact->save();

                                $deleting_secondary_by_emails = Contact::where('parent_id' , $api_contact->id)
                                ->where('is_parent' , 0)
                                ->whereNotIn('email', $new_email_array)
                                ->get();

                                if (count($deleting_secondary_by_emails) > 0) {
                                    foreach($deleting_secondary_by_emails as $deleting_secondary_by_email){
                                        $deleting_secondary_by_email->update([
                                            'is_deleted' => now()
                                        ]);

                                        $user_log = new UserLog();
                                            // $user_log->user_id = auth()->user()->id;
                                        $user_log->contact_id = !empty($deleting_secondary_by_email->contact_id) ? $deleting_secondary_by_email->contact_id : $deleting_secondary_by_email->id;
                                        $user_log->secondary_id = !empty($deleting_secondary_by_email->secondary_id) ? $deleting_secondary_by_email->secondary_id : $deleting_secondary_by_email->id;
                                        $user_log->action = 'Deletion';
                                        $user_log->user_notes = !empty($deleting_secondary_by_email->email) ? $deleting_secondary_by_email->email . ' ' . 'is ' . 'deleted in qcom at' .' '. now() . '  because it was deleted in cin7' : $deleting_secondary_by_email->firstName .' '. $deleting_secondary_by_email->lastName  . ' ' . 'is ' . 'deleted in qcom at' .' '. now() . '  because it was deleted in cin7';
                                        $user_log->save();
                                        $deleting_secondary_by_email->delete();
                                    }
                                }
                            }
                        }
                        $contact->save();

                        $UserLog = new UserLog([
                            'contact_id' => $api_contact->id,
                            'action' => 'Sync',
                            'user_notes' => 'Sync from Cin7 at ' . Carbon::now()->toDateTimeString(),
                        ]);
                        $UserLog->save();
                    }
                }


                $sync_log->last_synced = $current_date;
                $sync_log->record_count = $total_record_count;
                $sync_log->save();
             }
        }
        

        // if ($record_count > 0) {
          
        // }


        //com_contact_id = Contact::where('is_parent', 1)->pluck('contact_id')->toArray();

        //his->info(count($qcom_contact_id));
       //this->info(count($api_contact_ids));
        //ifferences = array_diff($qcom_contact_id, $api_contact_ids);
        //foreach ($differences as $difference) {
            //$contact = Contact::where('contact_id', $difference)->first();
            //$contact->status = 0;
            //$UserLog = new UserLog([
               // 'contact_id' => $difference,
                //'action' => 'Sync',
               // 'user_notes' => 'Disabled during Sync at ' . Carbon::now()->toDateTimeString() . 'Not found in cin7',
           // ]);
            //$UserLog->save();
            //$contact->save();
        //}
    }
}
