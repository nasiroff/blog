<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CustomSeeder extends Seeder
{

    protected function randomDateTime()
    {
        return Carbon::create(2024, 1, rand(1, 29), rand(1,23), rand(0, 59));
    }
}
