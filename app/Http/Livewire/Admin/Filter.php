<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;

class Filter extends Component
{
    use WithPagination;
    public $searchTerm;


    public function render()
    {
        return view(
            'livewire.admin.filter', [
            'products' =>  Product::where(function($sub_query){
                        $sub_query->where('name', 'like', '%'.$this->searchTerm.'%')
                        })->paginate(5)
        ]);
    }
}
