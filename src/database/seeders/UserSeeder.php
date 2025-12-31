<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 管理者
        User::create([
            'name' => '管理者',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => Carbon::now(),
            'role' => 'admin',
        ]);

        // 店舗
        User::create([
            'name' => '仙人',
            'email' => 'sennin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => Carbon::now(),
            'role' => 'shop',
        ]);
        User::create([
            'name' => '牛助',
            'email' => 'gyusuke@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => Carbon::now(),
            'role' => 'shop',
        ]);
    }

}
