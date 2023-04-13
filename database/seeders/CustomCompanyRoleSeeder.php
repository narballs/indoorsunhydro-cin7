<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\CustomCompanyRole;
use App\Models\Role;
use DB;

class CustomCompanyRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $custom_company_roles = [
            'Order Approver',
        ];

        CustomCompanyRole::truncate();

        foreach ($custom_company_roles as $custom_company_role) {
            $role = DB::table('roles')->where('name', $custom_company_role)->first();

            if (!empty($role)) {
                $custom_company_role = CustomCompanyRole::create([
                    'role_id' => $role->id
                ]);
            }
        }
    }
}
