<?php

namespace App\View\Components\Builder\Cards;

use App\View\Components\Builder\BaseComponent;

/**
 * Pricing Card Component
 * 
 * A specialized card component for displaying pricing plans with features,
 * pricing, and call-to-action buttons.
 */
class PricingCard extends BaseComponent
{
    /**
     * Component metadata
     */
    protected array $meta = [
        'name' => 'Pricing Card',
        'category' => 'cards', 
        'icon' => 'ti ti-currency-dollar',
        'description' => 'A card for displaying pricing plans with features and call-to-action',
        'version' => '1.0.0'
    ];

    /**
     * Default properties
     */
    public function getDefaultProperties(): array
    {
        return [
            // Plan Info
            'plan_name' => 'Basic Plan',
            'plan_description' => 'Perfect for getting started',
            'price' => '29',
            'currency' => '$',
            'price_period' => 'month',
            'original_price' => '',
            
            // Badge
            'badge_text' => '',
            'badge_variant' => 'bg-success',
            'is_popular' => false,
            
            // Features
            'features' => json_encode([
                'Up to 10 projects',
                '5GB storage',
                'Email support',
                'Basic analytics'
            ]),
            'features_icon' => 'ti ti-check',
            
            // Button
            'button_text' => 'Get Started',
            'button_url' => '#',
            'button_variant' => 'btn-primary',
            'button_size' => '',
            'button_target' => '_self',
            'button_block' => true,
            
            // Layout
            'featured' => false,
            
            // Styling (Tabler.io classes only)
            'shadow' => 'shadow-sm',
            'border_radius' => 'rounded',
            'margin' => 'mb-4',
        ];
    }

    /**
     * Get property definitions for the builder UI
     */
    public function getBuilderProperties(): array
    {
        return array_merge(parent::getBuilderProperties(), [
            'plan_name' => [
                'type' => 'text',
                'label' => 'Plan Name',
                'placeholder' => 'Basic Plan',
                'required' => true,
            ],
            'plan_description' => [
                'type' => 'text',
                'label' => 'Plan Description',
                'placeholder' => 'Perfect for getting started',
            ],
            'price' => [
                'type' => 'text',
                'label' => 'Price',
                'placeholder' => '29',
                'required' => true,
            ],
            'currency' => [
                'type' => 'text',
                'label' => 'Currency Symbol',
                'placeholder' => '$',
            ],
            'price_period' => [
                'type' => 'select',
                'label' => 'Price Period',
                'options' => [
                    'month' => 'per month',
                    'year' => 'per year',
                    'week' => 'per week',
                    'day' => 'per day',
                    'one-time' => 'one-time',
                ],
            ],
            'original_price' => [
                'type' => 'text',
                'label' => 'Original Price (crossed out)',
                'placeholder' => '49',
            ],
            'badge_text' => [
                'type' => 'text',
                'label' => 'Badge Text',
                'placeholder' => 'Most Popular',
            ],
            'badge_variant' => [
                'type' => 'select',
                'label' => 'Badge Color',
                'options' => [
                    'bg-primary' => 'Primary',
                    'bg-secondary' => 'Secondary',
                    'bg-success' => 'Success',
                    'bg-info' => 'Info',
                    'bg-warning' => 'Warning',
                    'bg-danger' => 'Danger',
                ],
            ],
            'is_popular' => [
                'type' => 'boolean',
                'label' => 'Mark as Popular',
            ],
            'features' => [
                'type' => 'textarea',
                'label' => 'Features (one per line)',
                'placeholder' => "Up to 10 projects\n5GB storage\nEmail support",
                'rows' => 5,
                'help' => 'Enter each feature on a new line',
            ],
            'features_icon' => [
                'type' => 'icon',
                'label' => 'Features Icon',
                'placeholder' => 'ti ti-check',
            ],
            'button_text' => [
                'type' => 'text',
                'label' => 'Button Text',
                'placeholder' => 'Get Started',
                'required' => true,
            ],
            'button_url' => [
                'type' => 'url',
                'label' => 'Button URL',
                'placeholder' => 'https://example.com/signup',
                'required' => true,
            ],
            'button_variant' => [
                'type' => 'select',
                'label' => 'Button Style',
                'options' => [
                    'btn-primary' => 'Primary',
                    'btn-secondary' => 'Secondary',
                    'btn-success' => 'Success',
                    'btn-info' => 'Info',
                    'btn-warning' => 'Warning',
                    'btn-danger' => 'Danger',
                    'btn-outline-primary' => 'Outline Primary',
                    'btn-outline-secondary' => 'Outline Secondary',
                ],
            ],
            'button_size' => [
                'type' => 'select',
                'label' => 'Button Size',
                'options' => [
                    '' => 'Default',
                    'btn-sm' => 'Small',
                    'btn-lg' => 'Large',
                ],
            ],
            'button_target' => [
                'type' => 'select',
                'label' => 'Button Target',
                'options' => [
                    '_self' => 'Same Window',
                    '_blank' => 'New Window',
                ],
            ],
            'button_block' => [
                'type' => 'boolean',
                'label' => 'Full Width Button',
            ],
            'featured' => [
                'type' => 'boolean',
                'label' => 'Featured Plan',
                'help' => 'Highlight this plan with special styling',
            ],
        ]);
    }

    /**
     * Generate CSS classes
     */
    public function getCssClasses(): string
    {
        $classes = ['card'];
        
        // Add base classes
        $classes[] = $this->getProperty('shadow', 'shadow-sm');
        $classes[] = $this->getProperty('border_radius', 'rounded');
        $classes[] = $this->getProperty('margin', 'mb-4');
        
        // Add height
        if ($this->getProperty('card_height')) {
            $classes[] = $this->getProperty('card_height');
        }
        
        // Featured styling - only use Tabler.io classes
        if ($this->getProperty('featured', false)) {
            $classes[] = 'border-primary';
            $classes[] = 'border-2'; // Tabler.io class for thicker border
        }
        
        // Popular styling - no custom classes, just Tabler.io
        if ($this->getProperty('is_popular', false)) {
            $classes[] = 'border-success'; // Use Tabler.io border color
        }
        
        return implode(' ', array_filter($classes));
    }

    /**
     * Get inline styles
     */
    public function getInlineStyles(): string
    {
        // Only return empty string - no custom colors, use Tabler.io classes only
        return '';
    }

    /**
     * Get features as array
     */
    public function getFeaturesArray(): array
    {
        $features = $this->getProperty('features', '');
        
        if (is_string($features)) {
            // If it's JSON, decode it
            $decoded = json_decode($features, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
            
            // If it's plain text with line breaks, split by lines
            return array_filter(explode("\n", $features));
        }
        
        return is_array($features) ? $features : [];
    }

    /**
     * Validate properties
     */
    public function validateProperties(): void
    {
        // Validation logic can be implemented here if needed
    }

    /**
     * Get the view name for this component
     */
    protected function getViewName(): string
    {
        return 'components.builder.cards.pricing-card';
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('components.builder.cards.pricing-card', [
            'cardInstance' => $this,
            'properties' => $this->properties
        ]);
    }
}
