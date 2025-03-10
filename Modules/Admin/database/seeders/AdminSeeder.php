<?php

namespace Modules\Admin\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Admin\Models\Admin;
use Modules\Booking\Models\Marketer;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Create Super Admin
         Admin::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password'),
            'user_type' => 'super_admin',
            'phone' => '1234567890',
        ]);

        // Create multiple admin users
        $admins = [
            [
                'name' => 'Admin One',
                'email' => 'admin1@example.com',
                'password' => Hash::make('password'),
                'user_type' => 'admin',
                'phone' => '1111111111',
            ],
            [
                'name' => 'Admin Two',
                'email' => 'admin2@example.com',
                'password' => Hash::make('password'),
                'user_type' => 'admin',
                'phone' => '2222222222',
            ],
            [
                'name' => 'Admin Three',
                'email' => 'admin3@example.com',
                'password' => Hash::make('password'),
                'user_type' => 'admin',
                'phone' => '3333333333',
            ],
        ];

        foreach ($admins as $admin) {
            Admin::create($admin);
        }

        // Create hotel managers
        $hotelManagers = [
            [
                'name' => 'Hotel Manager One',
                'email' => 'hotelmanager1@example.com',
                'password' => Hash::make('password'),
                'user_type' => 'hotel_manager',
                'phone' => '4444444444',
                'created_by' => 2
            ],
            [
                'name' => 'Hotel Manager Two',
                'email' => 'hotelmanager2@example.com',
                'password' => Hash::make('password'),
                'user_type' => 'hotel_manager',
                'phone' => '5555555555',
                'created_by' => 3
            ],
        ];

        foreach ($hotelManagers as $manager) {
            Admin::create($manager);
        }

        // Create marketers
        $marketers = [
            [
                'name' => 'Marketer One',
                'email' => 'marketer1@example.com',
                'password' => Hash::make('password'),
                'user_type' => 'marketer',
                'phone' => '6666666666',
                'created_by' => 2
            ],
            [
                'name' => 'Marketer Two',
                'email' => 'marketer2@example.com',
                'password' => Hash::make('password'),
                'user_type' => 'marketer',
                'phone' => '7777777777',
                'created_by' => 3
            ],
        ];

        foreach ($marketers as $marketer) {
            $admin = Admin::create($marketer);
            Marketer::create([
                'admin_id' => $admin->id,
                'active' => true
            ]);
        }
    }
}
