<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InventoryLocation;

class InventoryLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $inventory_locations = [
            [
                'cin7_branch_id' => 9899,
                'branch_name' => 'Sac Store',
                'status' => 1
            ],
            [
                'cin7_branch_id' => 174,
                'branch_name' => 'Indoor Sun Hydro 6060',
                'status' => 0
            ],
            [
                'cin7_branch_id' => 173,
                'branch_name' => 'Calaveras Garden Supplies',
                'status' => 0
            ],
            [
                'cin7_branch_id' => 172,
                'branch_name' => 'Indoor Sun OKC',
                'status' => 0
            ],
            [
                'cin7_branch_id' => 3,
                'branch_name' => 'Indoor Sun Hydro 5671',
                'status' => 1
            ]
        ];


        foreach($inventory_locations as $inventory_location) {
            $inventory_location_db = InventoryLocation::firstOrCreate(
                [
                    'cin7_branch_id' => $inventory_location['cin7_branch_id'],
                    'branch_name' => $inventory_location['branch_name'],
                    'status' => $inventory_location['status']
                ],
                [
                    'cin7_branch_id' => $inventory_location['cin7_branch_id'],
                    'branch_name' => $inventory_location['branch_name'],
                    'status' => $inventory_location['status']
                ],
            );
        }
    }
}
