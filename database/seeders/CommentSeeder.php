<?php

namespace Database\Seeders;

use App\Models\Comment ;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        Comment::create([
            'user_id' => 2,
            'post_id' => 1,
            'content' => 'Nice post Admin!',
        ]);

        Comment::create([
            'user_id' => 3,
            'post_id' => 1,
            'content' => 'Great content!',
        ]);

        Comment::create([
            'user_id' => 1,
            'post_id' => 2,
            'content' => 'Thanks for sharing!',
        ]);
    }
}
