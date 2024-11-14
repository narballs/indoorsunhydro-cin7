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

    // public static function sendShipstationLabelMail($template, $data) {
    //     try {
    //         Mail::send($template, $data, function($message) use ($data) {
    //             $message->from($data['from']);
    //             $message->to($data['email'])->subject($data['subject']);
    
    //             // Check if a file is provided and exists before attaching
    //             if (!empty($data['file']) && file_exists(storage_path('app/' . $data['file']))) {
    //                 $message->attach(storage_path('app/' . $data['file']), [
    //                     'as' => basename($data['file']), // Filename in the email
    //                     'mime' => 'application/pdf',     // MIME type for PDF
    //                 ]);
    //             }
    //         });

    //         return true; // Email queued successfully
    //     } catch (\Exception $e) {
    //         // Log the error or handle it as needed

    //         Log::error('Failed to send email: ' . $e->getMessage());
    //         return false; // Email sending failed
    //     }
    // }

    public static function sendShipstationLabelMail($template, $data) {
        try {
            Mail::send($template, $data, function($message) use ($data) {
                $message->from($data['from']);
                $message->to($data['email'])->subject($data['subject']);
    
                // Loop through each file in the 'files' array and attach if it exists
                if (!empty($data['files'])) {
                    foreach ($data['files'] as $filename => $path) {
                        if (file_exists($path)) {
                            $message->attach($path, [
                                'as' => basename($path), // Filename as it appears in the email
                                'mime' => 'application/pdf', // Assuming PDF for all attachments
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
    

    // public static function sendShipstationLabelMail($data) {
    //     Mail::send([], [], function($message) use ($data) {
    //         $message->from($data['from']);
    //         $message->to($data['email'])->subject($data['subject']);
            
    //         // Attach the label PDF to the email
    //         $message->attach(storage_path('app/' . $data['file']), [
    //             'as' => basename($data['file']), // Filename in the email
    //             'mime' => 'application/pdf',     // MIME type for PDF
    //         ]);
    
    //         // Generate body content using $data['content']
    //         $content = "Shipping to:\n" .
    //                    $data['content']['company'] . "\n" .
    //                    $data['content']['name'] . "\n" .
    //                    $data['content']['street1'] . "\n" .
    //                    $data['content']['street2'] . "\n" .
    //                    $data['content']['street3'] . "\n" .
    //                    $data['content']['city'] . ', ' . $data['content']['state'] . ' ' . $data['content']['postalCode'] . "\n" .
    //                    $data['content']['country'];
    
    //         // Set the email body with the shipping details
    //         $message->setBody('Your shipment label is attached.' . "\n\n" . $content, 'text/plain');
    //     });
    // }
    
    
}