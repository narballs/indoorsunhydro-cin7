<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\User;
use Auth;


class Filter2 extends Component
{
    public $searchTerm;
    use WithPagination;
    public function render()
    {
        $role = Auth::user()->hasRole('Admin');

        return view(
            'livewire.front_end_filter', [
            'products' =>  Product::with('options')->where(function($sub_query){
                $sub_query->where('name', 'like', '%' . $this->searchTerm . '%');
            })->paginate(5),
            'role' => $role
        ]);
    }
}