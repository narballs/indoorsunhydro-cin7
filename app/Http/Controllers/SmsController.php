<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\MobileNumber;
use App\Models\MobileNumberCampaign;
use App\Models\MobileNumberList;
use App\Models\NumberList;
use App\Models\SmsTemplate;

class SmsController extends Controller
{ 
    function __construct()
    {
        $this->middleware(['role:Newsletter']);
    }

    public function sms_list()
    {
        $number_lists = NumberList::orderBy('id', 'desc')->paginate(10);
        return view('newsletter_layout.number_list.index' , compact('number_lists'));
    }
    public function sms_list_create()
    {
        return view('newsletter_layout.number_list.create');
    }

    public function sms_list_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            // 'url' => 'required',
            // 'description' => 'required',
        ]);

        $subscribers_list = new NumberList();
        $subscribers_list->name = $request->name;
        $subscribers_list->url = $request->url;
        $subscribers_list->description = $request->description;
        $subscribers_list->save();

        return redirect()->route('sms_list')->with('success', 'Numbers list created successfully!');
    }

    public function sms_list_edit($id)
    {
        $number_list = NumberList::find($id);
        return view('newsletter_layout.number_list.create' , compact('number_list'));
    }

    public function sms_list_update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            // 'url' => 'required',
            // 'description' => 'required',
        ]);

        $subscribers_list = NumberList::find($id);
        $subscribers_list->name = $request->name;
        $subscribers_list->url = $request->url;
        $subscribers_list->description = $request->description;
        $subscribers_list->save();

        return redirect()->route('sms_list')->with('success', 'Numbers list updated successfully!');
    }

    
    public function sms_list_delete($id) {
        $subscribers_list = NumberList::findOrFail($id);
        $mobile_number_list = MobileNumberList::where('number_list_id', $id)->get();
        if (count($mobile_number_list) > 0) {
            foreach ($mobile_number_list as $subscriber_email) {
                $subscriber_email->delete();
            }
        }
        
        $subscribers_list->delete();

        return redirect()->back()->with('success', 'Subscribers list deleted successfully!');
    }


    // sms campaigns

    // public function sms_campaigns()
    // {
    //     $templates = MobileNumberCampaign::with('sent_newsletter' , 'sent_newsletter.subscriber_email_list')->orderBy('created_at' , 'DESC')->get();
    //     return view('newsletter_layout.sms_campaigns.index' , compact('mobile_numbers'));
    // }


    // Sms templates

    public function list_sms_templates() {

        $templates = SmsTemplate::orderBy('created_at' , 'DESC')->get();
        return view('newsletter_layout.sms_template.index', compact('templates'));

    }

    public function create_sms_template() {
        $number_list = NumberList::all();
        return view('newsletter_layout.sms_template.create', compact('number_list'));
    }

    public function store_sms_template(Request $request) {

    }

    public function edit_sms_template($id) {

    }

    public function update_sms_template(Request $request , $id) {

    }

    public function delete_sms_template($id) {

    }






}