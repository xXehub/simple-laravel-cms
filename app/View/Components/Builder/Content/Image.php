<?php

namespace App\View\Components\Builder\Content;

use App\View\Components\Builder\BaseComponent;

/**
 * Image Component
 * 
 * Displays images with Tabler.io styling and responsive options.
 * Supports various sizing, alignment, and styling options.
 */
class Image extends BaseComponent
{
    /**
     * Component metadata
     */
    protected array $meta = [
        'name' => 'Image',
        'category' => 'content',
        'icon' => 'ti ti-photo',
        'description' => 'Responsive images with various styling options',
        'version' => '1.0.0'
    ];

    /**
     * Default CSS classes for this component
     */
    protected array $defaultClasses = ['img-fluid'];

    /**
     * Get default properties for the component
     */
    public function getDefaultProperties(): array
    {
        return [
            'src' => 'https://via.placeholder.com/400x300/667684/ffffff?text=Image',
            'alt' => 'Image',
            'width' => '',
            'height' => '',
            'alignment' => 'left',
            'rounded' => false,
            'rounded_circle' => false,
            'thumbnail' => false,
            'responsive' => true,
            'lazy_loading' => true,
            'link_url' => '',
            'link_target' => '_self',
            'caption' => '',
            'caption_position' => 'below',
            'margin' => '',
            'css_classes' => [],
            'id' => '',
        ];
    }

    /**
     * Get property definitions for builder UI
     */
    public function getPropertyDefinitions(): array
    {
        return [
            'src' => [
                'type' => 'image',
                'label' => 'Image Source',
                'description' => 'URL or path to the image',
                'required' => true,
                'validation' => 'required|string|max:2048'
            ],
            'alt' => [
                'type' => 'text',
                'label' => 'Alt Text',
                'description' => 'Alternative text for accessibility',
                'required' => true,
                'validation' => 'required|string|max:255'
            ],
            'width' => [
                'type' => 'number',
                'label' => 'Width (px)',
                'description' => 'Fixed width in pixels (optional)',
                'min' => 1,
                'max' => 2000,
                'validation' => 'nullable|integer|min:1|max:2000'
            ],
            'height' => [
                'type' => 'number',
                'label' => 'Height (px)',
                'description' => 'Fixed height in pixels (optional)',
                'min' => 1,
                'max' => 2000,
                'validation' => 'nullable|integer|min:1|max:2000'
            ],
            'alignment' => [
                'type' => 'select',
                'label' => 'Alignment',
                'options' => [
                    'left' => 'Left',
                    'center' => 'Center',
                    'right' => 'Right'
                ],
                'validation' => 'required|string|in:left,center,right'
            ],
            'rounded' => [
                'type' => 'boolean',
                'label' => 'Rounded Corners',
                'description' => 'Add rounded corners to the image',
                'validation' => 'boolean'
            ],
            'rounded_circle' => [
                'type' => 'boolean',
                'label' => 'Circular Image',
                'description' => 'Make the image circular',
                'validation' => 'boolean'
            ],
            'thumbnail' => [
                'type' => 'boolean',
                'label' => 'Thumbnail Style',
                'description' => 'Add thumbnail border styling',
                'validation' => 'boolean'
            ],
            'responsive' => [
                'type' => 'boolean',
                'label' => 'Responsive',
                'description' => 'Make image responsive (recommended)',
                'validation' => 'boolean'
            ],
            'lazy_loading' => [
                'type' => 'boolean',
                'label' => 'Lazy Loading',
                'description' => 'Enable lazy loading for better performance',
                'validation' => 'boolean'
            ],
            'link_url' => [
                'type' => 'url',
                'label' => 'Link URL',
                'description' => 'Optional link when image is clicked',
                'validation' => 'nullable|string|max:2048'
            ],
            'link_target' => [
                'type' => 'select',
                'label' => 'Link Target',
                'options' => [
                    '_self' => 'Same Window',
                    '_blank' => 'New Window'
                ],
                'validation' => 'required|string|in:_self,_blank',
                'depends_on' => 'link_url'
            ],
            'caption' => [
                'type' => 'textarea',
                'label' => 'Caption',
                'description' => 'Optional image caption',
                'rows' => 2,
                'validation' => 'nullable|string|max:500'
            ],
            'caption_position' => [
                'type' => 'select',
                'label' => 'Caption Position',
                'options' => [
                    'below' => 'Below Image',
                    'above' => 'Above Image',
                    'overlay' => 'Overlay'
                ],
                'validation' => 'required|string|in:below,above,overlay',
                'depends_on' => 'caption'
            ],
            'margin' => [
                'type' => 'select',
                'label' => 'Margin',
                'options' => [
                    '' => 'None',
                    'm-1' => 'Small',
                    'm-2' => 'Medium',
                    'm-3' => 'Large',
                    'my-1' => 'Vertical Small',
                    'my-2' => 'Vertical Medium',
                    'my-3' => 'Vertical Large',
                    'mb-1' => 'Bottom Small',
                    'mb-2' => 'Bottom Medium',
                    'mb-3' => 'Bottom Large'
                ],
                'validation' => 'nullable|string'
            ],
            'css_classes' => [
                'type' => 'text',
                'label' => 'Additional CSS Classes',
                'description' => 'Additional Tabler utility classes',
                'validation' => 'nullable|string|max:500'
            ],
            'id' => [
                'type' => 'text',
                'label' => 'HTML ID',
                'description' => 'Unique identifier for the image element',
                'validation' => 'nullable|string|max:100'
            ]
        ];
    }

