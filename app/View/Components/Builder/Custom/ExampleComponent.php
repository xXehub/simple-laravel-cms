<?php

namespace App\View\Components\Builder\Custom;

use App\View\Components\Builder\BaseComponent;

/**
 * Example Custom Component
 * 
 * This is a template for creating custom page builder components.
 * Copy this file and modify it to create your own custom components.
 */
class ExampleComponent extends BaseComponent
{
    /**
     * Component metadata
     */
    protected array $meta = [
        'name' => 'Example Component',
        'category' => 'custom',
        'icon' => 'ti ti-puzzle',
        'description' => 'An example custom component for demonstration',
        'version' => '1.0.0'
    ];

    /**
     * Default CSS classes for this component
     */
    protected array $defaultClasses = ['custom-example-component'];

    /**
     * Get default properties for the component
     */
    public function getDefaultProperties(): array
    {
        return [
            'title' => 'Example Title',
            'content' => 'This is example content for the custom component.',
            'show_border' => true,
            'color_scheme' => 'primary',
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
        // Validate title
        if (empty($this->properties['title'])) {
            throw new \InvalidArgumentException('Title is required');
        }

        // Validate color scheme
        $allowedColors = ['primary', 'secondary', 'success', 'warning', 'danger', 'info'];
        if (!in_array($this->properties['color_scheme'], $allowedColors)) {
            $this->properties['color_scheme'] = 'primary';
        }

        // Ensure show_border is boolean
        $this->properties['show_border'] = (bool) $this->properties['show_border'];
    }

    /**
     * Get the component view name
     */
    protected function getViewName(): string
    {
        return 'components.builder.custom.example-component';
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
                'description' => 'The title text for the component',
                'required' => true
            ],
            'content' => [
                'type' => 'textarea',
                'label' => 'Content',
                'description' => 'The main content text',
                'rows' => 3
            ],
            'show_border' => [
                'type' => 'checkbox',
                'label' => 'Show Border',
                'description' => 'Display a border around the component'
            ],
            'color_scheme' => [
                'type' => 'select',
                'label' => 'Color Scheme',
                'description' => 'Choose the color scheme for the component',
                'options' => [
                    'primary' => 'Primary',
                    'secondary' => 'Secondary',
                    'success' => 'Success',
                    'warning' => 'Warning',
                    'danger' => 'Danger',
                    'info' => 'Info'
                ]
            ]
        ]);
    }

    /**
     * Get additional CSS classes based on properties
     */
    public function getCssClasses(): string
    {
        $classes = parent::getCssClasses();
        
        // Add color scheme class
        $colorClass = 'text-' . $this->getProperty('color_scheme', 'primary');
        $classes .= ' ' . $colorClass;
        
        // Add border class if enabled
        if ($this->getProperty('show_border', false)) {
            $classes .= ' border rounded';
        }
        
        return trim($classes);
    }
}
