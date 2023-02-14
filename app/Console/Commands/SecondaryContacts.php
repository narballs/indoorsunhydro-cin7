<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SecondaryContact;

class SecondaryContacts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Sync:SecondaryContacts';

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
        $total_contact_pages = 35;
        $client2 = new \GuzzleHttp\Client();

        for ($i = 1; $i <= $total_contact_pages; $i++) {
            $this->info('Processing page#' . $i);
            sleep(3);
            $res = $client2->request(
                'GET', 
                'https://api.cin7.com/api/v1/Contacts/?page=' . $i,
                [
                    'auth' => [
                        'IndoorSunHydroUS', 
                        'faada8a7a5ef4f90abaabb63e078b5c1'
                    ]
                ]
            );
            $contacts = $res->getBody()->getContents();
            $contacts = json_decode($contacts);
            
                
                foreach($contacts as $contact) {
                    $parent_id = $contact->id;
                    foreach($contact->secondaryContacts as $secondaryContact) {
                        $contact = new SecondaryContact([
                            'secondary_id' => $secondaryContact->id,
                            'parent_id' => $parent_id,
                            'company' => $secondaryContact->company,
                            'firstName' => $secondaryContact->firstName,
                            'lastName' => $secondaryContact->lastName,
                            'jobTitle' => $secondaryContact->jobTitle,
                            'email' => $secondaryContact->email,
                            'mobile' => $secondaryContact->mobile,
                            'phone' => $secondaryContact->phone
                        ]);
                        $contact->save();
                    }
                }
        }
    }
}
