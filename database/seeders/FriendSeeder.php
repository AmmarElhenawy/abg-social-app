<?php

namespace Database\Seeders;

use App\Models\Friend;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FriendSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Friend::create([
            'sender_id' => 1,
            'receiver_id' => 2,
            'status' => 'accepted',
        ]);

        Friend::create([
            'sender_id' => 1,
            'receiver_id' => 3,
            'status' => 'pending',
        ]);

        Friend::create([
            'sender_id' => 2,
            'receiver_id' => 3,
            'status' => 'rejected',
        ]);
    }
}
