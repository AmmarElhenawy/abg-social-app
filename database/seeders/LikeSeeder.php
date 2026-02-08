<?php

namespace Database\Seeders;

use App\Models\Like;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        Like::create([
            'user_id' => 2,
            'post_id' => 1,
        ]);

        Like::create([
            'user_id' => 3,
            'post_id' => 1,
        ]);

        Like::create([
            'user_id' => 1,
            'post_id' => 2,
        ]);
    }
}
