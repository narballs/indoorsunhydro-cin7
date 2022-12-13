<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactUs;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\JsonResponse;
use App\Mail\Subscribe;
use App\Http\Requests\Users\ContactUsRequest;

class ContactUsController extends Controller
{
    public function index() {
        return view('contact-us');
    }

    public function store(ContactUsRequest $request) {
        $contact = new ContactUs([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'subject' => $request->get('subject'),
            'message' => $request->get('message')
        ]);
        $contact->save();
        $query_id = $contact->id;
        $query = ContactUs::where('id', $query_id)->first();
        $name = $query->name;
        $email = $query->email;
        $subject = $query->subject;

        Mail::send('emails.subscribers',
            array(
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'subject' => $request->get('subject'),
                'message' => $request->get('message'),

            ), function($message) use ($contact, $email, $subject){
        
            $message->from('wqszeeshan@gmail.com');
            $message->to($email)->subject($subject);
        });
        // Mail::to($request->get('email'))->send(new Subscribe($request->get('email')));
        // //     return new JsonResponse(
        // //     [
        // //         'success' => true, 
        // //         'message' => "Thank you for subscribing to our email, please check your inbox"
        // //     ], 
        // //     200
        // // );
         return response()->json(['success' => true, 'created'=> true, 'msg' => 'Thank you for subscribing to our email, please check your inbox.']);
    // return back()->with('success', 'We have received your message and would like to thank you for writing to us.');
    }
}

