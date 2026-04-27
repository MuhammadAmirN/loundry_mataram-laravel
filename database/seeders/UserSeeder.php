<?php
namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Admin Laundry',
            'email'    => 'admin@laundrymataram.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        User::create([
            'name'     => 'User Demo',
            'email'    => 'user@demo.com',
            'password' => Hash::make('password'),
            'role'     => 'user',
        ]);
    }
}