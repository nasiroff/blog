<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $categories = [
            [
                'name' => 'Siyasət',
                'order' => 100
            ],
            [
                'name' => 'İqtisadiyyat',
                'order' => 200
            ],
            [
                'name' => 'Cəmiyyət',
                'order' => 300
            ],
            [
                'name' => 'Şou-biznes',
                'order' => 400
            ],
            [
                'name' => 'Müharibə',
                'order' => 500
            ],
            [
                'name' => 'İdman',
                'order' => 600
            ],
            [
                'name' => 'Kriminal',
                'order' => 700
            ],
            [
                'name' => 'Mədəniyyət',
                'order' => 800
            ],
        ];

        Category::query()->insert($categories);
    }
}
