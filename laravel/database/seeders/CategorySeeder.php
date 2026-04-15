<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Trừ bệnh', 'slug' => Str::slug('Trừ bệnh'), 'status' => true],
            ['name' => 'Trừ sâu', 'slug' => Str::slug('Trừ sâu'), 'status' => true],
            ['name' => 'Trừ cỏ', 'slug' => Str::slug('Trừ cỏ'), 'status' => true],
            ['name' => 'Trừ ốc và diệt chuột', 'slug' => Str::slug('Trừ ốc và diệt chuột'), 'status' => true],
            ['name' => 'Phân bón và Kích thích sinh trưởng', 'slug' => Str::slug('Phân bón và Kích thích sinh trưởng'), 'status' => true],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
