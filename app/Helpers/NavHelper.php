<?php

namespace App\Helpers;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Faq;
use App\Models\Product;
use App\Models\Page;
class NavHelper
{
    /**
     * get user option_name value.
     *
     * @param  user_id
     * @param  option_name
     * @param  default_value
     * @return option_value
     */
    public static function getCategories() {
         $categories = Category::orderBy('name', 'ASC')
            ->with('products')->where('is_active', 1)
            ->get();
        return $categories;
    }

    public static function getPages () {
        $pages = Page::where('status', 1)->get();
        return $pages;
    }


    public static function getFaqs () {
        $faqs = Faq::where('status', 1)->get();
        return $faqs;
    }


    public static function getBlogs () {
        $blogs = Blog::where('status', 1)->get();
        return $blogs;
    }
}


