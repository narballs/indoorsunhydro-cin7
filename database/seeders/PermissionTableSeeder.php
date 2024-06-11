<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'user-create',
            'user-delete',
            'user-edit',
            'user-list',
            'order-list',
            'contact-list',
            'user-logout',
            'edit-profile', 
            'order-approve',
            'newsletter-subscription-list',
            'newsletter-subscription-create',
            'newsletter-subscription-edit',
            'newsletter-subscription-delete',
            'newsletter-subscription-save',
            'newsletter-subscription-update',
            'newsletter-subscription-send',
        ];

        
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
