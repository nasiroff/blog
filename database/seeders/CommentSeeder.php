<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Faker\Factory;
use Illuminate\Support\Str;

class CommentSeeder extends CustomSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        $users = User::query()
            ->get();
        $userCount = $users->count();
        $posts = Post::query()
            ->get();
        $postCount = $posts->count();
        $comments = [];

        for ($i = 0; $i < 10000; $i++) {
            $comments[] = [
                'content' => $faker->sentence(20),
                'user_id' => $users->get(rand(0, $userCount - 1))->id,
                'post_id' => $posts->get(rand(0, $postCount - 1))->id,
                'created_at' => $this->randomDateTime()
            ];
        }

        Comment::query()->insert($comments);
    }
}
