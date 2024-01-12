<?php

namespace Database\Seeders;

use App\Models\Bussiness;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'phone' => "123456",
            'photo' => "lskfalskflas",
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
        ])->assignRole('admin');
        // User::create([
        //     'name' => 'user',
        //     'email' => 'user@gmail.com',
        //     'email_verified_at' => now(),
        //     'phone' => "123456",
        //     'photo' => "lskfalskflas",
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
        // ])->assignRole('user');
        Bussiness::create([
            'name' => 'bussiness',
            'email' => 'b@gmail.com',
            'phone' => '1234523525',
            'photo' => 'slkfasa',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
        ])->assignRole('bussiness');
    }
}
