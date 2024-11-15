<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Mail;
use App\Mail\Subscribe;
use Illuminate\Support\Facades\Log;

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
        });
       
    }

    public static function stockMailNotification($template, $data) {
        try {
            Mail::send($template, $data, function($message) use ($data) {
                $message->from($data['from']);
                $message->to($data['email'])->subject($data['subject']);
            });
    
            return true; // Email queued successfully
        } catch (\Exception $e) {
            // Log the error or handle it as per your application's requirements
            return false; // Email sending failed
        }
       
    }
    

    public static function send_discount_mail_request($template, $data) {
        Mail::send($template, $data, function($message) use ($data){
            $message->from($data['from']);
            $message->to($data['email'])->subject($data['subject']);
        });
    }

    

    public static function sendShipstationLabelMail($template, $data)
    {
        try {
            Mail::send($template, $data, function ($message) use ($data) {
                $message->from($data['from']);
                $message->to($data['email'])->subject($data['subject']);

                // Check if 'files' is provided and is an array
                if (!empty($data['files']) && is_array($data['files'])) {
                    foreach ($data['files'] as $file) {
                        // Ensure the file exists before attempting to attach it
                        if (file_exists(storage_path('app/' . $file))) {
                            $message->attach(storage_path('app/' . $file), [
                                'as' => basename($file),    // Filename in the email
                                'mime' => 'application/pdf' // MIME type for PDF
                            ]);
                        }
                    }
                }
            });

            return true; // Email queued successfully
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            Log::error('Failed to send email: ' . $e->getMessage());
            return false; // Email sending failed
        }
    }

    
    
}