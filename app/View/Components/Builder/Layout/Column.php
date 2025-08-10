<?php

namespace App\View\Components\Builder\Layout;

use App\View\Components\Builder\BaseComponent;

/**
 * Column Component
 * 
 * Bootstrap column component for creating responsive grid layouts.
 * Uses Tabler.io column classes with responsive breakpoints.
 */
class Column extends BaseComponent
{
    /**
     * Component metadata
     */
    protected array $meta = [
        'name' => 'Column',
        'category' => 'layout',
        'icon' => 'ti ti-layout-columns',
        'description' => 'Responsive column for grid layouts with breakpoint-specific sizing',
        'version' => '1.0.0'
    ];

    /**
     * Default CSS classes for this component
     */
    protected array $defaultClasses = ['col'];

    /**
     * Get default properties for the component
     */
    public function getDefaultProperties(): array
    {
        return [
            'xs_size' => 'auto',
            'sm_size' => '',
            'md_size' => '',
            'lg_size' => '',
            'xl_size' => '',
            'xxl_size' => '',
            'offset_xs' => '',
            'offset_sm' => '',
            'offset_md' => '',
            'offset_lg' => '',
            'offset_xl' => '',
            'offset_xxl' => '',
            'order' => '',
            'align_self' => '',
            'padding' => '',
            'margin' => '',
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
        // Validate column sizes (1-12 or auto)
        $allowedSizes = ['', 'auto', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'];
        
        foreach (['xs_size', 'sm_size', 'md_size', 'lg_size', 'xl_size', 'xxl_size'] as $property) {
            if (!in_array($this->properties[$property], $allowedSizes)) {
                $this->properties[$property] = '';
            }
        }

        // Validate offsets (0-11)
        $allowedOffsets = ['', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11'];
        
        foreach (['offset_xs', 'offset_sm', 'offset_md', 'offset_lg', 'offset_xl', 'offset_xxl'] as $property) {
            if (!in_array($this->properties[$property], $allowedOffsets)) {
                $this->properties[$property] = '';
            }
        }

        // Validate order
        $allowedOrder = ['', 'order-first', 'order-last', 'order-0', 'order-1', 'order-2', 'order-3', 'order-4', 'order-5'];
        if (!in_array($this->properties['order'], $allowedOrder)) {
            $this->properties['order'] = '';
        }

        // Validate align self
        $allowedAlign = ['', 'align-self-start', 'align-self-center', 'align-self-end', 'align-self-stretch'];
        if (!in_array($this->properties['align_self'], $allowedAlign)) {
            $this->properties['align_self'] = '';
        }

        // Validate padding
        $allowedPadding = ['', 'p-0', 'p-1', 'p-2', 'p-3', 'p-4', 'p-5'];
        if (!in_array($this->properties['padding'], $allowedPadding)) {
            $this->properties['padding'] = '';
        }

        // Validate margin
        $allowedMargin = ['', 'm-0', 'm-1', 'm-2', 'm-3', 'm-4', 'm-5'];
        if (!in_array($this->properties['margin'], $allowedMargin)) {
            $this->properties['margin'] = '';
        }
    }

    /**
     * Get the component view name
     */
    protected function getViewName(): string
    {
        return 'components.builder.layout.column';
    }

    /**
     * Generate CSS classes based on properties
     */
    public function getCssClasses(): string
    {
        $classes = [];

        // Build responsive column classes
        $breakpoints = [
            'xs' => '',
            'sm' => 'sm',
            'md' => 'md',
            'lg' => 'lg',
            'xl' => 'xl',
            'xxl' => 'xxl'
        ];

        $hasColumnClass = false;

        foreach ($breakpoints as $key => $breakpoint) {
            $size = $this->getProperty($key . '_size');
            
            if ($size !== '') {
                if ($breakpoint === '') {
                    // xs breakpoint
                    if ($size === 'auto') {
                        $classes[] = 'col-auto';
                    } else {
                        $classes[] = 'col-' . $size;
                    }
                } else {
                    // other breakpoints
                    if ($size === 'auto') {
                        $classes[] = 'col-' . $breakpoint . '-auto';
                    } else {
                        $classes[] = 'col-' . $breakpoint . '-' . $size;
                    }
                }
                $hasColumnClass = true;
            }
        }

        // If no specific column class, use default 'col'
        if (!$hasColumnClass) {
            $classes[] = 'col';
        }

        // Add offset classes
        foreach ($breakpoints as $key => $breakpoint) {
            $offset = $this->getProperty('offset_' . $key);
            
            if ($offset !== '') {
                if ($breakpoint === '') {
                    $classes[] = 'offset-' . $offset;
                } else {
                    $classes[] = 'offset-' . $breakpoint . '-' . $offset;
                }
            }
        }

        // Add order class
        if ($order = $this->getProperty('order')) {
            $classes[] = $order;
        }

        // Add align self
        if ($alignSelf = $this->getProperty('align_self')) {
            $classes[] = $alignSelf;
        }

        // Add spacing
        if ($padding = $this->getProperty('padding')) {
            $classes[] = $padding;
        }

        if ($margin = $this->getProperty('margin')) {
            $classes[] = $margin;
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
        $sizeOptions = [
            '' => 'Default',
            'auto' => 'Auto width',
            '1' => '1/12 (8.33%)',
            '2' => '2/12 (16.67%)',
            '3' => '3/12 (25%)',
            '4' => '4/12 (33.33%)',
            '5' => '5/12 (41.67%)',
            '6' => '6/12 (50%)',
            '7' => '7/12 (58.33%)',
            '8' => '8/12 (66.67%)',
            '9' => '9/12 (75%)',
            '10' => '10/12 (83.33%)',
            '11' => '11/12 (91.67%)',
            '12' => '12/12 (100%)'
        ];

        $offsetOptions = [
            '' => 'No offset',
            '0' => 'No offset',
            '1' => 'Offset 1 column',
            '2' => 'Offset 2 columns',
            '3' => 'Offset 3 columns',
            '4' => 'Offset 4 columns',
            '5' => 'Offset 5 columns',
            '6' => 'Offset 6 columns',
            '7' => 'Offset 7 columns',
            '8' => 'Offset 8 columns',
            '9' => 'Offset 9 columns',
            '10' => 'Offset 10 columns',
            '11' => 'Offset 11 columns'
        ];

        return array_merge(parent::getBuilderProperties(), [
            'xs_size' => [
                'type' => 'select',
                'label' => 'Extra Small (xs) Size',
                'description' => 'Column size on extra small screens (<576px)',
                'options' => $sizeOptions
            ],
            'sm_size' => [
                'type' => 'select',
                'label' => 'Small (sm) Size',
                'description' => 'Column size on small screens (≥576px)',
                'options' => $sizeOptions
            ],
            'md_size' => [
                'type' => 'select',
                'label' => 'Medium (md) Size',
                'description' => 'Column size on medium screens (≥768px)',
                'options' => $sizeOptions
            ],
            'lg_size' => [
                'type' => 'select',
                'label' => 'Large (lg) Size',
                'description' => 'Column size on large screens (≥992px)',
                'options' => $sizeOptions
            ],
            'xl_size' => [
                'type' => 'select',
                'label' => 'Extra Large (xl) Size',
                'description' => 'Column size on extra large screens (≥1200px)',
                'options' => $sizeOptions
            ],
            'xxl_size' => [
                'type' => 'select',
                'label' => 'Extra Extra Large (xxl) Size',
                'description' => 'Column size on extra extra large screens (≥1400px)',
                'options' => $sizeOptions
            ],
            'order' => [
                'type' => 'select',
                'label' => 'Column Order',
                'description' => 'Control the visual order of columns',
                'options' => [
                    '' => 'Default order',
                    'order-first' => 'First',
                    'order-last' => 'Last',
                    'order-0' => 'Order 0',
                    'order-1' => 'Order 1',
                    'order-2' => 'Order 2',
                    'order-3' => 'Order 3',
                    'order-4' => 'Order 4',
                    'order-5' => 'Order 5'
                ]
            ],
            'align_self' => [
                'type' => 'select',
                'label' => 'Self Alignment',
                'description' => 'Override the row\'s align-items value for this column',
                'options' => [
                    '' => 'Default',
                    'align-self-start' => 'Top',
                    'align-self-center' => 'Center',
                    'align-self-end' => 'Bottom',
                    'align-self-stretch' => 'Stretch'
                ]
            ],
            'padding' => [
                'type' => 'select',
                'label' => 'Padding',
                'description' => 'Add padding inside the column',
                'options' => [
                    '' => 'No padding',
                    'p-1' => 'Small padding',
                    'p-2' => 'Medium padding',
                    'p-3' => 'Default padding',
                    'p-4' => 'Large padding',
                    'p-5' => 'Extra large padding'
                ]
            ],
            'margin' => [
                'type' => 'select',
                'label' => 'Margin',
                'description' => 'Add margin around the column',
                'options' => [
                    '' => 'No margin',
                    'm-1' => 'Small margin',
                    'm-2' => 'Medium margin',
                    'm-3' => 'Default margin',
                    'm-4' => 'Large margin',
                    'm-5' => 'Extra large margin'
                ]
            ]
        ]);
    }
}
