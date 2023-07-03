<?php

namespace App\Helpers;
use App\Models\Category;
use App\Models\Product;
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
            ->where('name', '!=', 'Not visable')
            ->get();
        return $categories;
    }
}


