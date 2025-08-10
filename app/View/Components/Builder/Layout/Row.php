<?php

namespace App\View\Components\Builder\Layout;

use App\View\Components\Builder\BaseComponent;

/**
 * Row Component
 * 
 * Bootstrap row component for creating horizontal groups of columns.
 * Uses Tabler.io row classes with gutter and alignment utilities.
 */
class Row extends BaseComponent
{
    /**
     * Component metadata
     */
    protected array $meta = [
        'name' => 'Row',
        'category' => 'layout',
        'icon' => 'ti ti-layout-rows',
        'description' => 'Horizontal container for organizing columns in a grid layout',
        'version' => '1.0.0'
    ];

    /**
     * Default CSS classes for this component
     */
    protected array $defaultClasses = ['row'];

    /**
     * Get default properties for the component
     */
    public function getDefaultProperties(): array
    {
        return [
            'gutter' => '',
            'align_items' => '',
            'justify_content' => '',
            'flex_wrap' => '',
            'margin' => '',
            'padding' => '',
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
        // Validate gutter values
        $allowedGutter = ['', 'g-0', 'g-1', 'g-2', 'g-3', 'g-4', 'g-5', 'gx-0', 'gx-1', 'gx-2', 'gx-3', 'gx-4', 'gx-5', 'gy-0', 'gy-1', 'gy-2', 'gy-3', 'gy-4', 'gy-5'];
        if (!in_array($this->properties['gutter'], $allowedGutter)) {
            $this->properties['gutter'] = '';
        }

        // Validate align items
        $allowedAlign = ['', 'align-items-start', 'align-items-center', 'align-items-end', 'align-items-stretch'];
        if (!in_array($this->properties['align_items'], $allowedAlign)) {
            $this->properties['align_items'] = '';
        }

        // Validate justify content
        $allowedJustify = ['', 'justify-content-start', 'justify-content-center', 'justify-content-end', 'justify-content-between', 'justify-content-around', 'justify-content-evenly'];
        if (!in_array($this->properties['justify_content'], $allowedJustify)) {
            $this->properties['justify_content'] = '';
        }

        // Validate flex wrap
        $allowedWrap = ['', 'flex-wrap', 'flex-nowrap', 'flex-wrap-reverse'];
        if (!in_array($this->properties['flex_wrap'], $allowedWrap)) {
            $this->properties['flex_wrap'] = '';
        }

        // Validate margin values
        $allowedMargin = ['', 'm-0', 'm-1', 'm-2', 'm-3', 'm-4', 'm-5', 'mt-0', 'mt-1', 'mt-2', 'mt-3', 'mt-4', 'mt-5', 'mb-0', 'mb-1', 'mb-2', 'mb-3', 'mb-4', 'mb-5'];
        if (!in_array($this->properties['margin'], $allowedMargin)) {
            $this->properties['margin'] = '';
        }

        // Validate padding values
        $allowedPadding = ['', 'p-0', 'p-1', 'p-2', 'p-3', 'p-4', 'p-5', 'pt-0', 'pt-1', 'pt-2', 'pt-3', 'pt-4', 'pt-5', 'pb-0', 'pb-1', 'pb-2', 'pb-3', 'pb-4', 'pb-5'];
        if (!in_array($this->properties['padding'], $allowedPadding)) {
            $this->properties['padding'] = '';
        }
    }

    /**
     * Get the component view name
     */
    protected function getViewName(): string
    {
        return 'components.builder.layout.row';
    }

    /**
     * Generate CSS classes based on properties
     */
    public function getCssClasses(): string
    {
        $classes = ['row'];

        // Add gutter classes
        if ($gutter = $this->getProperty('gutter')) {
            $classes[] = $gutter;
        }

        // Add alignment classes
        if ($alignItems = $this->getProperty('align_items')) {
            $classes[] = $alignItems;
        }

        if ($justifyContent = $this->getProperty('justify_content')) {
            $classes[] = $justifyContent;
        }

        // Add flex wrap
        if ($flexWrap = $this->getProperty('flex_wrap')) {
            $classes[] = $flexWrap;
        }

        // Add spacing
        if ($margin = $this->getProperty('margin')) {
            $classes[] = $margin;
        }

        if ($padding = $this->getProperty('padding')) {
            $classes[] = $padding;
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
            'gutter' => [
                'type' => 'select',
                'label' => 'Gutter Size',
                'description' => 'Space between columns in the row',
                'options' => [
                    '' => 'Default gutter',
                    'g-0' => 'No gutter',
                    'g-1' => 'Small gutter (0.25rem)',
                    'g-2' => 'Medium gutter (0.5rem)',
                    'g-3' => 'Default gutter (1rem)',
                    'g-4' => 'Large gutter (1.5rem)',
                    'g-5' => 'Extra large gutter (3rem)',
                    'gx-0' => 'No horizontal gutter',
                    'gx-1' => 'Small horizontal gutter',
                    'gx-2' => 'Medium horizontal gutter',
                    'gx-3' => 'Default horizontal gutter',
                    'gx-4' => 'Large horizontal gutter',
                    'gx-5' => 'Extra large horizontal gutter',
                    'gy-0' => 'No vertical gutter',
                    'gy-1' => 'Small vertical gutter',
                    'gy-2' => 'Medium vertical gutter',
                    'gy-3' => 'Default vertical gutter',
                    'gy-4' => 'Large vertical gutter',
                    'gy-5' => 'Extra large vertical gutter'
                ]
            ],
            'align_items' => [
                'type' => 'select',
                'label' => 'Vertical Alignment',
                'description' => 'How to align columns vertically in the row',
                'options' => [
                    '' => 'Default (stretch)',
                    'align-items-start' => 'Top',
                    'align-items-center' => 'Center',
                    'align-items-end' => 'Bottom',
                    'align-items-stretch' => 'Stretch to fill'
                ]
            ],
            'justify_content' => [
                'type' => 'select',
                'label' => 'Horizontal Alignment',
                'description' => 'How to align columns horizontally in the row',
                'options' => [
                    '' => 'Default (start)',
                    'justify-content-start' => 'Left',
                    'justify-content-center' => 'Center',
                    'justify-content-end' => 'Right',
                    'justify-content-between' => 'Space between',
                    'justify-content-around' => 'Space around',
                    'justify-content-evenly' => 'Space evenly'
                ]
            ],
            'flex_wrap' => [
                'type' => 'select',
                'label' => 'Flex Wrap',
                'description' => 'How columns should wrap when exceeding row width',
                'options' => [
                    '' => 'Default (wrap)',
                    'flex-wrap' => 'Allow wrapping',
                    'flex-nowrap' => 'No wrapping',
                    'flex-wrap-reverse' => 'Wrap in reverse'
                ]
            ],
            'margin' => [
                'type' => 'select',
                'label' => 'Margin',
                'description' => 'Add margin around the row',
                'options' => [
                    '' => 'No margin',
                    'm-1' => 'Small margin',
                    'm-2' => 'Medium margin',
                    'm-3' => 'Default margin',
                    'm-4' => 'Large margin',
                    'm-5' => 'Extra large margin',
                    'mt-3' => 'Top margin only',
                    'mb-3' => 'Bottom margin only'
                ]
            ],
            'padding' => [
                'type' => 'select',
                'label' => 'Padding',
                'description' => 'Add padding inside the row',
                'options' => [
                    '' => 'No padding',
                    'p-1' => 'Small padding',
                    'p-2' => 'Medium padding',
                    'p-3' => 'Default padding',
                    'p-4' => 'Large padding',
                    'p-5' => 'Extra large padding',
                    'pt-3' => 'Top padding only',
                    'pb-3' => 'Bottom padding only'
                ]
            ]
        ]);
    }
}
