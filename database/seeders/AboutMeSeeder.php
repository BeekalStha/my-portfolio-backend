<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AboutMeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('about_mes')->insert([
            'name' => 'Bikal Shrestha',
            'slug' => 'bikal-shrestha',
            'email' =>'shresthabikal92@gmail.com',
            'phone' => '+977 9860463227',
            'location' => 'Panauti-8,Kavrepalanchok Nepal',
            'title' => 'Full Stack Developer on Laravel and Next.js or React.js',
            'description' => 'A passionate software engineer with expertise in web development and a love for coding.',
            'profile_picture' => '/profile_pictures/BikalStha.jpg',
            'bio' => 'A dedicated developer with a strong background in building dynamic web applications.',
            'age' => '24',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
