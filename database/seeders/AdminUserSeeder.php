<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        AdminUser::create([
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'nickname' => '超级管理员',
            'role' => 'super_admin',
            'status' => 'active',
        ]);
    }
}