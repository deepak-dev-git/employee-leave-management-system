<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Enums\UserType;
use App\Models\LeaveSetting;
use App\Models\User;
use Illuminate\Support\Str;

class AddUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'type' => UserType::ADMIN,
                'email' => 'admin@gmail.com',
                'status' => true,
                'image' => 'avatar.jpg',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'pass' => 'password',
                'remember_token' => Str::random(10),
            ]
        );
        $admin->assignRole(UserType::ADMIN);

        //employee create seeder (only 1 for now.(testing puprose)
        // $employee = User::updateOrCreate(
        //     ['email' => 'deepak@gmail.com'],
        //     [
        //         'name' => 'deepak',
        //         'type' => UserType::EMPLOYEE,
        //         'email' => 'deepak@gmail.com',
        //         'status' => true,
        //         'image' => 'avatar.jpg',
        //         'email_verified_at' => now(),
        //         'password' => bcrypt('password'),
        //         'pass' => 'password',
        //         'remember_token' => Str::random(10),
        //     ]
        // );
        // $employee->assignRole(UserType::EMPLOYEE);

        LeaveSetting::updateOrCreate(
            ['year' => date('Y')],
            ['allocated_days' => 30]
        );
    }
}
