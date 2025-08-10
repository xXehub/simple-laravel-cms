<?php

namespace App\View\Components\Builder;

use Illuminate\View\Component;

/**
 * Base class for all page builder components
 * 
 * This class provides common functionality for all builder components:
 * - Property management and validation
 * - CSS class generation
 * - Component metadata
 * - Tabler.io integration helpers
 */
abstract class BaseComponent extends Component
{
    /**
     * Component properties/attributes
     */
    protected array $properties = [];
    
    /**
     * Component metadata
     */
    protected array $meta = [
        'name' => '',
        'category' => '',
        'icon' => '',
        'description' => '',
        'version' => '1.0.0'
    ];
    
    /**
     * Default CSS classes for Tabler.io
     */
    protected array $defaultClasses = [];
    
    /**
     * Allowed CSS classes (Tabler.io only)
     */
    protected array $allowedClasses = [
        // Layout
        'container', 'container-fluid', 'row', 'col', 'col-auto', 'col-sm', 'col-md', 'col-lg', 'col-xl',
        // Spacing
        'm-0', 'm-1', 'm-2', 'm-3', 'm-4', 'm-5', 'mt-', 'mb-', 'ms-', 'me-', 'mx-', 'my-',
        'p-0', 'p-1', 'p-2', 'p-3', 'p-4', 'p-5', 'pt-', 'pb-', 'ps-', 'pe-', 'px-', 'py-',
        // Display
        'd-none', 'd-block', 'd-inline', 'd-inline-block', 'd-flex', 'd-grid',
        // Flexbox
        'justify-content-start', 'justify-content-center', 'justify-content-end', 'justify-content-between',
        'align-items-start', 'align-items-center', 'align-items-end', 'flex-wrap', 'flex-nowrap',
        // Text
        'text-start', 'text-center', 'text-end', 'text-primary', 'text-secondary', 'text-success',
        'text-danger', 'text-warning', 'text-info', 'text-light', 'text-dark', 'text-muted',
        // Background
        'bg-primary', 'bg-secondary', 'bg-success', 'bg-danger', 'bg-warning', 'bg-info',
        'bg-light', 'bg-dark', 'bg-white', 'bg-transparent',
        // Border
        'border', 'border-top', 'border-bottom', 'border-start', 'border-end', 'border-0',
        'rounded', 'rounded-top', 'rounded-bottom', 'rounded-start', 'rounded-end', 'rounded-0',
        // Components
        'card', 'card-header', 'card-body', 'card-footer', 'card-title', 'card-subtitle',
        'btn', 'btn-primary', 'btn-secondary', 'btn-success', 'btn-danger', 'btn-warning',
        'btn-info', 'btn-light', 'btn-dark', 'btn-outline-primary', 'btn-sm', 'btn-lg',
        'alert', 'alert-primary', 'alert-secondary', 'alert-success', 'alert-danger',
        'badge', 'badge-primary', 'badge-secondary', 'table', 'table-responsive'
    ];

    public function __construct(array $properties = [])
    {
        $this->properties = array_merge($this->getDefaultProperties(), $properties);
        $this->validateProperties();
    }

    /**
     * Get component metadata
     */
    public function getMeta(): array
    {
        return $this->meta;
    }

    /**
     * Get component metadata (alias for getMeta for backward compatibility)
     */
    public function getMetadata(): array
    {
        return $this->getMeta();
    }

    /**
     * Get component properties
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * Get a specific property value
     */
    public function getProperty(string $key, $default = null)
    {
        return $this->properties[$key] ?? $default;
    }

    /**
     * Set a property value
     */
    public function setProperty(string $key, $value): void
    {
        $this->properties[$key] = $value;
        $this->validateProperties();
    }

    /**
     * Set multiple properties at once
     */
    public function setProperties(array $properties): void
    {
        $this->properties = array_merge($this->properties, $properties);
        $this->validateProperties();
    }

