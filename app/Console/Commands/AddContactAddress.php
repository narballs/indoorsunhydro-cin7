<?php

namespace App\Console\Commands;

use App\Models\Contact;
use App\Models\ContactsAddress;
use Illuminate\Console\Command;

class AddContactAddress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:AddNewContactAddress';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Repair Addresses Table for Contacts';

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
        $contacts = Contact::withTrashed()->where('is_parent', 1)
        ->where('contact_id','!=', null)
        ->where('parent_id', null)
        ->get();


        if (count($contacts) > 0 ) {
            foreach ($contacts as $contact) {
                $check_delivery_address = ContactsAddress::where('contact_id', $contact->id)->where('address_type', 'Shipping')->first();
                $check_billing_address = ContactsAddress::where('contact_id', $contact->id)->where('address_type', 'Billing')->first();
                if (empty($check_delivery_address)) {
                    $create_delivery_address = new ContactsAddress([
                        'contact_id' => $contact->id,
                        'DeliveryFirstName' => $contact->firstName,
                        'DeliveryLastName' => $contact->lastName,
                        'DeliveryCompany' => $contact->company,
                        'DeliveryAddress1' => $contact->address1,
                        'DeliveryAddress2' => $contact->address2,
                        'DeliveryCity' => $contact->city,
                        'DeliveryState' => $contact->state,
                        'DeliveryZip' => $contact->postCode,
                        'DeliveryCountry' => $contact->country,
                        'DeliveryPhone' => $contact->phone,
                        'is_default' => 1,
                        'address_type' => 'Shipping',
                    ]);
                    $create_delivery_address->save();
                } else {
                    $this->info('Delivery Address Already Exists');
                }

                if (empty($check_billing_address)) {
                    $create_billing_address = new ContactsAddress([
                        'contact_id' => $contact->id,
                        'BillingFirstName' => $contact->firstName,
                        'BillingLastName' => $contact->lastName,
                        'BillingCompany' => $contact->company,
                        'BillingAddress1' => $contact->address1,
                        'BillingAddress2' => $contact->address2,
                        'BillingCity' => $contact->city,
                        'BillingState' => $contact->state,
                        'BillingZip' => $contact->postCode,
                        'BillingCountry' => $contact->country,
                        'BillingPhone' => $contact->phone,
                        'is_default' => 1,
                        'address_type' => 'Billing',
                    ]);
                    $create_billing_address->save();
                } else {
                    $this->info('Billing Address Already Exists');
                }
            }

            $this->info('Contacts Addresses Added Successfully');
        } else {
            $this->info('No Contacts Found');
        }
    }
}
