<?php

namespace App\View\Components\Builder\Layout;

use App\View\Components\Builder\BaseComponent;

/**
 * Container Component
 * 
 * Basic container component that wraps content in a responsive container.
 * Uses Tabler.io container classes (.container or .container-fluid).
 */
class Container extends BaseComponent
{
    /**
     * Component metadata
     */
    protected array $meta = [
        'name' => 'Container',
        'category' => 'layout',
        'icon' => 'ti ti-layout-grid',
        'description' => 'Responsive container that centers and constrains content width',
        'version' => '1.0.0'
    ];

    /**
     * Default CSS classes for this component
     */
    protected array $defaultClasses = ['container'];

    /**
     * Get default properties for the component
     */
    public function getDefaultProperties(): array
    {
        return [
            'fluid' => false,
            'padding' => '',
            'margin' => '',
            'background' => '',
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
        // Ensure fluid is boolean
        $this->properties['fluid'] = (bool) $this->properties['fluid'];

        // Validate padding values
        $allowedPadding = ['', 'p-0', 'p-1', 'p-2', 'p-3', 'p-4', 'p-5'];
        if (!in_array($this->properties['padding'], $allowedPadding)) {
            $this->properties['padding'] = '';
        }

        // Validate margin values
        $allowedMargin = ['', 'm-0', 'm-1', 'm-2', 'm-3', 'm-4', 'm-5', 'mt-auto', 'mb-auto', 'my-auto'];
        if (!in_array($this->properties['margin'], $allowedMargin)) {
            $this->properties['margin'] = '';
        }

        // Validate background values
        $allowedBg = ['', 'bg-primary', 'bg-secondary', 'bg-success', 'bg-warning', 'bg-danger', 'bg-info', 'bg-light', 'bg-dark', 'bg-white', 'bg-transparent'];
        if (!in_array($this->properties['background'], $allowedBg)) {
            $this->properties['background'] = '';
        }

        // Validate text alignment
        $allowedAlign = ['', 'text-start', 'text-center', 'text-end'];
        if (!in_array($this->properties['text_align'], $allowedAlign)) {
            $this->properties['text_align'] = '';
        }

        // Validate min height
        $allowedHeight = ['', 'vh-100', 'min-vh-100'];
        if (!in_array($this->properties['min_height'], $allowedHeight)) {
            $this->properties['min_height'] = '';
        }
    }

    /**
     * Get the component view name
     */
    protected function getViewName(): string
    {
        return 'components.builder.layout.container';
    }

    /**
     * Generate CSS classes based on properties
     */
    public function getCssClasses(): string
    {
        $classes = [];

        // Container type
        if ($this->getProperty('fluid', false)) {
            $classes[] = 'container-fluid';
        } else {
            $classes[] = 'container';
        }

        // Add spacing
        if ($padding = $this->getProperty('padding')) {
            $classes[] = $padding;
        }

        if ($margin = $this->getProperty('margin')) {
            $classes[] = $margin;
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
     * Get property definitions for the builder UI
     */
    public function getBuilderProperties(): array
    {
        return array_merge(parent::getBuilderProperties(), [
            'fluid' => [
                'type' => 'checkbox',
                'label' => 'Fluid Container',
                'description' => 'Use full width container instead of fixed max-width'
            ],
            'padding' => [
                'type' => 'select',
                'label' => 'Padding',
                'description' => 'Add padding around the container content',
                'options' => [
                    '' => 'No padding',
                    'p-1' => 'Small (0.25rem)',
                    'p-2' => 'Medium (0.5rem)',
                    'p-3' => 'Default (1rem)',
                    'p-4' => 'Large (1.5rem)',
                    'p-5' => 'Extra Large (3rem)'
                ]
            ],
            'margin' => [
                'type' => 'select',
                'label' => 'Margin',
                'description' => 'Add margin around the container',
                'options' => [
                    '' => 'No margin',
                    'm-1' => 'Small (0.25rem)',
                    'm-2' => 'Medium (0.5rem)',
                    'm-3' => 'Default (1rem)',
                    'm-4' => 'Large (1.5rem)',
                    'm-5' => 'Extra Large (3rem)',
                    'mt-auto' => 'Auto top margin',
                    'mb-auto' => 'Auto bottom margin',
                    'my-auto' => 'Auto vertical margin'
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
            'text_align' => [
                'type' => 'select',
                'label' => 'Text Alignment',
                'description' => 'Set text alignment for container content',
                'options' => [
                    '' => 'Default',
                    'text-start' => 'Left',
                    'text-center' => 'Center',
                    'text-end' => 'Right'
                ]
            ],
            'min_height' => [
                'type' => 'select',
                'label' => 'Minimum Height',
                'description' => 'Set minimum height for the container',
                'options' => [
                    '' => 'Auto',
                    'vh-100' => 'Full viewport height',
                    'min-vh-100' => 'Minimum full viewport height'
                ]
            ]
        ]);
    }
}