    /**
     * Get validation rules for properties
     */
    public function getValidationRules(): array
    {
        return [
            'src' => 'required|string|max:2048',
            'alt' => 'required|string|max:255',
            'width' => 'nullable|integer|min:1|max:2000',
            'height' => 'nullable|integer|min:1|max:2000',
            'alignment' => 'required|string|in:left,center,right',
            'rounded' => 'boolean',
            'rounded_circle' => 'boolean',
            'thumbnail' => 'boolean',
            'responsive' => 'boolean',
            'lazy_loading' => 'boolean',
            'link_url' => 'nullable|string|max:2048',
            'link_target' => 'required|string|in:_self,_blank',
            'caption' => 'nullable|string|max:500',
            'caption_position' => 'required|string|in:below,above,overlay',
            'margin' => 'nullable|string|max:50',
            'css_classes' => 'nullable|string|max:500',
            'id' => 'nullable|string|max:100'
        ];
    }

    /**
     * Generate image CSS classes
     */
    public function getImageClasses(): string
    {
        $classes = [];
        
        // Add responsive class if enabled
        if ($this->getProperty('responsive', true)) {
            $classes[] = 'img-fluid';
        }
        
        // Add rounded styles
        if ($this->getProperty('rounded_circle', false)) {
            $classes[] = 'rounded-circle';
        } elseif ($this->getProperty('rounded', false)) {
            $classes[] = 'rounded';
        }
        
        // Add thumbnail style
        if ($this->getProperty('thumbnail', false)) {
            $classes[] = 'img-thumbnail';
        }
        
        // Add margin
        $margin = $this->getProperty('margin', '');
        if (!empty($margin)) {
            $classes[] = $margin;
        }
        
        // Add custom classes
        $customClasses = $this->getProperty('css_classes', []);
        if (!empty($customClasses)) {
            if (is_array($customClasses)) {
                $classes = array_merge($classes, $customClasses);
            } else {
                $classes[] = $customClasses;
            }
        }
        
        return implode(' ', array_filter($classes));
    }

    /**
     * Get container CSS classes for alignment
     */
    public function getContainerClasses(): string
    {
        $alignment = $this->getProperty('alignment', 'left');
        
        switch ($alignment) {
            case 'center':
                return 'text-center';
            case 'right':
                return 'text-end';
            default:
                return 'text-start';
        }
    }

    /**
     * Check if image has a link
     */
    public function hasLink(): bool
    {
        return !empty($this->getProperty('link_url', ''));
    }

    /**
     * Check if image has a caption
     */
    public function hasCaption(): bool
    {
        return !empty($this->getProperty('caption', ''));
    }

    /**
     * Get image style attribute for fixed dimensions
     */
    public function getImageStyle(): string
    {
        $styles = [];
        
        $width = $this->getProperty('width', '');
        $height = $this->getProperty('height', '');
        
        if (!empty($width)) {
            $styles[] = "width: {$width}px";
        }
        
        if (!empty($height)) {
            $styles[] = "height: {$height}px";
            $styles[] = "object-fit: cover"; // Maintain aspect ratio
        }
        
        return !empty($styles) ? implode('; ', $styles) : '';
    }

    /**
     * Get the view name for this component
     */
    protected function getViewName(): string
    {
        return 'components.builder.content.image';
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view($this->getViewName());
    }
}