    /**
     * Generate CSS classes for the component
     */
    public function getCssClasses(): string
    {
        $classes = array_merge($this->defaultClasses, $this->getProperty('css_classes', []));
        $classes = array_filter($classes, [$this, 'isAllowedClass']);
        return implode(' ', array_unique($classes));
    }

    /**
     * Check if a CSS class is allowed (Tabler.io only)
     */
    protected function isAllowedClass(string $class): bool
    {
        // Allow exact matches
        if (in_array($class, $this->allowedClasses)) {
            return true;
        }

        // Allow partial matches for responsive and spacing classes
        $patterns = [
            '/^(col|offset)-(sm|md|lg|xl|xxl)-\d+$/', // Bootstrap columns
            '/^(m|p)(t|b|s|e|x|y)?-[0-5]$/', // Spacing utilities
            '/^text-(sm|md|lg|xl|xxl)-(start|center|end)$/', // Responsive text alignment
            '/^d-(sm|md|lg|xl|xxl)-(none|block|inline|flex|grid)$/' // Responsive display
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $class)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Generate component HTML attributes
     */
    public function getAttributes(): array
    {
        $attributes = [];

        // Add ID if provided
        if ($id = $this->getProperty('id')) {
            $attributes['id'] = $id;
        }

        // Add CSS classes
        if ($classes = $this->getCssClasses()) {
            $attributes['class'] = $classes;
        }

        // Add data attributes
        foreach ($this->getProperty('data_attributes', []) as $key => $value) {
            $attributes["data-{$key}"] = $value;
        }

        return $attributes;
    }

    /**
     * Render attributes as HTML string
     */
    public function renderAttributes(): string
    {
        $attributes = $this->getAttributes();
        $html = [];

        foreach ($attributes as $key => $value) {
            $html[] = sprintf('%s="%s"', $key, htmlspecialchars($value));
        }

        return implode(' ', $html);
    }

    /**
     * Get property definitions for the builder UI
     */
    public function getPropertyDefinitions(): array
    {
        return $this->getBuilderProperties();
    }

    /**
     * Get validation rules for component properties
     */
    public function getValidationRules(): array
    {
        return [
            'id' => 'nullable|string|max:255',
            'css_classes' => 'nullable|array',
            'css_classes.*' => 'string',
            'data_attributes' => 'nullable|array'
        ];
    }

    /**
     * Get default properties for the component (abstract method)
     */
    abstract public function getDefaultProperties(): array;

    /**
     * Validate component properties
     */
    protected function validateProperties(): void
    {
        // Basic validation - override in child classes for specific validation
        $validator = \Validator::make($this->properties, $this->getValidationRules());
        
        if ($validator->fails()) {
            throw new \InvalidArgumentException('Invalid component properties: ' . $validator->errors()->first());
        }
    }

    /**
     * Get the view / contents of the component
     */
    public function render()
    {
        return view($this->getViewName(), [
            'properties' => $this->properties,
            'attributes' => $this->getAttributes(),
            'attributeString' => $this->renderAttributes()
        ]);
    }

    /**
     * Get the component view name
     */
    abstract protected function getViewName(): string;

    /**
     * Get component configuration for the builder UI
     */
    public function getBuilderConfig(): array
    {
        return [
            'meta' => $this->getMeta(),
            'properties' => $this->getBuilderProperties(),
            'preview' => $this->getBuilderPreview()
        ];
    }

    /**
     * Get property definitions for the builder UI
     */
    public function getBuilderProperties(): array
    {
        return [
            'id' => [
                'type' => 'text',
                'label' => 'Element ID',
                'description' => 'Unique identifier for this element'
            ],
            'css_classes' => [
                'type' => 'multiselect',
                'label' => 'CSS Classes',
                'description' => 'Tabler.io CSS classes',
                'options' => $this->allowedClasses
            ]
        ];
    }

    /**
     * Get preview HTML for the builder
     */
    protected function getBuilderPreview(): string
    {
        try {
            return $this->render()->render();
        } catch (\Exception $e) {
            // Return a fallback preview during registration
            return '<div class="card"><div class="card-body"><p>Preview not available during registration</p></div></div>';
        }
    }
}
