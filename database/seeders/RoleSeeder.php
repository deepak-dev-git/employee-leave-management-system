<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Enums\UserType;

class RoleSeeder extends Seeder
{
    public function run()
    {
        Role::updateOrCreate(['name' => UserType::ADMIN]);
        Role::updateOrCreate(['name' => UserType::EMPLOYEE]);
    }
}
