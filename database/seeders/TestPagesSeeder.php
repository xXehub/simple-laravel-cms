<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class TestPagesSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run()
    {
        // Create test pages for page builder
        $testPages = [
            [
                'title' => 'Home Page',
                'slug' => 'home',
                'content' => null,
                'template' => 'builder',
                'is_published' => true,
                'meta_title' => 'Home - Test Page Builder',
                'meta_description' => 'Test home page for page builder functionality',
                'sort_order' => 1
            ],
            [
                'title' => 'About Us',
                'slug' => 'about',
                'content' => null,
                'template' => 'builder',
                'is_published' => true,
                'meta_title' => 'About Us - Test Page Builder',
                'meta_description' => 'Test about page for page builder functionality',
                'sort_order' => 2
            ],
            [
                'title' => 'Services',
                'slug' => 'services',
                'content' => null,
                'template' => 'builder',
                'is_published' => true,
                'meta_title' => 'Services - Test Page Builder',
                'meta_description' => 'Test services page for page builder functionality',
                'sort_order' => 3
            ]
        ];

        foreach ($testPages as $pageData) {
            Page::updateOrCreate(
                ['slug' => $pageData['slug']],
                $pageData
            );
        }

        $this->command->info('Test pages created successfully!');
    }
}
