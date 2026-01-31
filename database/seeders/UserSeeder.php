<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $admin_role = Role::where('name', 'admin')->first();
        $customer_role = Role::where('name', 'customer')->first();

        User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role_id' => $admin_role->id,
        ]);

        User::create([
            'name' => 'Customer User 1',
            'email' => 'customer1@test.com',
            'password' => Hash::make('password'),
            'role_id' => $customer_role->id,
        ]);

        User::create([
            'name' => 'Customer User 2',
            'email' => 'customer2@test.com',
            'password' => Hash::make('password'),
            'role_id' => $customer_role->id,
        ]);
    }
}
