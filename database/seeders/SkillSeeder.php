<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('skills')->insert([
            [
                'name' => 'Laravel',
                'slug' => 'laravel',
                'level' => 'intermediate',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'React.js',
                'slug' => 'react-js',
                'level' => 'intermediate',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Next.js',
                'slug' => 'next-js',
                'level' => 'intermediate',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
