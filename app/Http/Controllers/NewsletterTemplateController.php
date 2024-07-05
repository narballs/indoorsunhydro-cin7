<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriberTemplate;
use Illuminate\Http\Request;
use App\Models\NewsletterTemplate;
use App\Models\SubscriberList;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsletterTemplateController extends Controller
{
    public function index()
    {
        $templates = NewsletterTemplate::with('sent_newsletter' , 'sent_newsletter.subscriber_email_list')->orderBy('created_at' , 'DESC')->get();
        return view('newsletter_layout.newsletter_templates.index', compact('templates'));
    }

    public function create()
    {
        $subscriber_email_lists = SubscriberList::all();
        return view('newsletter_layout.newsletter_templates.create', compact('subscriber_email_lists'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'content' => 'required',
        ]);
        $newsletter_template = new NewsletterTemplate();
        $newsletter_template->name = $request->name;
        $newsletter_template->content = $request->content;
        $newsletter_template->save();

        $check_newsletter_list_entry = NewsletterSubscriberTemplate::where('list_id', $request->subscriber_email_list_id)->where('newsletter_template_id', $newsletter_template->id)->first();
        if (!empty($check_newsletter_list_entry)) {
            $delete_newsletter_template = NewsletterTemplate::find($newsletter_template->id);
            $delete_newsletter_template->delete();
            return redirect()->back()->with('error', 'This newsletter template is already assigned to the selected subscriber list!');
        } 
        else {
            $newsletter_subscriber_template = new NewsletterSubscriberTemplate();
            $newsletter_subscriber_template->list_id = $request->subscriber_email_list_id;
            $newsletter_subscriber_template->newsletter_template_id = $newsletter_template->id;
            $newsletter_subscriber_template->save();
        }

        return redirect()->route('newsletter-templates.index')->with('success', 'Newsletter template created successfully!');
    }


    public function edit_newsletter_template($id)
    {
        $newsletterTemplate = NewsletterTemplate::find($id);
        $subscriber_email_lists = SubscriberList::all();
        $selected_id  = null;
        if (count($subscriber_email_lists) > 0) {
            foreach ($subscriber_email_lists as $subscriber_email_list) {
                $check_newsletter_list_entry = NewsletterSubscriberTemplate::where('list_id', $subscriber_email_list->id)->where('newsletter_template_id', $newsletterTemplate->id)->first();
                if (!empty($check_newsletter_list_entry)) {
                    $selected_id = $subscriber_email_list->id;
                    break;
                }
            }
        }
        return view('newsletter_layout.newsletter_templates.edit', compact('newsletterTemplate' , 'subscriber_email_lists' , 'selected_id'));
    }

    public function duplicate_newsletter_template($id) {
        $newsletterTemplate = NewsletterTemplate::find($id);
        $newNewsletterTemplate = $newsletterTemplate->replicate();
        $newNewsletterTemplate->name = $newsletterTemplate->name . ' (Copy)';
        $newNewsletterTemplate->save();

        return redirect()->route('newsletter-templates.index')->with('success', 'Newsletter template duplicated successfully!');
    }

    public function update_newsletter_template(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'content' => 'required',
        ]);

        $newsletterTemplate = NewsletterTemplate::find($id);
        $newsletterTemplate->name = $request->name;
        $newsletterTemplate->content = $request->content;
        $newsletterTemplate->save();


        if (!empty($request->subscriber_email_list_id)) {
            $check_newsletter_list_entry = NewsletterSubscriberTemplate::where('list_id', $request->subscriber_email_list_id)->where('newsletter_template_id', $newsletterTemplate->id)->first();
        
            if (!empty($check_newsletter_list_entry)) {
                return redirect()->back()->with('error', 'This newsletter template is already assigned to the selected subscriber list!');
            } 
            else {
                $newsletter_subscriber_template = new NewsletterSubscriberTemplate();
                $newsletter_subscriber_template->list_id = $request->subscriber_email_list_id;
                $newsletter_subscriber_template->newsletter_template_id = $newsletterTemplate->id;
                $newsletter_subscriber_template->save();
            }
        }

        return redirect()->route('newsletter-templates.index')->with('success', 'Newsletter template updated successfully!');
    }


    public function delete_newsletter_template($id)
    {
        $newsletterTemplate = NewsletterTemplate::find($id);
        $delete_newsletter_subscriber_template = NewsletterSubscriberTemplate::where('newsletter_template_id', $newsletterTemplate->id)->first();
        $delete_newsletter_subscriber_template->delete();
        $newsletterTemplate->delete();
        return redirect()->route('newsletter-templates.index')->with('success', 'Newsletter template deleted successfully!');
    }

    public function newsletter_templates_detail($id) {
        $newsletter_template = NewsletterTemplate::find($id);
        return view('newsletter_layout.newsletter_templates.detail', compact('newsletter_template'));
    }

    public function upload_newsletterImage(Request $request)
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('/theme/bootstrap5/newsletter_images');
            
            // Create the directory if it doesn't exist
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            
            $file->move($destinationPath, $filename);

            $url = asset('theme/bootstrap5/newsletter_images/' . $filename);

            return response()->json([
                'uploaded' => true,
                'url' => $url,
            ]);
        }

        return response()->json([
            'uploaded' => false,
            'error' => [
                'message' => 'No file uploaded.'
            ]
        ]);
    }
}
