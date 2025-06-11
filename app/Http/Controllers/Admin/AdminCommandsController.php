<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\SettingHelper;
use App\Helpers\UtilHelper;
use \App\Http\Controllers\Controller;
use App\Http\Middleware\IsAdmin;
use App\Models\ApiErrorLog;
use App\Models\ApiKeys;
use App\Models\Contact;
use App\Models\User;
use App\Models\UserLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminCommandsController extends Controller
{
    
    public function import_contacts(Request $request) {

        try {
            Artisan::call('sync:supplier');
            Artisan::call('ContactsTo:Users');
            Artisan::call('Assign:UserToContacts');

            return response()->json([
                'status' => 'success', 
                'message' => 'Contacts imported successfully.'
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'status' => 'error', 
                'message' => $e->getMessage()
            ]);
        }

        

    }

    public function update_product_prices () {
        try {
            Artisan::call('Sync:ProductOptions');
            return response()->json([
                'status' => 'success', 
                'message' => 'Product prices updated successfully.'
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'status' => 'error', 
                'message' => $e->getMessage()
            ]);
        }
    }

    public function reset_cin7_api_keys () {
        try {
            Artisan::call('reset:cin7_api_keys');
            return redirect()->back()->with('success', 'Cin7 API keys reset successfully.');
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function send_stock_summary_emails () {
        try {
            Artisan::call('report:daily-user-stock-requests');
            return redirect()->back()->with('success', 'Stock summary command executed successfully.');
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    

    public function import_specific_contact(Request $request) {
        $email = $request->email;
        $check_email = User::where('email', $email)->first();

        if ($check_email) {
            return response()->json([
                'status' => 'error',
                'message' => 'User with this email already exists.'
            ]);
        }

        $cin7_auth_username = SettingHelper::getSetting('cin7_auth_username');
        $cin7_auth_password = SettingHelper::getSetting('cin7_auth_password');

        $cin7api_key_for_other_jobs = ApiKeys::where('password', $cin7_auth_password)
            ->where('is_active', 1)
            ->where('is_stop', 0)
            ->first();

        if (!$cin7api_key_for_other_jobs) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cin7 API key not found.'
            ]);
        }

        $cin7_auth_username = $cin7api_key_for_other_jobs->username;
        $cin7_auth_password = $cin7api_key_for_other_jobs->password;
        $threshold = $cin7api_key_for_other_jobs->threshold;
        $request_count = $cin7api_key_for_other_jobs->request_count ?? 0;
        $api_key_id = $cin7api_key_for_other_jobs->id;

        if ($request_count >= $threshold) {
            UtilHelper::send_threshold_alert_email($request_count, $threshold, $cin7api_key_for_other_jobs);
            return response()->json([
                'status' => 'error',
                'message' => 'Cin7 API key request count has reached the threshold limit.'
            ]);
        }

        // $contacts_api_url = "https://api.cin7.com/api/v1/Contacts?where=email='{$email}'&limit=1";
        $where = urlencode("email='$email'");
        $contacts_api_url = "https://api.cin7.com/api/v1/Contacts?where={$where}&limit=1";
        $client = new Client();

        try {
            $res = $client->request('GET', $contacts_api_url, [
                'auth' => [$cin7_auth_username, $cin7_auth_password]
            ]);

            UtilHelper::saveEndpointRequestLog('Sync Contacts', $contacts_api_url, $api_key_id);
            UtilHelper::saveDailyApiLog('sync_contacts');

            $apiContact = json_decode($res->getBody()->getContents());

            Log::info('API Contact Response: ', ['response' => $apiContact]);

            // Correct check to ensure exactly 1 contact is returned
            if (!is_array($apiContact) || count($apiContact) < 1 || !isset($apiContact[0])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No contact found with this email.'
                ]);
            }

            // Import the contact and its secondary contacts
            $this->importingSpecificContact($apiContact[0]);

            return response()->json([
                'status' => 'success',
                'message' => 'Contact imported successfully.'
            ]);
        } catch (\Exception $e) {

            ApiErrorLog::create([
                'payload' => $e->getMessage(),
                'exception' => $e->getCode()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to import contact, please check the logs for more details.'
            ]);
        }
    }


    public function importingSpecificContact($api_contact)
    {
        DB::transaction(function () use ($api_contact) {
            $tax_class = !empty($api_contact->taxStatus)
                ? $api_contact->taxStatus
                : (strtolower($api_contact->postalState) === 'california' ? '8.75%' : 'Out of State');

            $contact = Contact::create([
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

            $user = User::firstOrCreate(
                ['email' => $api_contact->email],
                ['name' => trim("{$api_contact->firstName} {$api_contact->lastName}") ?: $api_contact->company]
            );

            $contact->update(['user_id' => $user->id]);

            $this->syncSecondaryContacts($api_contact, $tax_class);

            $this->logSync($api_contact->id);
        });
    }


    protected function syncSecondaryContacts($api_contact, $tax_class)
    {
        $new_email_array = [];

        if (!empty($api_contact->secondaryContacts)) {
            foreach ($api_contact->secondaryContacts as $secondary) {
                $new_email_array[] = $secondary->email;

                $contact = Contact::updateOrCreate(
                    ['secondary_id' => $secondary->id],
                    [
                        'parent_id' => $api_contact->id,
                        'is_parent' => 0,
                        'status' => 1,
                        'firstName' => $secondary->firstName,
                        'lastName' => $secondary->lastName,
                        'jobTitle' => $secondary->jobTitle,
                        'company' => $secondary->company,
                        'priceColumn' => $api_contact->priceColumn,
                        'phone' => $secondary->phone,
                        'mobile' => $secondary->mobile,
                        'email' => $secondary->email,
                        'credit_limit' => $api_contact->creditLimit,
                        'paymentTerms' => $api_contact->paymentTerms,
                        'tax_class' => $tax_class,
                    ]
                );

                $user = User::firstOrCreate(
                    ['email' => $secondary->email],
                    ['name' => trim("{$secondary->firstName} {$secondary->lastName}") ?: $secondary->company]
                );

                $contact->update(['user_id' => $user->id]);
            }

            $old_contacts = Contact::where('parent_id', $api_contact->id)
                ->where('is_parent', 0)
                ->whereNotIn('email', $new_email_array)
                ->get();

            foreach ($old_contacts as $old) {
                $old->update(['is_deleted' => now()]);
                UserLog::create([
                    'contact_id' => $old->contact_id ?? $old->id,
                    'secondary_id' => $old->secondary_id ?? $old->id,
                    'action' => 'Deletion',
                    'user_notes' => ($old->email ?? "{$old->firstName} {$old->lastName}") . ' is deleted in qcom at ' . now() . ' because it was deleted in cin7',
                ]);
                $old->delete();
            }
        }
    }


    protected function logSync($contact_id)
    {
        UserLog::create([
            'contact_id' => $contact_id,
            'action' => 'Sync',
            'user_notes' => 'Sync from Cin7 at ' . Carbon::now()->toDateTimeString(),
        ]);
    }


    
}