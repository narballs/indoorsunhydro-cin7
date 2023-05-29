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
        $sync_time  = Carbon::now()->toIso8601String();
        $sync_log = ApiSyncLog::where('end_point', 'https://api.cin7.com/api/v1/Contacts')->first();

        $date = $sync_log->last_synced;

        $rawDate = Carbon::parse($date);
        $formattedDateSting = $rawDate->toDateTimeString().'T'.'00:00:00'.'Z';  
        $client2 = new \GuzzleHttp\Client();
        $total_contact_pages = 150;
        $api_contact_ids = [];
        


        for ($i = 1; $i <= $total_contact_pages; $i++) {

            $this->info('Processing page#--------------------------' . $i);
            sleep(5);
            try {
                $res = $client2->request(
                    'GET', 
                    'https://api.cin7.com/api/v1/Contacts?where=modifieddate>='. $formattedDateSting .'&page='.$i,
                    [
                        'auth' => [
                            env('API_USER'),
                            env('API_PASSWORD')
                        ]
                    ]
                );

                $api_contacts = $res->getBody()->getContents();
                $api_contacts = json_decode($api_contacts);
                $record_count = count($api_contacts);
                $this->info('Record Count => '. $record_count);
                if ($record_count < 1 || empty($record_count)) {
                    $this->info('----------------break-----------------');
                    break;
                }
            }
            catch (\Exception $e) {
                $msg = $e->getMessage();
                $errorlog = new ApiErrorLog();
                $errorlog->payload = $e->getMessage();
                $errorlog->exception = $e->getCode();
                $errorlog->save();

            }

            

           
            foreach($api_contacts as $api_contact) {
                $this->info($api_contact->id);
                $this->info('Processing contacts ' . $api_contact->firstName);
                $contact = Contact::where('contact_id', $api_contact->id)->first();
                array_push($api_contact_ids, $api_contact->id);
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
                    $contact->notes = $api_contact->notes;
                    $contact->save();

                    if ($api_contact->secondaryContacts) 
                    {
                        foreach($api_contact->secondaryContacts as $apiSecondaryContact) {
                            $secondary_contact = Contact::where('secondary_id', $apiSecondaryContact->id)
                                ->where('parent_id', $contact->contact_id)
                                ->first();
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
                                if ($secondary_contact->status == 0) {
                                    $secondary_contact->status = 0;
                                }
                                else {
                                    $secondary_contact->status = 1;
                                }
                                $secondary_contact->save();
                            }
                            else {
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
                                $secondary->contact->credit_limit = $api_contact->creditLimit;
                                $secondary->contact->balance_owing = $api_contact->balanceOwing;
                                $secondary_contact->status = 1;
                                $secondary_contact->save();
                            }

                            

                            $UserLog = new UserLog([
                               'action' => 'Sync',
                               'user_notes' => 'Sync from Cin7 at '.Carbon::now()->toDateTimeString(). 'is Secondary Contacts '.'and primary account is '.$api_contact->email, 
                            ]);       
                            $UserLog->save();
                        }
                    }
                }
                else {
                    foreach($api_contact->secondaryContacts as $secondaryContact) {
                        echo $secondaryContact->id.'---'.$secondaryContact->firstName;
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
                        'balance_owing' =>$api_contact->balanceOwing,
                        'notes' => $api_contact->notes 
                    ]);
                    if ($api_contact->secondaryContacts) {
                        foreach($api_contact->secondaryContacts as $secondaryContact) {
                            $secondaryContact = new Contact ([
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
                                'credit_limit' => $api_contact->creditLimit

                            ]);
                            $secondaryContact->save();
                        }
                    }
                    $contact->save();

                    $UserLog = new UserLog([
                        'contact_id' => $api_contact->id,
                        'action' => 'Sync',
                        'user_notes' => 'Sync from Cin7 at '.Carbon::now()->toDateTimeString(),        
                    ]);
                    $UserLog->save();
                }
            }
        }
        $current_date = Carbon::now()->toDateString().'T'.'00:00:00'.'Z';
       
        if ($record_count > 0) {
            $sync_log->last_synced = $current_date;
            $sync_log->save();
        }
       
        $qcom_contact_id = Contact::where('is_parent', 1)->pluck('contact_id')->toArray();
  

        $this->info(count($qcom_contact_id));  
        $this->info(count($api_contact_ids));
        $differences = array_diff($qcom_contact_id, $api_contact_ids);
        foreach($differences as $difference) {
            $contact = Contact::where('contact_id', $difference)->first();
            $contact->status = 0;
               $UserLog = new UserLog([
                        'contact_id' => $difference,
                        'action' => 'Sync',
                        'user_notes' => 'Disabled during Sync at '.Carbon::now()->toDateTimeString() .'Not found in cin7',        
                    ]);
                $UserLog->save();
            $contact->save();
        }
    }
}
