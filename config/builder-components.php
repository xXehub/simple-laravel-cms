<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Page Builder Components Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration defines all available components for the page builder.
    | Components are organized by category and can be enabled/disabled individually.
    |
    */

    'components' => [
        
        // Layout Components
        'layout.container' => [
            'class' => \App\View\Components\Builder\Layout\Container::class,
            'enabled' => true,
        ],
        
        'layout.row' => [
            'class' => \App\View\Components\Builder\Layout\Row::class,
            'enabled' => true,
        ],
        
        'layout.column' => [
            'class' => \App\View\Components\Builder\Layout\Column::class,
            'enabled' => true,
        ],
        
        'layout.section' => [
            'class' => \App\View\Components\Builder\Layout\Section::class,
            'enabled' => true,
        ],

        // Content Components
        'content.heading' => [
            'class' => \App\View\Components\Builder\Content\Heading::class,
            'enabled' => true,
        ],
        
        'content.text' => [
            'class' => \App\View\Components\Builder\Content\Text::class,
            'enabled' => true,
        ],
        
        'content.button' => [
            'class' => \App\View\Components\Builder\Content\Button::class,
            'enabled' => true,
        ],
        
        'content.image' => [
            'class' => \App\View\Components\Builder\Content\Image::class,
            'enabled' => true,
        ],
        
        // Card Components
        'cards.basic-card' => [
            'class' => \App\View\Components\Builder\Cards\BasicCard::class,
            'enabled' => true,
        ],
        
        'cards.feature-card' => [
            'class' => \App\View\Components\Builder\Cards\FeatureCard::class,
            'enabled' => true,
        ],
        
        'cards.pricing-card' => [
            'class' => \App\View\Components\Builder\Cards\PricingCard::class,
            'enabled' => true,
        ],

        // Custom Components
        'custom.vanilla-component' => [
            'class' => \App\View\Components\Builder\Custom\VanillaComponent::class,
            'enabled' => true,
        ],
        
        'custom.example-component' => [
            'class' => \App\View\Components\Builder\Custom\ExampleComponent::class,
            'enabled' => true,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Builder Settings
    |--------------------------------------------------------------------------
    */
    
    'settings' => [
        
        // Enable/disable custom components
        'allow_custom_components' => true,
        
        // Custom components directory
        'custom_components_path' => 'View/Components/Builder/Custom',
        
        // Enable component validation
        'validate_components' => true,
        
        // Cache component registry
        'cache_registry' => true,
        'cache_duration' => 3600, // 1 hour
        
        // Builder UI settings
        'sidebar_width' => 300,
        'property_panel_width' => 350,
        'canvas_padding' => 20,
        
        // Auto-save settings
        'auto_save' => true,
        'auto_save_interval' => 30000, // 30 seconds
        
        // Preview settings
        'live_preview' => true,
        'preview_breakpoints' => ['xs', 'sm', 'md', 'lg', 'xl'],
        
    ],

    /*
    |--------------------------------------------------------------------------
    | Component Defaults
    |--------------------------------------------------------------------------
    */
    
    'defaults' => [
        
        // Default container settings
        'container' => [
            'fluid' => false,
            'css_classes' => ['container']
        ],
        
        // Default spacing
        'spacing' => [
            'margin' => 'mb-3',
            'padding' => 'p-3'
        ],
        
        // Default colors (Tabler.io theme colors)
        'colors' => [
            'primary' => '#206bc4',
            'secondary' => '#626976',
            'success' => '#2fb344',
            'warning' => '#f59f00',
            'danger' => '#d63384',
            'info' => '#4299e1',
            'light' => '#f8f9fa',
            'dark' => '#354052'
        ],
        
    ],

    /*
    |--------------------------------------------------------------------------
    | Validation Rules
    |--------------------------------------------------------------------------
    */
    
    'validation' => [
        
        // Required component methods
        'required_methods' => [
            'getDefaultProperties',
            'getViewName',
            'render'
        ],
        
        // Required metadata fields
        'required_meta' => [
            'name',
            'category',
            'icon',
            'description'
        ],
        
        // Allowed HTML tags in content
        'allowed_html_tags' => [
            'p', 'div', 'span', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
            'a', 'img', 'button', 'strong', 'em', 'br', 'hr',
            'ul', 'ol', 'li', 'table', 'thead', 'tbody', 'tr', 'td', 'th'
        ],
        
        // Max nesting depth for components
        'max_nesting_depth' => 10,
        
    ]

];
