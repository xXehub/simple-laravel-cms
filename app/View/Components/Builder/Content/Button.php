<?php

namespace App\View\Components\Builder\Content;

use App\View\Components\Builder\BaseComponent;

/**
 * Button Component
 * 
 * Creates buttons with Tabler.io button classes and variants.
 * Supports different styles, sizes, icons, and link functionality.
 */
class Button extends BaseComponent
{
    /**
     * Component metadata
     */
    protected array $meta = [
        'name' => 'Button',
        'category' => 'content',
        'icon' => 'ti ti-square-rounded',
        'description' => 'Interactive buttons with various styles and options',
        'version' => '1.0.0'
    ];

    /**
     * Default CSS classes for this component
     */
    protected array $defaultClasses = ['btn'];

    /**
     * Get default properties for the component
     */
    public function getDefaultProperties(): array
    {
        return [
            'text' => 'Click Me',
            'url' => '#',
            'variant' => 'btn-primary',
            'size' => '',
            'target' => '_self',
            'icon' => '',
            'icon_position' => 'left',
            'full_width' => false,
            'disabled' => false,
            'outline' => false,
            'pill' => false,
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
        // Validate button variant
        $allowedVariants = [
            'btn-primary', 'btn-secondary', 'btn-success', 'btn-warning', 
            'btn-danger', 'btn-info', 'btn-light', 'btn-dark',
            'btn-outline-primary', 'btn-outline-secondary', 'btn-outline-success',
            'btn-outline-warning', 'btn-outline-danger', 'btn-outline-info',
            'btn-outline-light', 'btn-outline-dark'
        ];
        if (!in_array($this->properties['variant'], $allowedVariants)) {
            $this->properties['variant'] = 'btn-primary';
        }

        // Validate size
        $allowedSizes = ['', 'btn-sm', 'btn-lg'];
        if (!in_array($this->properties['size'], $allowedSizes)) {
            $this->properties['size'] = '';
        }

        // Validate target
        $allowedTargets = ['_self', '_blank', '_parent', '_top'];
        if (!in_array($this->properties['target'], $allowedTargets)) {
            $this->properties['target'] = '_self';
        }

        // Validate icon position
        $allowedPositions = ['left', 'right'];
        if (!in_array($this->properties['icon_position'], $allowedPositions)) {
            $this->properties['icon_position'] = 'left';
        }

        // Validate margin
        $allowedMargin = ['', 'm-0', 'm-1', 'm-2', 'm-3', 'm-4', 'm-5', 'me-2', 'me-3', 'mb-2', 'mb-3'];
        if (!in_array($this->properties['margin'], $allowedMargin)) {
            $this->properties['margin'] = '';
        }

        // Validate boolean properties
        $this->properties['full_width'] = (bool) $this->properties['full_width'];
        $this->properties['disabled'] = (bool) $this->properties['disabled'];
        $this->properties['outline'] = (bool) $this->properties['outline'];
        $this->properties['pill'] = (bool) $this->properties['pill'];

        // Sanitize text and URL
        $this->properties['text'] = htmlspecialchars($this->properties['text']);
        $this->properties['url'] = filter_var($this->properties['url'], FILTER_SANITIZE_URL);

        // Sanitize icon (allow only Tabler icon classes)
        if (!empty($this->properties['icon'])) {
            if (!str_starts_with($this->properties['icon'], 'ti ti-')) {
                $this->properties['icon'] = 'ti ti-' . ltrim($this->properties['icon'], 'ti-');
            }
        }

        // Handle outline variant conversion
        if ($this->properties['outline'] && !str_contains($this->properties['variant'], 'outline')) {
            $baseColor = str_replace('btn-', '', $this->properties['variant']);
            $this->properties['variant'] = 'btn-outline-' . $baseColor;
        } elseif (!$this->properties['outline'] && str_contains($this->properties['variant'], 'outline')) {
            $baseColor = str_replace('btn-outline-', '', $this->properties['variant']);
            $this->properties['variant'] = 'btn-' . $baseColor;
        }
    }

    /**
     * Get the component view name
     */
    protected function getViewName(): string
    {
        return 'components.builder.content.button';
    }

    /**
     * Generate CSS classes based on properties
     */
    public function getCssClasses(): string
    {
        $classes = ['btn'];

        // Add variant
        $classes[] = $this->getProperty('variant', 'btn-primary');

        // Add size
        if ($size = $this->getProperty('size')) {
            $classes[] = $size;
        }

        // Add full width
        if ($this->getProperty('full_width', false)) {
            $classes[] = 'w-100';
        }

        // Add pill style
        if ($this->getProperty('pill', false)) {
            $classes[] = 'rounded-pill';
        }

        // Add margin
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
     * Get button attributes
     */
    public function getButtonAttributes(): array
    {
        $attributes = $this->getAttributes();

        // Add disabled state
        if ($this->getProperty('disabled', false)) {
            $attributes['disabled'] = 'disabled';
            $attributes['aria-disabled'] = 'true';
        }

        // Add href for links
        if (!$this->getProperty('disabled', false) && $this->getProperty('url') !== '#') {
            $attributes['href'] = $this->getProperty('url');
            $attributes['target'] = $this->getProperty('target', '_self');
        }

        return $attributes;
    }

    /**
     * Determine if this should be a link or button element
     */
    public function isLink(): bool
    {
        return !$this->getProperty('disabled', false) && 
               !empty($this->getProperty('url')) && 
               $this->getProperty('url') !== '#';
    }

    /**
     * Get property definitions for the builder UI
     */
    public function getBuilderProperties(): array
    {
        return array_merge(parent::getBuilderProperties(), [
            'text' => [
                'type' => 'text',
                'label' => 'Button Text',
                'description' => 'The text displayed on the button',
                'required' => true,
                'placeholder' => 'Enter button text...'
            ],
            'url' => [
                'type' => 'url',
                'label' => 'Button URL',
                'description' => 'The URL the button links to (use # for no link)',
                'placeholder' => 'https://example.com'
            ],
            'variant' => [
                'type' => 'select',
                'label' => 'Button Style',
                'description' => 'Choose the button color and style',
                'options' => [
                    'btn-primary' => 'Primary',
                    'btn-secondary' => 'Secondary',
                    'btn-success' => 'Success',
                    'btn-warning' => 'Warning',
                    'btn-danger' => 'Danger',
                    'btn-info' => 'Info',
                    'btn-light' => 'Light',
                    'btn-dark' => 'Dark'
                ]
            ],
            'outline' => [
                'type' => 'checkbox',
                'label' => 'Outline Style',
                'description' => 'Use outline button style instead of filled'
            ],
            'size' => [
                'type' => 'select',
                'label' => 'Button Size',
                'description' => 'Choose the button size',
                'options' => [
                    '' => 'Default',
                    'btn-sm' => 'Small',
                    'btn-lg' => 'Large'
                ]
            ],
            'target' => [
                'type' => 'select',
                'label' => 'Link Target',
                'description' => 'How the link should open',
                'options' => [
                    '_self' => 'Same window',
                    '_blank' => 'New window/tab',
                    '_parent' => 'Parent frame',
                    '_top' => 'Top frame'
                ]
            ],
            'icon' => [
                'type' => 'text',
                'label' => 'Icon (Optional)',
                'description' => 'Tabler icon name (e.g., "arrow-right", "download")',
                'placeholder' => 'arrow-right'
            ],
            'icon_position' => [
                'type' => 'select',
                'label' => 'Icon Position',
                'description' => 'Position of the icon relative to text',
                'options' => [
                    'left' => 'Left of text',
                    'right' => 'Right of text'
                ]
            ],
            'full_width' => [
                'type' => 'checkbox',
                'label' => 'Full Width',
                'description' => 'Make the button span the full width of its container'
            ],
            'pill' => [
                'type' => 'checkbox',
                'label' => 'Pill Style',
                'description' => 'Use rounded pill style'
            ],
            'disabled' => [
                'type' => 'checkbox',
                'label' => 'Disabled',
                'description' => 'Disable the button interaction'
            ],
            'margin' => [
                'type' => 'select',
                'label' => 'Margin',
                'description' => 'Add margin around the button',
                'options' => [
                    '' => 'No margin',
                    'me-2' => 'Right margin (small)',
                    'me-3' => 'Right margin (medium)',
                    'mb-2' => 'Bottom margin (small)',
                    'mb-3' => 'Bottom margin (medium)',
                    'm-2' => 'All sides margin (small)',
                    'm-3' => 'All sides margin (medium)'
                ]
            ]
        ]);
    }
}
