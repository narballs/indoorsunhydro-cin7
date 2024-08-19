<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\NavHelper;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Faq;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;




class PagesController extends Controller
{
    public function index()
    {
        $pages = Page::paginate(10);
        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'name' => 'required',
            // 'title' => 'required',
            // 'banner_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // 'description' => 'required',
        ]);

        $banner_image = null;
        if ($request->hasFile('banner_image')) {
            $image = $request->file('banner_image');
            $banner_image = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('pages/banners');
            File::makeDirectory($destinationPath, $mode = 0777, true, true);
            $image->move($destinationPath, $banner_image);
        }
        try {
            $page = Page::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'banner_image' => $banner_image,
                'title' => $request->title,
                'description' => $request->description,
                'status' => $request->status,

            ]);

            
            return redirect()->route('pages.index')->with('success', 'Page created successfully.');
        }
        catch(\Exception $e) {
            return redirect()->route('pages.index')->with('error', 'Something went wrong !');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $page = Page::findOrFail($id);
        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required',
            // 'title' => 'required',
            // 'banner_image' => 'required|max:2048',
            // 'description' => 'required',
        ]);
        $page = Page::findOrFail($id);
        try {
            $Image =  null;
            if ($banner_image = $request->file('banner_image')) {
                $destinationPath = public_path('pages/banners');
                $Image = time() . "." . $banner_image->getClientOriginalExtension();
                $banner_image->move($destinationPath, $Image);
                File::makeDirectory($destinationPath, $mode = 0777, true, true);
                $request['banner_image'] = "$Image";

            } else {
                $Image = $page->banner_image;
            }
            $page->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'banner_image' => $Image,
                'title' => $request->title,
                'description' => $request->description,
                'status' => $request->status,
            ]);
            return redirect()->route('pages.index')->with('success', 'Page updated successfully.');
        } catch (\Exception $e) {
            // Log the exception
            Log::info('Exception: ' . $e->getMessage());
            Log::info('Stack trace: ' . $e->getTraceAsString());
            return redirect()->route('pages.index')->with('error', 'Something went wrong !');
            // Handle the exception (e.g., return an error response, redirect, etc.)
        }
        
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
            
        $page = Page::findOrFail($id);
        $page->delete();
        return redirect()->route('pages.index')->with('success', 'Page deleted successfully.');      
    }

    public function image_upload(Request $request)
    {
        
        if($request->hasFile('upload')) {
            //get filename with extension
            $filenamewithextension = $request->file('upload')->getClientOriginalName();
    
            //get filename without extension
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
    
            //get file extension
            $extension = $request->file('upload')->getClientOriginalExtension();
    
            //filename to store
            $filenametostore = $filename.'_'.time().'.'.$extension;
    
            //Upload File
            $request->file('upload')->storeAs('public/uploads', $filenametostore);
            $request->file('upload')->storeAs('public/uploads/thumbnail', $filenametostore);

            //Resize image here
            $thumbnailpath = public_path('storage/uploads/thumbnail/'.$filenametostore);
            $img = Image::make($thumbnailpath)->resize(200, 150, function($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($thumbnailpath);

            echo json_encode([
                'default' => asset('storage/uploads/'.$filenametostore),
                '500' => asset('storage/uploads/thumbnail/'.$filenametostore)
            ]);
        }
    }

    // faq page section 

    public function create_faq() {
        return view('admin.pages.faqs.create');
    }


    public function store_faq(Request $request) {
        $validated = $request->validate([
            'question' => 'required',
            'answer' => 'required',
        ]);
        $faq = Faq::create([
            'question' => $request->question,
            'answer' => $request->answer,
            'status' => $request->status,
        ]);
        return redirect()->route('faqs.index')->with('success', 'Faq created successfully.');
    }

    public function faqs() {
        $faqs = Faq::paginate(10);
        return view('admin.pages.faqs.index', compact('faqs'));
    }

    public function edit_faq($id) {
        $faq = Faq::findOrFail($id);
        return view('admin.pages.faqs.edit', compact('faq'));
    }

    public function update_faq(Request $request, $id) {
        $validated = $request->validate([
            'question' => 'required',
            'answer' => 'required',
        ]);
        $faq = Faq::findOrFail($id);
        $faq->update([
            'question' => $request->question,
            'answer' => $request->answer,
            'status' => $request->status,
        ]);
        return redirect()->route('faqs.index')->with('success', 'Faq updated successfully.');
    }

    public function delete_faq($id) {
        $faq = Faq::findOrFail($id);
        $faq->delete();
        return redirect()->route('faqs.index')->with('success', 'Faq deleted successfully.');
    }


    //blog section

    public function create_blog() {
        return view('admin.pages.blogs.create');
    }


    // public function store_blog(Request $request) {
    //     $validated = $request->validate([
    //         'title' => 'required',
    //         'description' => 'required',
    //     ]);

    //     if ($request->hasFile('blog_image')) {
    //         $image = $request->file('blog_image');
    //         $blog_image = time() . '.' . $image->getClientOriginalExtension();
    //         $destinationPath = public_path('pages/blogs');
    //         File::makeDirectory($destinationPath, $mode = 0777, true, true);
    //         Image::make($image)->resize(1920, 512)->save($destinationPath . '/' . $blog_image);
            
    //     } else {
    //         $blog_image = null;
    //     }

    //     if ($request->hasFile('thumbnail')) {
    //         $thumbnail = $request->file('thumbnail');
    //         $thumbnailImage = time() . '_banner.' . $thumbnail->getClientOriginalExtension();
    //         $bannerDestinationPath = public_path('pages/blogs/thumbnails');
    //         File::makeDirectory($bannerDestinationPath, 0777, true, true);
    //         Image::make($thumbnail)->resize(400, 240)->save($bannerDestinationPath . '/' . $thumbnailImage);

    //     } else {
    //         $thumbnailImage = null;
    //     }
    //     $random_number = rand(1, 1000);
    //     try {
    //         $blog = Blog::create([
    //             'title' => $request->title,
    //             'description' => $request->description,
    //             'slug' => Str::slug($request->title) . '-' . $random_number,
    //             'image' => $blog_image,
    //             'thumbnail' => $thumbnailImage,
    //             'status' => $request->status,
    //         ]);
    //         return redirect()->route('blogs.index')->with('success', 'Blog created successfully.');
    //     }
    //     catch(\Exception $e) {
    //         Log::info('Exception: ' . $e->getMessage());
    //         return redirect()->route('blogs.index')->with('error', 'Something went wrong !');
    //     }
    // }


    public function store_blog(Request $request) {
        $validated = $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $blog_image = null;
        $thumbnail_image = null;

        if ($request->hasFile('blog_image')) {
            $image = $request->file('blog_image');
            $blog_image = time() . '_cover' . $image->getClientOriginalExtension();
            $destinationPath = public_path('pages/blogs/covers');
            File::makeDirectory($destinationPath, 0777, true, true);
            Image::make($image)->resize(1920, 517)->save($destinationPath . '/' . $blog_image);
        }
        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $thumbnail_image = time() . '_thumbnail.' . $thumbnail->getClientOriginalExtension();
            $thumbnailDestinationPath = public_path('pages/blogs/thumbnails');
            File::makeDirectory($thumbnailDestinationPath, 0777, true, true);
            Image::make($thumbnail)->resize(400, 240)->save($thumbnailDestinationPath . '/' . $thumbnail_image);

        }

        $random_number = rand(1, 1000);

        try {
            $blog = Blog::create([
                'title' => $request->title,
                'description' => $request->description,
                'slug' => Str::slug($request->title) . '-' . $random_number,
                'image' => $blog_image,
                'thumbnail' => $thumbnail_image,
                'status' => $request->status,
            ]);
            return redirect()->route('blogs.index')->with('success', 'Blog created successfully.');
        }
        catch(\Exception $e) {
            return redirect()->route('blogs.index')->with('error', 'Something went wrong !');
        }
    }

    public function blogs() {
        $blogs = Blog::paginate(10);
        return view('admin.pages.blogs.index', compact('blogs'));
    }

    public function edit_blog($id) {
        $blog = Blog::findOrFail($id);
        return view('admin.pages.blogs.edit', compact('blog'));
    }

    public function update_blog(Request $request, $id) {
        $validated = $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);
        $random_number = rand(1, 1000);
        // $blog = Blog::findOrFail($id);
        // $Image =  null;

        // if( !empty($request->blog_image)) {
        //     if ($request->hasFile('blog_image')) {
        //         $blog_image = $request->file('blog_image');
        //         $destinationPath = public_path('pages/blogs');
            
        //         // Generate a unique filename for the cover image
        //         $Image = time() . "." . $blog_image->getClientOriginalExtension();
            
        //         // Create the destination directory if it does not exist
        //         if (!File::isDirectory($destinationPath)) {
        //             File::makeDirectory($destinationPath, $mode = 0777, true, true);
        //         }
            
        //         // Resize and save the cover image with the specified dimensions (1920x517)
        //         $coverImageUrl = $destinationPath . '/' . $Image;
        //         $coverImage = Image::make($blog_image)->fit(900, 300)->save($coverImageUrl);
            
        //         // Generate a unique filename for the thumbnail
        //         $fileName_thumb = rand(11111111, 99999999) . "_" . time() . "_thumb_" . $Image;
        //         $thumbnailPath = public_path('pages/blogs/thumbnails');
            
        //         // Create the thumbnail directory if it does not exist
        //         if (!File::isDirectory($thumbnailPath)) {
        //             File::makeDirectory($thumbnailPath, 0777, true, true);
        //         }
            
        //         // Resize and save the thumbnail with the specified dimensions (290x200)
        //         $thumbnailUrl = $thumbnailPath . '/' . $fileName_thumb;
        //         $thumbnailImage = Image::make($blog_image)->fit(290, 200)->save($thumbnailUrl);
            
        //         // Move the original cover image to the destination directory
        //         $blog_image->move($destinationPath, $Image);
            
        //         // Store the filename in the request data
        //         $request['blog_image'] = "$Image";
        //     }

        // } else {
        //     $Image = $blog->image;
        // }
        // try{
        //     $blog->update([
        //         'title' => $request->title,
        //         'description' => $request->description,
        //         // 'slug' => Str::slug($request->title) . '-' . $random_number,
        //         'image' => $Image,
        //         'thumbnail' => !empty($blog_image2) ? $fileName_thumb : $blog->thumbnail,
        //         'status' => $request->status,
        //     ]);
        //     return redirect()->route('blogs.index')->with('success', 'Blog updated successfully.');
        // }
        // catch(\Exception $e) {
        //     return redirect()->route('blogs.index')->with('error', 'Something went wrong !');
        // }


        $blog = Blog::findOrFail($id);
        $blog_image = $blog->image;
        $thumbnail_image = $blog->thumbnail;
        
        if ($request->hasFile('blog_image')) {
            $image = $request->file('blog_image');
            $blog_image = time() . '_cover' . $image->getClientOriginalExtension();
            $destinationPath = public_path('pages/blogs/covers');
            File::makeDirectory($destinationPath, 0777, true, true);
            Image::make($image)->resize(1920, 517)->save($destinationPath . '/' . $blog_image);
        }
    
        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $thumbnail_image = time() . '_thumbnail.' . $thumbnail->getClientOriginalExtension();
            $thumbnailDestinationPath = public_path('pages/blogs/thumbnails');
            File::makeDirectory($thumbnailDestinationPath, 0777, true, true);
            Image::make($thumbnail)->resize(400, 240)->save($thumbnailDestinationPath . '/' . $thumbnail_image);
        }
    
        try {
            $blog->update([
                'title' => $request->title,
                'description' => $request->description,
                'image' => $blog_image,
                'thumbnail' => $thumbnail_image,
                'status' => $request->status,
            ]);
    
            return redirect()->route('blogs.index')->with('success', 'Blog updated successfully.');
        } catch (\Exception $e) {
            Log::info('Exception: ' . $e->getMessage());
            return redirect()->route('blogs.index')->with('error', 'Something went wrong !');
        }
    }

    public function delete_blog($id) {
        $blog = Blog::findOrFail($id);
        $blog->delete();
        return redirect()->route('blogs.index')->with('success', 'Blog deleted successfully.');
    }


    public function blog_detail($slug) {
        $blog_detail = Blog::where('slug', $slug)->first();
        return view('partials.blog_detail', compact('blog_detail')); 
    }
    

    public function blog_search (Request $request) {
        $search_value = $request->search_blog;
        $page_slug = $request->page_slug;
        $blogs = Blog::where('title', 'LIKE', '%' . $search_value . '%')->paginate(10);
        $page = Page::where('slug', $page_slug)->first();
        return view('partials.search_blogs', compact('blogs' , 'page' , 'search_value'));
    }
}
