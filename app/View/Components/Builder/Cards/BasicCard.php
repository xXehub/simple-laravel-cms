<?php

namespace App\View\Components\Builder\Cards;

use App\View\Components\Builder\BaseComponent;

/**
 * Basic Card Component
 * 
 * Creates a basic card with header, body, and footer sections.
 * Perfect for content organization and layout structure.
 */
class BasicCard extends BaseComponent
{
    /**
     * Component metadata
     */
    protected array $meta = [
        'name' => 'Basic Card',
        'category' => 'cards',
        'icon' => 'ti ti-layout-cards',
        'description' => 'Basic card with header, body, and footer sections',
        'version' => '1.0.0'
    ];

    /**
     * Default CSS classes for this component
     */
    protected array $defaultClasses = ['card'];

    /**
     * Get default properties for the component
     */
    public function getDefaultProperties(): array
    {
        return [
            'header_title' => 'Card Title',
            'header_subtitle' => '',
            'body_content' => 'This is the card body content. You can add any HTML content here.',
            'footer_content' => '',
            'show_header' => true,
            'show_footer' => false,
            'header_background' => '',
            'card_style' => '',
            'border_style' => '',
            'shadow' => 'shadow-sm',
            'margin' => 'mb-3',
            'padding' => '',
            'height' => '',
            'css_classes' => [],
            'id' => '',
            'data_attributes' => []
        ];
    }

    /**
     * Validate component properties
     */
    protected function validateProperties(): void
    {
        // Validate card style
        $allowedStyles = ['', 'card-primary', 'card-secondary', 'card-success', 'card-warning', 'card-danger', 'card-info'];
        if (!in_array($this->properties['card_style'], $allowedStyles)) {
            $this->properties['card_style'] = '';
        }

        // Validate border style
        $allowedBorders = ['', 'border-primary', 'border-secondary', 'border-success', 'border-warning', 'border-danger', 'border-info'];
        if (!in_array($this->properties['border_style'], $allowedBorders)) {
            $this->properties['border_style'] = '';
        }

        // Validate shadow
        $allowedShadows = ['', 'shadow-none', 'shadow-sm', 'shadow', 'shadow-lg'];
        if (!in_array($this->properties['shadow'], $allowedShadows)) {
            $this->properties['shadow'] = 'shadow-sm';
        }

        // Validate margin
        $allowedMargin = ['', 'mb-2', 'mb-3', 'mb-4', 'mb-5', 'm-2', 'm-3', 'm-4'];
        if (!in_array($this->properties['margin'], $allowedMargin)) {
            $this->properties['margin'] = 'mb-3';
        }

        // Validate padding
        $allowedPadding = ['', 'p-2', 'p-3', 'p-4', 'p-5'];
        if (!in_array($this->properties['padding'], $allowedPadding)) {
            $this->properties['padding'] = '';
        }

        // Validate height
        $allowedHeight = ['', 'h-25', 'h-50', 'h-75', 'h-100'];
        if (!in_array($this->properties['height'], $allowedHeight)) {
            $this->properties['height'] = '';
        }

        // Validate header background
        $allowedHeaderBg = ['', 'bg-primary', 'bg-secondary', 'bg-success', 'bg-warning', 'bg-danger', 'bg-info', 'bg-light', 'bg-dark'];
        if (!in_array($this->properties['header_background'], $allowedHeaderBg)) {
            $this->properties['header_background'] = '';
        }

        // Validate boolean properties
        $this->properties['show_header'] = (bool) $this->properties['show_header'];
        $this->properties['show_footer'] = (bool) $this->properties['show_footer'];

        // Sanitize content
        $this->properties['header_title'] = htmlspecialchars($this->properties['header_title']);
        $this->properties['header_subtitle'] = htmlspecialchars($this->properties['header_subtitle']);
        // Note: body_content and footer_content allow HTML, so we don't escape them
    }

    /**
     * Get the component view name
     */
    protected function getViewName(): string
    {
        return 'components.builder.cards.basic-card';
    }

    /**
     * Generate CSS classes based on properties
     */
    public function getCssClasses(): string
    {
        $classes = ['card'];

        // Add card style
        if ($style = $this->getProperty('card_style')) {
            $classes[] = $style;
        }

        // Add border style
        if ($border = $this->getProperty('border_style')) {
            $classes[] = $border;
        }

        // Add shadow
        if ($shadow = $this->getProperty('shadow')) {
            $classes[] = $shadow;
        }

        // Add margin
        if ($margin = $this->getProperty('margin')) {
            $classes[] = $margin;
        }

        // Add padding
        if ($padding = $this->getProperty('padding')) {
            $classes[] = $padding;
        }

        // Add height
        if ($height = $this->getProperty('height')) {
            $classes[] = $height;
        }

        // Add custom classes
        $customClasses = $this->getProperty('css_classes', []);
        if (is_array($customClasses)) {
            $classes = array_merge($classes, $customClasses);
        }

        // Filter allowed classes and return
        $classes = array_filter($classes, [$this, 'isAllowedClass']);
        return implode(' ', array_unique($classes));
    }

