<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Page;

class TestPageSeederFixed extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create or update test pages for page builder
        $testPages = [
            [
                'title' => 'Home Page Builder Test',
                'slug' => 'home-test',
                'content' => '',
                'template' => 'builder',
                'is_published' => true,
                'meta_title' => 'Home - Page Builder Test',
                'meta_description' => 'Test home page for page builder functionality',
                'sort_order' => 1
            ],
            [
                'title' => 'About Us Builder Test',
                'slug' => 'about-test',
                'content' => '',
                'template' => 'builder',
                'is_published' => true,
                'meta_title' => 'About Us - Page Builder Test',
                'meta_description' => 'Test about page for page builder functionality',
                'sort_order' => 2
            ],
            [
                'title' => 'Services Builder Test',
                'slug' => 'services-test',
                'content' => '',
                'template' => 'builder',
                'is_published' => true,
                'meta_title' => 'Services - Page Builder Test',
                'meta_description' => 'Test services page for page builder functionality',
                'sort_order' => 3
            ]
        ];

        foreach ($testPages as $pageData) {
            $page = Page::updateOrCreate(
                ['slug' => $pageData['slug']],
                $pageData
            );
            
            $this->command->info("Created/Updated page: {$page->title} (ID: {$page->id})");
        }

        $this->command->info('✅ Test pages created successfully!');
    }
}
