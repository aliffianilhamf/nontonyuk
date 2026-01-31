<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Video;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $customer_1 = User::where('email', 'customer1@test.com')->first();
        $customer_2 = User::where('email', 'customer2@test.com')->first();

        $video_1 = Video::find(1);
        $video_2 = Video::find(2);
        $video_3 = Video::find(3);

        Permission::create([
            'user_id' => $customer_1->id,
            'video_id' => $video_1->id,
            'status' => 'approved',
            'expires_at' => now()->addMinutes(5),
        ]);

        Permission::create([
            'user_id' => $customer_1->id,
            'video_id' => $video_2->id,
            'status' => 'approved',
            'expires_at' => now()->subMinutes(5),
        ]);

        Permission::create([
            'user_id' => $customer_2->id,
            'video_id' => $video_3->id,
            'status' => 'pending',
            'expires_at' => null,
        ]);
    }
}
