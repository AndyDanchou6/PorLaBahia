<?php

namespace Database\Seeders;

use App\Models\ContentManagementSystem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CmsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ContentManagementSystem::create([
            'page' => 'home',
            'section' => 1,
            'title' => 'meow',
            'subtitle' => 'meow'
        ]);

        ContentManagementSystem::create([
            'page' => 'home',
            'section' => 2,
            'title' => 'meow',
            'subtitle' => 'meow'
        ]);
    }
}
