<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo 1 admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'phone' => '0123456789',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'admin',
            'last_login_at' => now(),
            'last_login_ip' => '127.0.0.1',
            'remember_token' => Str::random(10),
        ]);

        // Tạo 9 user thường
        for ($i = 1; $i <= 9; $i++) {
            User::create([
                'name' => "User $i",
                'email' => "user$i@example.com",
                'phone' => "09876543$i",
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'user',
                'last_login_at' => now(),
                'last_login_ip' => '127.0.0.1',
                'remember_token' => Str::random(10),
            ]);
        }
    }
}
