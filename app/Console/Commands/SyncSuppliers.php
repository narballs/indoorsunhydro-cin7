<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Contact;
use App\Models\SecondaryContact;
use App\Models\UserLog;
use Carbon\Carbon;



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
        $client2 = new \GuzzleHttp\Client();

        $total_contact_pages = 35;

        for ($i = 1; $i <= $total_contact_pages; $i++) {
            $this->info('Processing page#' . $i);
            sleep(3);
            $res = $client2->request(
                'GET', 
                'https://api.cin7.com/api/v1/Contacts/?page=' . $i,
                [
                    'auth' => [
                        // env('API_USER'),
                        // env('API_PASSWORD')
                        'IndoorSunHydroUS',
                        'faada8a7a5ef4f90abaabb63e078b5c1'
                    ]
                ]
            );

            $api_contacts = $res->getBody()->getContents();
            $api_contacts = json_decode($api_contacts);
            foreach($api_contacts as $api_contact) {
                $this->info($api_contact->id);
                $this->info('Processing contacts ' . $api_contact->firstName);
                $contact = Contact::where('contact_id', $api_contact->id)->first();
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
                    $contact->address1 = $api_contact->address2;
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
                    $contact->notes = $api_contact->notes;
                    if ($api_contact->secondaryContacts) 
                        // dd($api_contact->secondaryContacts);
                    {
                        foreach($api_contact->secondaryContacts as $secondaryContact) {
                            $contact->parent_id = $contact->contact_id;
                            $contact->secondary_id = $secondaryContact->id;
                            $contact->is_parent = 0;
                            $contact->company = $secondaryContact->company;
                            $contact->firstName = $secondaryContact->firstName;
                            $contact->lastName = $secondaryContact->lastName;
                            $contact->jobTitle  = $secondaryContact->jobTitle;
                            $contact->email = $secondaryContact->email;
                            $contact->mobile = $secondaryContact->mobile;
                            $contact->phone = $secondaryContact->phone;
                            $contact->save();

                            $UserLog = new UserLog([
                               'action' => 'Sync',
                               'user_notes' => 'Sync from Cin7 at '.Carbon::now()->toDateTimeString(). 'is Secondary Contacts '.'and primary account is '.$api_contact->email, 
                                ]);       
                            $UserLog->save();
                        }
                    }
                    $contact->save();
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
                        'address1' => $api_contact->address2,
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
                        'notes' => $api_contact->notes 
                    ]);
                    if ($api_contact->secondaryContacts) {
                        foreach($api_contact->secondaryContacts as $secondaryContact) {
                            $secondaryContact = new Contact ([
                              'secondary_id' => $secondaryContact->id,
                              'parent_id'  => $api_contact->id,
                              'is_parent' => 0,
                              'firstName' => $secondaryContact->firstName,
                              'lastName' => $secondaryContact->lastName,
                              'jobTitle' => $secondaryContact->jobTitle,
                              'company' => $secondaryContact->company,
                              'phone' => $secondaryContact->phone,
                              'mobile' => $secondaryContact->mobile,
                             'email' => $secondaryContact->email,
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
    }
}
