<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Mail;
use App\Mail\Subscribe;

use App\Models\Contact;

use Session;

class UserHelper
{
    /**
     * get user option_name value.
     *
     * @param  method
     * @param  url
     * @param  body
     * @return extra
     */
    public static function getAllMemberIds($user) {
        $user_ids = $parent_ids = $contact_ids = [];

        $contacts_by_email = Contact::where('email', $user->email)->get();

        if (!empty($contacts_by_email)) {
            foreach ($contacts_by_email as $email_contact) {
                if (!empty($email_contact->user_id)) {
                    $user_ids[] = $email_contact->user_id;
                }

                if (!empty($email_contact->parent_id)) {
                    $parent_ids[] = $email_contact->parent_id;
                }

                if (!empty($email_contact->contact_id)) {
                    $contact_ids[] = $email_contact->contact_id;
                }
            }
        }

        $ids_array_1 = $ids_array_2 = $ids_array_3 = [];

        if (!empty($user_ids)) {
            $ids_array_1 = Contact::whereIn('user_id', $user_ids)->pluck('id')->toArray();
        }

        if (!empty($contact_ids)) {
            $ids_array_2 = Contact::whereIn('parent_id', $contact_ids)->pluck('id')->toArray();
        }

        if (!empty($parent_ids)) {
            $ids_array_3 = Contact::whereIn('contact_id', $parent_ids)->pluck('id')->toArray();
        }

        $member_ids = array_merge($ids_array_1, $ids_array_2, $ids_array_3);
        return $member_ids;
    }

    public static function switch_company($contact_id) {
        $contact = Contact::where('contact_id', $contact_id)->where('status', '!=', 0)->first();
        if (!empty($contact)) {
            $active_contact_id = $contact->contact_id;
            $active_company = $contact->company;
            Session::put([
                'contact_id' => $active_contact_id,
                'company' => $active_company
            ]);

        } else {
            $contact = Contact::where('secondary_id', $contact_id)->where('status', '!=', 0)->first();
            $active_contact_id = $contact->secondary_id;
            $active_company = $contact->company;
            Session::put([
                'contact_id' => $active_contact_id,
                'company' => $active_company
            ]);
        }

        return response()->json([
            'status' => '204',
            'message' => 'Company Switch Successfully !'
        ]);
    }
}