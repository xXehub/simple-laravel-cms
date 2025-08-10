<?php

namespace App\View\Components\Builder\Custom;

use App\View\Components\Builder\BaseComponent;

/**
 * Vanilla Component Template
 * 
 * Copy this file to create new custom components.
 * Replace "VanillaComponent" with your component name.
 */
class VanillaComponent extends BaseComponent
{
    /**
     * Component metadata - CUSTOMIZE THIS
     */
    protected array $meta = [
        'name' => 'Vanilla Component',
        'category' => 'custom',
        'icon' => 'ti ti-puzzle',
        'description' => 'A vanilla template for creating custom components',
        'version' => '1.0.0'
    ];

    /**
     * Default CSS classes for this component - CUSTOMIZE THIS
     */
    protected array $defaultClasses = [];

    /**
     * Get default properties for the component - CUSTOMIZE THIS
     */
    public function getDefaultProperties(): array
    {
        return [
            // Add your custom properties here
            'title' => '',
            'content' => '',
            
            // Standard properties (keep these)
            'css_classes' => [],
            'id' => '',
            'data_attributes' => []
        ];
    }

    /**
     * Validate component properties - CUSTOMIZE THIS
     */
    protected function validateProperties(): void
    {
        // Add validation logic here
        // Example:
        // if (empty($this->properties['title'])) {
        //     throw new \InvalidArgumentException('Title is required');
        // }
    }

    /**
     * Get the component view name - CUSTOMIZE THIS
     */
    protected function getViewName(): string
    {
        return 'components.builder.custom.vanilla-component';
    }

    /**
     * Get property definitions for the builder UI - CUSTOMIZE THIS
     */
    public function getBuilderProperties(): array
    {
        return array_merge(parent::getBuilderProperties(), [
            // Add your property definitions here
            'title' => [
                'type' => 'text',
                'label' => 'Title',
                'description' => 'The component title',
                'required' => false
            ],
            'content' => [
                'type' => 'textarea',
                'label' => 'Content',
                'description' => 'The component content',
                'rows' => 3
            ],
        ]);
    }
}
