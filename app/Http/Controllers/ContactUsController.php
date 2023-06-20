<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactUs;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\JsonResponse;
use App\Mail\Subscribe;
use App\Http\Requests\Users\ContactUsRequest;
use App\Services\ZendeskService;
use Zendesk\API\HttpClient as ZendeskClient;

class ContactUsController extends Controller
{
    
    // protected $zendeskService;

    // public function __construct(ZendeskService $zendeskService)
    // {
    //     $this->zendeskService = $zendeskService;
    // }
    
    public function index() {
        return view('contact-us');
    }

    public function store(ContactUsRequest $request) {
        $this->validate($request, [
            'subject' => 'required',
            'message' => 'required',
            'name' => 'required|min:1',
            'email' => 'required|email',
        ]);

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

        
        $subdomain = env('ZENDESK_SUBDOMAIN'); 
        $username = env('ZENDESK_USERNAME'); 
        $token =  env('ZENDESK_TOKEN'); 
        $auth = [
            'token' => $token, 
        ];
        
        $client = new ZendeskClient($subdomain);
        $client->setAuth('basic', ['username' => $username, 'token' => $token]);

        $subject = $request->input('subject');
        $description = $request->input('message');
        $requesterName = $request->input('name');
        $requesterEmail = $request->input('email');

        $ticketData = [
            'subject' => $subject,
            'description' => $description,
            'requester' => [
                'email' => $requesterEmail,
                'name' => $requesterName,
            ],
        ];

        $response = $client->tickets()->create($ticketData);
        // $ticketId = $response->ticket->id;

        // Mail::send('emails.subscribers',
        //     array(
        //         'name' => $request->get('name'),
        //         'email' => $request->get('email'),
        //         'subject' => $request->get('subject'),
        //         'message' => $request->get('message'),

        //     ), function($message) use ($contact, $email, $subject){
        
        //     $message->from('wqszeeshan@gmail.com');
        //     $message->to($email)->subject($subject);
        // });
        return response()->json(['success' => true, 'created'=> true, 'msg' => "Thank you. Your request has been received. You'll get an email notification confirming a ticket has been created."]);
    }
}

