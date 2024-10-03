<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection, WithHeadings
{
    protected $products;

    public function __construct($products)
    {
        $this->products = $products;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Collect product data and format it for Excel
        return $this->products->map(function ($product) {
            return [
                'product_id' => $product->product_id,
                'name' => $product->name,
                'description' => $product->description,
                'slug' => $product->slug,
                'category' => $product->categories->pluck('name'),
                'brand' => $product->product_brand->name ?? '', // Check if brand exists
                'weight' => $product->weight,
            ];
        });
    }

    /**
     * Add headings to the Excel file
     */
    public function headings(): array
    {
        return [
            'Name',
            'Description',
            'Slug',
            'Category',
            'Brand',
            'Weight',
        ];
    }
}