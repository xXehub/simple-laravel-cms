<?php

namespace App\View\Components\Builder\Content;

use App\View\Components\Builder\BaseComponent;

/**
 * Heading Component
 * 
 * Creates headings (H1-H6) with Tabler.io typography and styling options.
 * Supports all heading levels with color, alignment, and weight controls.
 */
class Heading extends BaseComponent
{
    /**
     * Component metadata
     */
    protected array $meta = [
        'name' => 'Heading',
        'category' => 'content',
        'icon' => 'ti ti-heading',
        'description' => 'Text headings (H1-H6) with typography and styling options',
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
            'text' => 'Your Heading Text',
            'level' => 'h2',
            'color' => '',
            'text_align' => '',
            'font_weight' => '',
            'font_size' => '',
            'margin_top' => '',
            'margin_bottom' => 'mb-3',
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
        // Validate heading level
        $allowedLevels = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'];
        if (!in_array($this->properties['level'], $allowedLevels)) {
            $this->properties['level'] = 'h2';
        }

        // Validate color
        $allowedColors = ['', 'text-primary', 'text-secondary', 'text-success', 'text-warning', 'text-danger', 'text-info', 'text-light', 'text-dark', 'text-muted', 'text-white'];
        if (!in_array($this->properties['color'], $allowedColors)) {
            $this->properties['color'] = '';
        }

        // Validate text alignment
        $allowedAlign = ['', 'text-start', 'text-center', 'text-end'];
        if (!in_array($this->properties['text_align'], $allowedAlign)) {
            $this->properties['text_align'] = '';
        }

        // Validate font weight
        $allowedWeight = ['', 'fw-light', 'fw-normal', 'fw-medium', 'fw-semibold', 'fw-bold'];
        if (!in_array($this->properties['font_weight'], $allowedWeight)) {
            $this->properties['font_weight'] = '';
        }

        // Validate font size
        $allowedSize = ['', 'fs-1', 'fs-2', 'fs-3', 'fs-4', 'fs-5', 'fs-6'];
        if (!in_array($this->properties['font_size'], $allowedSize)) {
            $this->properties['font_size'] = '';
        }

        // Validate margins
        $allowedMargin = ['', 'm-0', 'm-1', 'm-2', 'm-3', 'm-4', 'm-5', 'mt-0', 'mt-1', 'mt-2', 'mt-3', 'mt-4', 'mt-5', 'mb-0', 'mb-1', 'mb-2', 'mb-3', 'mb-4', 'mb-5'];
        
        if (!in_array($this->properties['margin_top'], $allowedMargin)) {
            $this->properties['margin_top'] = '';
        }
        
        if (!in_array($this->properties['margin_bottom'], $allowedMargin)) {
            $this->properties['margin_bottom'] = 'mb-3';
        }

        // Sanitize text content
        $this->properties['text'] = htmlspecialchars($this->properties['text']);
    }

    /**
     * Get the component view name
     */
    protected function getViewName(): string
    {
        return 'components.builder.content.heading';
    }

    /**
     * Generate CSS classes based on properties
     */
    public function getCssClasses(): string
    {
        $classes = [];

        // Add color class
        if ($color = $this->getProperty('color')) {
            $classes[] = $color;
        }

        // Add text alignment
        if ($textAlign = $this->getProperty('text_align')) {
            $classes[] = $textAlign;
        }

        // Add font weight
        if ($fontWeight = $this->getProperty('font_weight')) {
            $classes[] = $fontWeight;
        }

        // Add font size override
        if ($fontSize = $this->getProperty('font_size')) {
            $classes[] = $fontSize;
        }

        // Add margins
        if ($marginTop = $this->getProperty('margin_top')) {
            $classes[] = $marginTop;
        }

        if ($marginBottom = $this->getProperty('margin_bottom')) {
            $classes[] = $marginBottom;
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
     * Get the heading tag based on level
     */
    public function getHeadingTag(): string
    {
        return $this->getProperty('level', 'h2');
    }

    /**
     * Get property definitions for the builder UI
     */
    public function getBuilderProperties(): array
    {
        return array_merge(parent::getBuilderProperties(), [
            'text' => [
                'type' => 'text',
                'label' => 'Heading Text',
                'description' => 'The text content for the heading',
                'required' => true,
                'placeholder' => 'Enter your heading text...'
            ],
            'level' => [
                'type' => 'select',
                'label' => 'Heading Level',
                'description' => 'Choose the semantic heading level',
                'options' => [
                    'h1' => 'H1 (Main title)',
                    'h2' => 'H2 (Section title)',
                    'h3' => 'H3 (Subsection title)',
                    'h4' => 'H4 (Minor title)',
                    'h5' => 'H5 (Small title)',
                    'h6' => 'H6 (Smallest title)'
                ]
            ],
            'color' => [
                'type' => 'select',
                'label' => 'Text Color',
                'description' => 'Choose the text color using Tabler color scheme',
                'options' => [
                    '' => 'Default color',
                    'text-primary' => 'Primary',
                    'text-secondary' => 'Secondary',
                    'text-success' => 'Success',
                    'text-warning' => 'Warning',
                    'text-danger' => 'Danger',
                    'text-info' => 'Info',
                    'text-light' => 'Light',
                    'text-dark' => 'Dark',
                    'text-muted' => 'Muted',
                    'text-white' => 'White'
                ]
            ],
            'text_align' => [
                'type' => 'select',
                'label' => 'Text Alignment',
                'description' => 'Set the text alignment',
                'options' => [
                    '' => 'Default',
                    'text-start' => 'Left',
                    'text-center' => 'Center',
                    'text-end' => 'Right'
                ]
            ],
            'font_weight' => [
                'type' => 'select',
                'label' => 'Font Weight',
                'description' => 'Set the font weight',
                'options' => [
                    '' => 'Default',
                    'fw-light' => 'Light',
                    'fw-normal' => 'Normal',
                    'fw-medium' => 'Medium',
                    'fw-semibold' => 'Semi Bold',
                    'fw-bold' => 'Bold'
                ]
            ],
            'font_size' => [
                'type' => 'select',
                'label' => 'Font Size Override',
                'description' => 'Override the default heading size',
                'options' => [
                    '' => 'Default size for level',
                    'fs-1' => 'Extra Large',
                    'fs-2' => 'Large',
                    'fs-3' => 'Medium Large',
                    'fs-4' => 'Medium',
                    'fs-5' => 'Small',
                    'fs-6' => 'Extra Small'
                ]
            ],
            'margin_top' => [
                'type' => 'select',
                'label' => 'Top Margin',
                'description' => 'Add margin above the heading',
                'options' => [
                    '' => 'No margin',
                    'mt-1' => 'Small margin',
                    'mt-2' => 'Medium margin',
                    'mt-3' => 'Default margin',
                    'mt-4' => 'Large margin',
                    'mt-5' => 'Extra large margin'
                ]
            ],
            'margin_bottom' => [
                'type' => 'select',
                'label' => 'Bottom Margin',
                'description' => 'Add margin below the heading',
                'options' => [
                    'mb-0' => 'No margin',
                    'mb-1' => 'Small margin',
                    'mb-2' => 'Medium margin',
                    'mb-3' => 'Default margin',
                    'mb-4' => 'Large margin',
                    'mb-5' => 'Extra large margin'
                ]
            ]
        ]);
    }
}
