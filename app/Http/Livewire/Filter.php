<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;


class Filter extends Component
{
    public $searchTerm;
    use WithPagination;
    public function render()
    {
        return view(
            'livewire.filter', [
            'products' =>  Product::with('options')->where(function($sub_query){
                        $sub_query->where('name', 'like', '%'.$this->searchTerm.'%');
                        })->paginate(5)
        ]);
    }
}
