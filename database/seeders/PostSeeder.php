<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
{
        Post::create([
            'user_id' => 1,
            'content' => 'First post by Admin',
            'image' => null,
        ]);

        Post::create([
            'user_id' => 2,
            'content' => 'First post by User One',
            'image' => null,
        ]);

        Post::create([
            'user_id' => 3,
            'content' => 'First post by User Two',
            'image' => null,
        ]);
    }
    }
    }
