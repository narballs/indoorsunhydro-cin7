<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class SpecificAdminNotificationsSeeder extends Seeder
{
    public function run()
    {
        // Step 1: Get model_ids of admins with role 1 or 6
        $admin_users = DB::table('model_has_roles')
            ->whereIn('role_id', [1, 6,7,8,9])
            ->pluck('model_id')
            ->toArray();

        // Step 2: Get user emails
        $admins = User::select('id', 'email')
            ->whereIn('id', $admin_users)
            ->get();

        // Step 3: Insert into specific_admin_notifications
        foreach ($admins as $admin) {
            DB::table('specific_admin_notifications')->updateOrInsert(
                ['user_id' => $admin->id], // Assumes a user_id column exists
                [
                    'email' => $admin->email,
                    'receive_order_notifications' => false,
                    'receive_label_notifications' => false,
                    'receive_accounting_reports' => false,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }
    }
}