    /**
     * Get header CSS classes
     */
    public function getHeaderClasses(): string
    {
        $classes = ['card-header'];

        // Add header background
        if ($bg = $this->getProperty('header_background')) {
            $classes[] = $bg;
            // Add text color for better contrast
            if (in_array($bg, ['bg-primary', 'bg-secondary', 'bg-success', 'bg-danger', 'bg-info', 'bg-dark'])) {
                $classes[] = 'text-white';
            }
        }

        return implode(' ', $classes);
    }

    /**
     * Get property definitions for the builder UI
     */
    public function getBuilderProperties(): array
    {
        return array_merge(parent::getBuilderProperties(), [
            'show_header' => [
                'type' => 'checkbox',
                'label' => 'Show Header',
                'description' => 'Display the card header section'
            ],
            'header_title' => [
                'type' => 'text',
                'label' => 'Header Title',
                'description' => 'Main title text in the card header',
                'required' => false,
                'placeholder' => 'Enter card title...'
            ],
            'header_subtitle' => [
                'type' => 'text',
                'label' => 'Header Subtitle',
                'description' => 'Optional subtitle text in the card header',
                'placeholder' => 'Enter subtitle...'
            ],
            'header_background' => [
                'type' => 'select',
                'label' => 'Header Background',
                'description' => 'Background color for the card header',
                'options' => [
                    '' => 'Default',
                    'bg-primary' => 'Primary',
                    'bg-secondary' => 'Secondary',
                    'bg-success' => 'Success',
                    'bg-warning' => 'Warning',
                    'bg-danger' => 'Danger',
                    'bg-info' => 'Info',
                    'bg-light' => 'Light',
                    'bg-dark' => 'Dark'
                ]
            ],
            'body_content' => [
                'type' => 'textarea',
                'label' => 'Body Content',
                'description' => 'Main content of the card (HTML allowed)',
                'required' => true,
                'placeholder' => 'Enter card content...',
                'rows' => 4
            ],
            'show_footer' => [
                'type' => 'checkbox',
                'label' => 'Show Footer',
                'description' => 'Display the card footer section'
            ],
            'footer_content' => [
                'type' => 'textarea',
                'label' => 'Footer Content',
                'description' => 'Content for the card footer (HTML allowed)',
                'placeholder' => 'Enter footer content...',
                'rows' => 2
            ],
            'card_style' => [
                'type' => 'select',
                'label' => 'Card Style',
                'description' => 'Overall styling theme for the card',
                'options' => [
                    '' => 'Default',
                    'card-primary' => 'Primary',
                    'card-secondary' => 'Secondary',
                    'card-success' => 'Success',
                    'card-warning' => 'Warning',
                    'card-danger' => 'Danger',
                    'card-info' => 'Info'
                ]
            ],
            'border_style' => [
                'type' => 'select',
                'label' => 'Border Style',
                'description' => 'Border color for the card',
                'options' => [
                    '' => 'Default',
                    'border-primary' => 'Primary',
                    'border-secondary' => 'Secondary',
                    'border-success' => 'Success',
                    'border-warning' => 'Warning',
                    'border-danger' => 'Danger',
                    'border-info' => 'Info'
                ]
            ],
            'shadow' => [
                'type' => 'select',
                'label' => 'Shadow',
                'description' => 'Shadow effect for the card',
                'options' => [
                    'shadow-none' => 'No shadow',
                    'shadow-sm' => 'Small shadow',
                    'shadow' => 'Normal shadow',
                    'shadow-lg' => 'Large shadow'
                ]
            ],
            'margin' => [
                'type' => 'select',
                'label' => 'Margin',
                'description' => 'Spacing around the card',
                'options' => [
                    '' => 'No margin',
                    'mb-2' => 'Bottom margin (small)',
                    'mb-3' => 'Bottom margin (medium)',
                    'mb-4' => 'Bottom margin (large)',
                    'mb-5' => 'Bottom margin (extra large)',
                    'm-2' => 'All sides margin (small)',
                    'm-3' => 'All sides margin (medium)',
                    'm-4' => 'All sides margin (large)'
                ]
            ],
            'height' => [
                'type' => 'select',
                'label' => 'Height',
                'description' => 'Fixed height for the card',
                'options' => [
                    '' => 'Auto height',
                    'h-25' => '25% height',
                    'h-50' => '50% height',
                    'h-75' => '75% height',
                    'h-100' => '100% height'
                ]
            ]
        ]);
    }
}
