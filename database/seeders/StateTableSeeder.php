<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\State;

class StateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        State::truncate();
        $states = [
                    'Alabama',
                    'Alaska',
                    'Arkansas', 
                    'California', 
                    'Connecticut', 
                    'Florida', 
                    'Hawaii', 
                    'Indiana', 
                    'Kansas', 
                    'Maryland'
            ];
        foreach($states as $state) {
            State::create([
                'name' => $state
            ]);
        }
    }
}
