<?php

namespace App\View\Components\Builder\Cards;

use App\View\Components\Builder\BaseComponent;

/**
 * Feature Card Component
 * 
 * A card component for showcasing features with icon, title, description,
 * and optional call-to-action button.
 */
class FeatureCard extends BaseComponent
{
    /**
     * Component metadata
     */
    protected array $meta = [
        'name' => 'Feature Card',
        'category' => 'cards',
        'icon' => 'ti ti-star',
        'description' => 'A card for showcasing features with icon and description',
        'version' => '1.0.0'
    ];

    /**
     * Default properties
     */
    public function getDefaultProperties(): array
    {
        return [
            // Content
            'title' => 'Awesome Feature',
            'description' => 'This is a great feature that will help your users accomplish their goals.',
            'icon' => 'ti ti-star',
            'icon_size' => '48',
            'icon_color' => 'text-primary',
            
            // Button
            'button_text' => '',
            'button_url' => '#',
            'button_variant' => 'btn-primary',
            'button_size' => '',
            'button_target' => '_self',
            
            // Layout
            'icon_position' => 'top',
            'text_align' => 'text-center',
            'hover_effect' => true,
            
            // Styling (Tabler.io classes only)
            'shadow' => 'shadow-sm',
            'border_radius' => 'rounded',
            'padding' => 'p-4',
            'margin' => 'mb-4',
        ];
    }

    /**
     * Get property definitions for the builder UI
     */
    public function getBuilderProperties(): array
    {
        return array_merge(parent::getBuilderProperties(), [
            'title' => [
                'type' => 'text',
                'label' => 'Title',
                'placeholder' => 'Enter feature title',
                'required' => true,
            ],
            'description' => [
                'type' => 'textarea',
                'label' => 'Description',
                'placeholder' => 'Enter feature description',
                'rows' => 3,
            ],
            'icon' => [
                'type' => 'icon',
                'label' => 'Icon',
                'placeholder' => 'ti ti-star',
                'help' => 'Tabler icon class',
            ],
            'icon_size' => [
                'type' => 'select',
                'label' => 'Icon Size',
                'options' => [
                    '24' => 'Small (24px)',
                    '32' => 'Medium (32px)',
                    '48' => 'Large (48px)',
                    '64' => 'Extra Large (64px)',
                ],
            ],
            'icon_color' => [
                'type' => 'select',
                'label' => 'Icon Color',
                'options' => [
                    'text-primary' => 'Primary',
                    'text-secondary' => 'Secondary',
                    'text-success' => 'Success',
                    'text-info' => 'Info',
                    'text-warning' => 'Warning',
                    'text-danger' => 'Danger',
                    'text-dark' => 'Dark',
                    'text-muted' => 'Muted',
                ],
            ],
            'button_text' => [
                'type' => 'text',
                'label' => 'Button Text',
                'placeholder' => 'Learn More',
            ],
            'button_url' => [
                'type' => 'url',
                'label' => 'Button URL',
                'placeholder' => 'https://example.com',
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
            'icon_position' => [
                'type' => 'select',
                'label' => 'Icon Position',
                'options' => [
                    'top' => 'Top',
                    'left' => 'Left',
                    'right' => 'Right',
                ],
            ],
            'text_align' => [
                'type' => 'select',
                'label' => 'Text Alignment',
                'options' => [
                    'text-center' => 'Center',
                    'text-start' => 'Left',
                    'text-end' => 'Right',
                ],
            ],
            'hover_effect' => [
                'type' => 'boolean',
                'label' => 'Hover Effect',
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
        $classes[] = $this->getProperty('padding', 'p-4');
        $classes[] = $this->getProperty('text_align', 'text-center');
        
        // Add height
        if ($this->getProperty('card_height')) {
            $classes[] = $this->getProperty('card_height');
        }
        
        // Add hover effect
        if ($this->getProperty('hover_effect', true)) {
            $classes[] = 'feature-card-hover';
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
     * Get icon classes
     */
    public function getIconClasses(): string
    {
        $classes = [];
        
        // Base icon class
        $iconClass = $this->getProperty('icon', 'ti ti-star');
        $classes[] = $iconClass;
        
        // Icon color
        $iconColor = $this->getProperty('icon_color', 'text-primary');
        $classes[] = $iconColor;
        
        return implode(' ', $classes);
    }

    /**
     * Get icon size
     */
    public function getIconSize(): string
    {
        return $this->getProperty('icon_size', '48');
    }

    /**
     * Check if has button
     */
    public function hasButton(): bool
    {
        return !empty($this->getProperty('button_text'));
    }

    /**
     * Get button classes
     */
    public function getButtonClasses(): string
    {
        $classes = ['btn'];
        
        $classes[] = $this->getProperty('button_variant', 'btn-primary');
        
        if ($size = $this->getProperty('button_size')) {
            $classes[] = $size;
        }
        
        return implode(' ', $classes);
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
        return 'components.builder.cards.feature-card';
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('components.builder.cards.feature-card', [
            'cardInstance' => $this,
            'properties' => $this->properties
        ]);
    }
}
