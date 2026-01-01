<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 
        $courses = [
            [
                'shop_id' => 1,
                'name' => '1000円先払い',
                'description' => '1000円先払いするコースです',
                'price' => 1000,
                'is_active' => true,
            ],
            [
                'shop_id' => 1,
                'name' => '2000円先払い',
                'description' => '2000円先払いするコースです',
                'price' => 2000,
                'is_active' => true,
            ],
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}
