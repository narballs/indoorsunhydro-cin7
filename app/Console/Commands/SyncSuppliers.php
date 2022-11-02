<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Contact;

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


        // Find total category pages
        //$total_products_pages = 44;
        // echo env('API_USER'); 
        // echo env('API_PASSWORD');
        // echo 'here';
        // exit;
        $total_contact_pages = 1;

        for ($i = 1; $i <= $total_contact_pages; $i++) {
            $this->info('Processing page#' . $i);

            $res = $client2->request(
                'GET', 
                'https://api.cin7.com/api/v1/Contacts/?page=' . $i,
                //'https://api.cin7.com/api/v1/Contacts/9888', 
                [
                    'auth' => [
                        'IndoorSunHydro2US', 
                        '764c3409324f4c14b5eadf8dcdd7dd2f'
                    ]
                ]
            );

            $api_contacts = $res->getBody()->getContents();
            $api_contacts = json_decode($api_contacts);
            //dd($api_contacts);
            //$brands = [];
            foreach($api_contacts as $api_contact) {
                $this->info($api_contact->id);
                //$brands[] = $api_product->brand;
                //dd($api_contact);
                $this->info('---------------------------------------');
                $this->info('Processing contacts ' . $api_contact->firstName);
                $this->info('---------------------------------------');
                $contact_data = [
                    'contact_id' => $api_contact->id,
                    'status' => $api_contact->isActive,
                    'type' => $api_contact->type,
                    'company' => $api_contact->company,
                    'firstName' => $api_contact->firstName,
                    'lastName' => $api_contact->lastName,
                    'priceColumn' => $api_contact->priceColumn,
                    'jobTitle'  => $api_contact->jobTitle,
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
                    
                ];
                //dd($contact_data);
                //$contact = new Contact;
                $contact = Contact::firstOrCreate(
                    [
                        'id' => $api_contact->id,
                        'contact_id' => $api_contact->id
                    ],
                    $contact_data 
                );
            }
        }
    }
}
