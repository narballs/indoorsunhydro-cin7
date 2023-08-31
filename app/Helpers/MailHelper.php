<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Mail;
use App\Mail\Subscribe;

class MailHelper
{
    /**
     * get user option_name value.
     *
     * @param  method
     * @param  url
     * @param  body
     * @return extra
     */
    public static function sendMail($template,$name, $email, $subject, $reference, $order_items, $dateCreated, $addresses)
    {

        //return $template;
        // Mail::send($template,
        //     array(
        //         'name' =>  $name,
        //         'email' => $email,
        //         'subject' => $subject,
        //         'reference' => $reference,
        //         'order_items' => $order_items, 
        //         'dateCreated' => $dateCreated, 
        //         'addresses' => $addresses
        //     ), 
        //     function($message) use ($name, $email, $subject){
        //         $message->from('wqszeeshan@gmail.com');
        //         $message->to($email)->subject($subject);
        // });
    }


    public static function sendMailNotification($template, $data) {
        Mail::send($template, $data, function($message) use ($data){
            $message->from($data['from']);
            $message->to($data['email'])->subject($data['subject']);
            $message->to($data['user_email']->email)->subject('Your order has been placed');
        });
       
    }
}