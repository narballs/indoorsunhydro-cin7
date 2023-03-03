<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Branch;
class SyncBranches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

     protected $signature = 'Sync:Branches';

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
        $total_branch_pages = 1;
        $client2 = new \GuzzleHttp\Client();

        for ($i = 1; $i <= $total_branch_pages; $i++) {
            $this->info('Processing page#' . $i);
            sleep(3);
            $res = $client2->request(
                'GET', 
                'https://api.cin7.com/api/v1/Branches/?page=' . $i,
                [
                    'auth' => [
                       env('API_USER'),
                    env('API_PASSWORD')
                    ]
                ]
            );

            $branches = $res->getBody()->getContents();
            $branches = json_decode($branches);
        
                foreach($branches as $branch) {
                        $branch = new Branch([
                          'branchId' => $branch->id,
                          'secondaryContactId' => "",
                          'branchLocationId' => "",
                          'isActive' => $branch->isActive,
                          'company' =>  $branch->company,
                          'firstName' =>  $branch->firstName,
                          'lastName' =>  $branch->lastName,
                          'jobTitle' =>  $branch->jobTitle,
                          'email' =>  $branch->email,
                          'website' =>  $branch->website,
                          'phone' =>  $branch->phone,
                          'fax' =>  $branch->fax,
                          'mobile' =>  $branch->mobile,
                          'address1' =>  $branch->address1,
                          'address2' =>  $branch->address2,
                          'city' =>  $branch->city,
                          'state' =>  $branch->state,
                          'postCode' => $branch->postCode,
                          'country' =>  $branch->country,
                          'postalAddress1' =>  $branch->postalAddress1,
                          'postalAddress2' =>  $branch->postalAddress2,
                          'postalCity' =>  $branch->postalCity,
                          'postalPostCode' =>  $branch->postalPostCode,
                          'postalState' =>  $branch->postalState,
                          'postalCountry' =>  $branch->postalCountry,
                          'notes' =>  $branch->notes,
                          'integrationRef' =>  $branch->integrationRef,
                            $customFields = [
                            'customFields' => $branch->customFields,
                          ],
                          'branchType' =>  $branch->branchType,
                          'stockControlOptions' =>  $branch->stockControlOptions,
                          'taxStatus' =>  $branch->taxStatus,
                          'accountNumber' =>  $branch->accountNumber,
                        ]);

                        $branch->save();
                    }
            }
        }
    }
