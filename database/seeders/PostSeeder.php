<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Support\Str;

class PostSeeder extends CustomSeeder
{


    public function run(): void
    {
        $faker = Factory::create();
        $users = User::query()
            ->get();
        $userCount = $users->count();
        $categories = Category::query()
            ->get();
        $categoryCount = $categories->count();
        $posts = [];
        for ($i = 0; $i < 1000; $i++) {

            $posts[] = [
                'title' => $faker->sentence(7),
                'content' => $faker->sentence(20),
                'category_id' => $categories->get(rand(0, $categoryCount - 1))->id,
                'user_id' => $users->get(rand(0, $userCount - 1))->id,
                'status' => rand(1, 3),
                'created_at' => $this->randomDateTime()
            ];
            Post::query()
                ->insert($posts);
        }
    }

}
