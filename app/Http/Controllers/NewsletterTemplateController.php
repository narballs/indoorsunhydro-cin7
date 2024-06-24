<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewsletterTemplate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsletterTemplateController extends Controller
{
    public function index()
    {
        $templates = NewsletterTemplate::all();
        return view('newsletter_layout.newsletter_templates.index', compact('templates'));
    }

    public function create()
    {
        return view('newsletter_layout.newsletter_templates.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'content' => 'required',
        ]);

        NewsletterTemplate::create([
            'name' => $request->name,
            'content' => $request->content,
        ]);

        return redirect()->route('newsletter-templates.index')->with('success', 'Newsletter template created successfully!');
    }


    public function edit_newsletter_template($id)
    {
        $newsletterTemplate = NewsletterTemplate::find($id);
        return view('newsletter_layout.newsletter_templates.edit', compact('newsletterTemplate'));
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

        return redirect()->route('newsletter-templates.index')->with('success', 'Newsletter template updated successfully!');
    }


    public function delete_newsletter_template($id)
    {
        $newsletterTemplate = NewsletterTemplate::find($id);
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
