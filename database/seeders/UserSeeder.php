<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@social.com',
            'password' => Hash::make('password'),
            'profile_picture' => 'avatars/admin.jpg',
            'bio' => 'Platform Administrator',
            'isAcive' => true,
        ]);

        // Test Users
        $john = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'profile_picture' => 'avatars/john.jpg',
            'bio' => 'Software Developer | Tech Enthusiast',
            'isAcive' => true,
        ]);

        $jane = User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
            'profile_picture' => 'avatars/jane.jpg',
            'bio' => 'Designer | Artist | Traveler',
            'isAcive' => true,
        ]);

        $this->command->info('Users created successfully!');
    }
}
