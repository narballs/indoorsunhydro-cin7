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


    // public static function SendWholesalePaymentInvoice($template, $invoice , $email) {
    //     Mail::send($template, $invoice, function($message) use ($invoice , $email){
    //         $message->from(SettingHelper::getSetting('noreply_email_address'));
    //         $message->to($email)->subject('Wholesale Payment Invoice');
    //     });
    // }

    public static function SendWholesalePaymentInvoice($template, $invoice, $email) {

        Log::info("Sending Wholesale Payment Invoice to: " . $email , $invoice);

        try {

            if (is_array($invoice) && !empty($invoice)) {
                $invoice = (object) $invoice;
            }


            // Send email
            Mail::send($template, ['invoice' => $invoice], function($message) use ($invoice, $email) {
                // Set sender email
                $message->from(SettingHelper::getSetting('noreply_email_address'));
    
                // Set recipient email
                $message->to($email)->subject('Wholesale Payment Invoice' ); // Add dynamic invoice number in subject
            });
            
            // Log successful email send
            Log::info("Wholesale Payment Invoice sent successfully to: " . $email);
        } catch (\Exception $e) {
            // Log any errors that occur
            Log::error("Error sending Wholesale Payment Invoice: " . $invoice->invoice_number . " to: " . $email . " - " . $e->getMessage());
            Log::error("Error sending Wholesale Payment Invoice: " . $e->getMessage());
        }
    }
    

    

    // public static function sendShipstationLabelMail($template, $data)
    // {
    //     try {
    //         Mail::send($template, $data, function ($message) use ($data) {
    //             $message->from($data['from']);
    //             $message->to($data['email'])->subject($data['subject']);

    //             // Check if 'files' is provided and is an array
    //             if (!empty($data['files']) && is_array($data['files'])) {
    //                 foreach ($data['files'] as $file) {
    //                     // Ensure the file exists before attempting to attach it
    //                     if (file_exists(storage_path('app/public' . $file))) {
    //                         $message->attach(storage_path('app/public' . $file), [
    //                             'as' => basename($file),    // Filename in the email
    //                             'mime' => 'application/pdf' // MIME type for PDF
    //                         ]);
    //                     }
    //                 }
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
            // Ensure the email data has the necessary fields for sending
            if (empty($data['email']) || empty($data['from']) || empty($data['subject'])) {
                throw new \Exception('Email data is missing required fields.');
            }

            // Get the packing slip file name and label file name from the data
            $packingSlipFileName = $data['packingSlipFileName'];
            $file_name = $data['labelFileName'];

            // Define the full file paths
            $packingSlipPath = public_path('packing_slips/' . $packingSlipFileName);
            $labelPath = public_path('labels/' . $file_name);

            // Send email with attachments
            Mail::send($template, $data, function ($message) use ($data, $packingSlipFileName, $file_name, $packingSlipPath, $labelPath) {
                $message->from($data['from']);
                $message->to($data['email'])->subject($data['subject']);

                // Attach Packing Slip
                if (file_exists($packingSlipPath)) {
                    $message->attach($packingSlipPath, [
                        'as' => $packingSlipFileName,
                        'mime' => 'application/pdf'
                    ]);
                }

                // Attach Label
                if (file_exists($labelPath)) {
                    $message->attach($labelPath, [
                        'as' => $file_name,
                        'mime' => 'application/pdf'
                    ]);
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