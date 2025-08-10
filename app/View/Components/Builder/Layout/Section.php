<?php

namespace App\View\Components\Builder\Layout;

use App\View\Components\Builder\BaseComponent;

/**
 * Section Component
 * 
 * Semantic section component for organizing page content.
 * Uses HTML5 <section> element with Tabler.io styling utilities.
 */
class Section extends BaseComponent
{
    /**
     * Component metadata
     */
    protected array $meta = [
        'name' => 'Section',
        'category' => 'layout',
        'icon' => 'ti ti-section',
        'description' => 'Semantic section element for organizing page content',
        'version' => '1.0.0'
    ];

    /**
     * Default CSS classes for this component
     */
    protected array $defaultClasses = [];

    /**
     * Get default properties for the component
     */
    public function getDefaultProperties(): array
    {
        return [
            'padding_top' => '',
            'padding_bottom' => '',
            'padding_x' => '',
            'margin_top' => '',
            'margin_bottom' => '',
            'background' => '',
            'background_image' => '',
            'background_size' => '',
            'background_position' => '',
            'min_height' => '',
            'text_align' => '',
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
        // Validate padding values
        $allowedPadding = ['', 'pt-0', 'pt-1', 'pt-2', 'pt-3', 'pt-4', 'pt-5', 'pb-0', 'pb-1', 'pb-2', 'pb-3', 'pb-4', 'pb-5'];
        
        if (!in_array($this->properties['padding_top'], $allowedPadding)) {
            $this->properties['padding_top'] = '';
        }
        
        if (!in_array($this->properties['padding_bottom'], $allowedPadding)) {
            $this->properties['padding_bottom'] = '';
        }

        $allowedPaddingX = ['', 'px-0', 'px-1', 'px-2', 'px-3', 'px-4', 'px-5'];
        if (!in_array($this->properties['padding_x'], $allowedPaddingX)) {
            $this->properties['padding_x'] = '';
        }

        // Validate margin values
        $allowedMargin = ['', 'mt-0', 'mt-1', 'mt-2', 'mt-3', 'mt-4', 'mt-5', 'mb-0', 'mb-1', 'mb-2', 'mb-3', 'mb-4', 'mb-5'];
        
        if (!in_array($this->properties['margin_top'], $allowedMargin)) {
            $this->properties['margin_top'] = '';
        }
        
        if (!in_array($this->properties['margin_bottom'], $allowedMargin)) {
            $this->properties['margin_bottom'] = '';
        }

        // Validate background values
        $allowedBg = ['', 'bg-primary', 'bg-secondary', 'bg-success', 'bg-warning', 'bg-danger', 'bg-info', 'bg-light', 'bg-dark', 'bg-white', 'bg-transparent'];
        if (!in_array($this->properties['background'], $allowedBg)) {
            $this->properties['background'] = '';
        }

        // Validate background size
        $allowedBgSize = ['', 'cover', 'contain', 'auto'];
        if (!in_array($this->properties['background_size'], $allowedBgSize)) {
            $this->properties['background_size'] = '';
        }

        // Validate background position
        $allowedBgPosition = ['', 'center', 'top', 'bottom', 'left', 'right', 'top left', 'top right', 'bottom left', 'bottom right'];
        if (!in_array($this->properties['background_position'], $allowedBgPosition)) {
            $this->properties['background_position'] = '';
        }

        // Validate min height
        $allowedHeight = ['', 'vh-25', 'vh-50', 'vh-75', 'vh-100', 'min-vh-100'];
        if (!in_array($this->properties['min_height'], $allowedHeight)) {
            $this->properties['min_height'] = '';
        }

        // Validate text alignment
        $allowedAlign = ['', 'text-start', 'text-center', 'text-end'];
        if (!in_array($this->properties['text_align'], $allowedAlign)) {
            $this->properties['text_align'] = '';
        }

        // Sanitize background image URL
        if (!empty($this->properties['background_image'])) {
            $this->properties['background_image'] = filter_var($this->properties['background_image'], FILTER_SANITIZE_URL);
        }
    }

    /**
     * Get the component view name
     */
    protected function getViewName(): string
    {
        return 'components.builder.layout.section';
    }

    /**
     * Generate CSS classes based on properties
     */
    public function getCssClasses(): string
    {
        $classes = [];

        // Add spacing classes
        if ($paddingTop = $this->getProperty('padding_top')) {
            $classes[] = $paddingTop;
        }

        if ($paddingBottom = $this->getProperty('padding_bottom')) {
            $classes[] = $paddingBottom;
        }

        if ($paddingX = $this->getProperty('padding_x')) {
            $classes[] = $paddingX;
        }

        if ($marginTop = $this->getProperty('margin_top')) {
            $classes[] = $marginTop;
        }

        if ($marginBottom = $this->getProperty('margin_bottom')) {
            $classes[] = $marginBottom;
        }

        // Add background
        if ($background = $this->getProperty('background')) {
            $classes[] = $background;
        }

        // Add text alignment
        if ($textAlign = $this->getProperty('text_align')) {
            $classes[] = $textAlign;
        }

        // Add min height
        if ($minHeight = $this->getProperty('min_height')) {
            $classes[] = $minHeight;
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
     * Get inline styles for background image
     */
    public function getInlineStyles(): string
    {
        $styles = [];

        if ($backgroundImage = $this->getProperty('background_image')) {
            $styles[] = "background-image: url('" . htmlspecialchars($backgroundImage) . "')";
            
            if ($backgroundSize = $this->getProperty('background_size')) {
                $styles[] = "background-size: {$backgroundSize}";
            }
            
            if ($backgroundPosition = $this->getProperty('background_position')) {
                $styles[] = "background-position: {$backgroundPosition}";
            }
            
            $styles[] = "background-repeat: no-repeat";
        }

        return implode('; ', $styles);
    }

    /**
     * Get property definitions for the builder UI
     */
    public function getBuilderProperties(): array
    {
        return array_merge(parent::getBuilderProperties(), [
            'padding_top' => [
                'type' => 'select',
                'label' => 'Top Padding',
                'description' => 'Add padding to the top of the section',
                'options' => [
                    '' => 'No padding',
                    'pt-1' => 'Small (0.25rem)',
                    'pt-2' => 'Medium (0.5rem)',
                    'pt-3' => 'Default (1rem)',
                    'pt-4' => 'Large (1.5rem)',
                    'pt-5' => 'Extra Large (3rem)'
                ]
            ],
            'padding_bottom' => [
                'type' => 'select',
                'label' => 'Bottom Padding',
                'description' => 'Add padding to the bottom of the section',
                'options' => [
                    '' => 'No padding',
                    'pb-1' => 'Small (0.25rem)',
                    'pb-2' => 'Medium (0.5rem)',
                    'pb-3' => 'Default (1rem)',
                    'pb-4' => 'Large (1.5rem)',
                    'pb-5' => 'Extra Large (3rem)'
                ]
            ],
            'padding_x' => [
                'type' => 'select',
                'label' => 'Horizontal Padding',
                'description' => 'Add padding to the left and right of the section',
                'options' => [
                    '' => 'No padding',
                    'px-1' => 'Small (0.25rem)',
                    'px-2' => 'Medium (0.5rem)',
                    'px-3' => 'Default (1rem)',
                    'px-4' => 'Large (1.5rem)',
                    'px-5' => 'Extra Large (3rem)'
                ]
            ],
            'margin_top' => [
                'type' => 'select',
                'label' => 'Top Margin',
                'description' => 'Add margin to the top of the section',
                'options' => [
                    '' => 'No margin',
                    'mt-1' => 'Small (0.25rem)',
                    'mt-2' => 'Medium (0.5rem)',
                    'mt-3' => 'Default (1rem)',
                    'mt-4' => 'Large (1.5rem)',
                    'mt-5' => 'Extra Large (3rem)'
                ]
            ],
            'margin_bottom' => [
                'type' => 'select',
                'label' => 'Bottom Margin',
                'description' => 'Add margin to the bottom of the section',
                'options' => [
                    '' => 'No margin',
                    'mb-1' => 'Small (0.25rem)',
                    'mb-2' => 'Medium (0.5rem)',
                    'mb-3' => 'Default (1rem)',
                    'mb-4' => 'Large (1.5rem)',
                    'mb-5' => 'Extra Large (3rem)'
                ]
            ],
            'background' => [
                'type' => 'select',
                'label' => 'Background Color',
                'description' => 'Set the background color using Tabler color scheme',
                'options' => [
                    '' => 'No background',
                    'bg-primary' => 'Primary',
                    'bg-secondary' => 'Secondary',
                    'bg-success' => 'Success',
                    'bg-warning' => 'Warning',
                    'bg-danger' => 'Danger',
                    'bg-info' => 'Info',
                    'bg-light' => 'Light',
                    'bg-dark' => 'Dark',
                    'bg-white' => 'White',
                    'bg-transparent' => 'Transparent'
                ]
            ],
            'background_image' => [
                'type' => 'url',
                'label' => 'Background Image URL',
                'description' => 'URL of the background image'
            ],
            'background_size' => [
                'type' => 'select',
                'label' => 'Background Size',
                'description' => 'How the background image should be sized',
                'options' => [
                    '' => 'Default',
                    'cover' => 'Cover (fill area)',
                    'contain' => 'Contain (fit within area)',
                    'auto' => 'Auto (original size)'
                ]
            ],
            'background_position' => [
                'type' => 'select',
                'label' => 'Background Position',
                'description' => 'Position of the background image',
                'options' => [
                    '' => 'Default',
                    'center' => 'Center',
                    'top' => 'Top',
                    'bottom' => 'Bottom',
                    'left' => 'Left',
                    'right' => 'Right',
                    'top left' => 'Top Left',
                    'top right' => 'Top Right',
                    'bottom left' => 'Bottom Left',
                    'bottom right' => 'Bottom Right'
                ]
            ],
            'min_height' => [
                'type' => 'select',
                'label' => 'Minimum Height',
                'description' => 'Set minimum height for the section',
                'options' => [
                    '' => 'Auto',
                    'vh-25' => '25% of viewport',
                    'vh-50' => '50% of viewport',
                    'vh-75' => '75% of viewport',
                    'vh-100' => 'Full viewport height',
                    'min-vh-100' => 'Minimum full viewport height'
                ]
            ],
            'text_align' => [
                'type' => 'select',
                'label' => 'Text Alignment',
                'description' => 'Set text alignment for section content',
                'options' => [
                    '' => 'Default',
                    'text-start' => 'Left',
                    'text-center' => 'Center',
                    'text-end' => 'Right'
                ]
            ]
        ]);
    }
}
