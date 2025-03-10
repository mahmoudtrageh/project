<?php

namespace Modules\Booking\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Booking\Models\RoomType;

class RoomTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create multiple admin users
        $room_types = [
            [
                'name' => 'root-type1',
                'description' => 'admin1@example.com',
            ],

            [
                'name' => 'root-type2',
                'description' => 'admin1@example.com',
            ],

            [
                'name' => 'root-type3',
                'description' => 'admin1@example.com',
            ],
        ];

        foreach ($room_types as $room_type) {
            RoomType::create($room_type);
        }
    }
}
