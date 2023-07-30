<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Helpers\UtilHelper;
use DB;
use Illuminate\Support\Facades\Log;
use App\Models\Contact;
use App\Models\ApiOrder;
use App\Models\SecondaryContact;

use App\Helpers\SettingHelper;

class SyncContacts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The maximum number of unhandled exceptions to allow before failing.
     *
     * @var int
     */
    public $maxExceptions = 3;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 120;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $_method;
    protected $_body;
    protected $_apiBaseURL = 'https://api.cin7.com/api/';
    protected $_pathParam;


    public function __construct($method, $body, $pathParam = null)
    {
        $this->_method = $method;
        $this->_body = $body;
        $this->_pathParam = $pathParam;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        switch ($this->_method) {
            case 'create_contact':
                $res = UtilHelper::sendRequest('POST', $this->_apiBaseURL . 'v1/Contacts', $this->_body, []);
                break;
            case 'update_contact':
                $res = UtilHelper::sendRequest('PUT', $this->_apiBaseURL . 'v1/Contacts', $this->_body, []);
                break;
            case 'list_contact':
                $res = UtilHelper::sendRequest('GET', $this->_apiBaseURL . 'v1/Contacts', $this->_body, []);
                break;
            case 'retrive_contact':
                $res = UtilHelper::sendRequest('GET', $this->_apiBaseURL . 'v1/Contacts/' . $this->_pathParam, $this->_body, []);
                break;

            default:
                $res = UtilHelper::sendRequest('GET', $this->_apiBaseURL . 'v1/Contacts', $this->_body, []);
                break;
        }
        $response = json_decode($res);

        $contact_id = $response[0]->id;
        $code = $response[0]->code;
        echo $contact_id . '-----' . $code;
        if ($contact_id) {
            $contact = Contact::where('email', $code)->update(
                [
                    'contact_id' => $contact_id,
                    'status' => '1'
                ]
            );

            $user_id = Contact::where('email', $code)->pluck('user_id')->first();
            $api_order = ApiOrder::where('user_id', $user_id)->update(['memberId' => $contact_id]);
        }

        if ($this->_method == 'update_contact') {
            
            $client = new \GuzzleHttp\Client();
            $res = $client->request(
                'GET', 
                'https://api.cin7.com/api/v1/Contacts/'. $contact_id, 
                [
                    'auth' => [
                        SettingHelper::getSetting('cin7_auth_username'),
                        SettingHelper::getSetting('cin7_auth_password')
                    ]
                 
                ]
            );

            //dd($res->getBody()->getContents());
            $secondary_contacts = $res->getBody()->getContents();
            $secondary_contacts = json_decode($secondary_contacts, true);

            $last_contact = [];

            if (!empty($secondary_contacts['secondaryContacts'])) {
                $last_contact = end($secondary_contacts['secondaryContacts']);
            }

            $last_contact_id = isset($last_contact['id']) ? $last_contact['id'] : null;

            if (is_null($last_contact_id)) {
                print('Last contact id not found.');
                return;
            }
            //echo $last_contact_id;exit;
            $get_email = $this->_body[0]['secondaryContacts'][0]['email'];
            if(!empty($get_email)) {
                $contact = SecondaryContact::where('email',$get_email)->update(
                    [
                        'secondary_id' => $last_contact_id
                    ]
                );
            }else{
                return response()->json([
                    'message' => 'email not found!'
                ],400);
            }

            //$user_id = Contact::where('email', $code)->pluck('user_id')->first();
            //$api_order = ApiOrder::where('user_id', $user_id)->update(['memberId' => $last_contact_id]);
        }
    }
}
