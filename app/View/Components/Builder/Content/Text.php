<?php

namespace App\View\Components\Builder\Content;

use App\View\Components\Builder\BaseComponent;

/**
 * Text Component
 * 
 * Creates text content with Tabler.io typography options.
 * Supports rich formatting, colors, alignment, and sizing.
 */
class Text extends BaseComponent
{
    /**
     * Component metadata
     */
    protected array $meta = [
        'name' => 'Text',
        'category' => 'content',
        'icon' => 'ti ti-typography',
        'description' => 'Rich text content with typography and formatting options',
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
            'content' => 'Your text content goes here. You can add multiple paragraphs and basic formatting.',
            'color' => '',
            'text_align' => '',
            'font_size' => '',
            'font_weight' => '',
            'line_height' => '',
            'font_style' => '',
            'text_decoration' => '',
            'margin_top' => '',
            'margin_bottom' => 'mb-3',
            'allow_html' => false,
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
        // Validate color
        $allowedColors = ['', 'text-primary', 'text-secondary', 'text-success', 'text-warning', 'text-danger', 'text-info', 'text-light', 'text-dark', 'text-muted', 'text-white'];
        if (!in_array($this->properties['color'], $allowedColors)) {
            $this->properties['color'] = '';
        }

        // Validate text alignment
        $allowedAlign = ['', 'text-start', 'text-center', 'text-end', 'text-justify'];
        if (!in_array($this->properties['text_align'], $allowedAlign)) {
            $this->properties['text_align'] = '';
        }

        // Validate font size
        $allowedSize = ['', 'fs-1', 'fs-2', 'fs-3', 'fs-4', 'fs-5', 'fs-6'];
        if (!in_array($this->properties['font_size'], $allowedSize)) {
            $this->properties['font_size'] = '';
        }

        // Validate font weight
        $allowedWeight = ['', 'fw-light', 'fw-normal', 'fw-medium', 'fw-semibold', 'fw-bold'];
        if (!in_array($this->properties['font_weight'], $allowedWeight)) {
            $this->properties['font_weight'] = '';
        }

        // Validate line height
        $allowedLineHeight = ['', 'lh-1', 'lh-sm', 'lh-base', 'lh-lg'];
        if (!in_array($this->properties['line_height'], $allowedLineHeight)) {
            $this->properties['line_height'] = '';
        }

        // Validate font style
        $allowedStyle = ['', 'fst-normal', 'fst-italic'];
        if (!in_array($this->properties['font_style'], $allowedStyle)) {
            $this->properties['font_style'] = '';
        }

        // Validate text decoration
        $allowedDecoration = ['', 'text-decoration-none', 'text-decoration-underline', 'text-decoration-line-through'];
        if (!in_array($this->properties['text_decoration'], $allowedDecoration)) {
            $this->properties['text_decoration'] = '';
        }

        // Validate margins
        $allowedMargin = ['', 'm-0', 'm-1', 'm-2', 'm-3', 'm-4', 'm-5', 'mt-0', 'mt-1', 'mt-2', 'mt-3', 'mt-4', 'mt-5', 'mb-0', 'mb-1', 'mb-2', 'mb-3', 'mb-4', 'mb-5'];
        
        if (!in_array($this->properties['margin_top'], $allowedMargin)) {
            $this->properties['margin_top'] = '';
        }
        
        if (!in_array($this->properties['margin_bottom'], $allowedMargin)) {
            $this->properties['margin_bottom'] = 'mb-3';
        }

        // Validate allow_html
        $this->properties['allow_html'] = (bool) $this->properties['allow_html'];

        // Sanitize content based on HTML permission
        if ($this->properties['allow_html']) {
            // Allow basic HTML tags
            $allowedTags = '<p><br><strong><em><b><i><u><a><ul><ol><li><h1><h2><h3><h4><h5><h6><blockquote><code><pre>';
            $this->properties['content'] = strip_tags($this->properties['content'], $allowedTags);
        } else {
            // Escape all HTML
            $this->properties['content'] = htmlspecialchars($this->properties['content']);
        }
    }

    /**
     * Get the component view name
     */
    protected function getViewName(): string
    {
        return 'components.builder.content.text';
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

        // Add font size
        if ($fontSize = $this->getProperty('font_size')) {
            $classes[] = $fontSize;
        }

        // Add font weight
        if ($fontWeight = $this->getProperty('font_weight')) {
            $classes[] = $fontWeight;
        }

        // Add line height
        if ($lineHeight = $this->getProperty('line_height')) {
            $classes[] = $lineHeight;
        }

        // Add font style
        if ($fontStyle = $this->getProperty('font_style')) {
            $classes[] = $fontStyle;
        }

        // Add text decoration
        if ($textDecoration = $this->getProperty('text_decoration')) {
            $classes[] = $textDecoration;
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
     * Get formatted content for display
     */
    public function getFormattedContent(): string
    {
        $content = $this->getProperty('content', '');
        
        if ($this->getProperty('allow_html', false)) {
            // Return HTML content as-is (already sanitized in validation)
            return $content;
        } else {
            // Convert line breaks to <br> tags for plain text
            return nl2br($content);
        }
    }

    /**
     * Get property definitions for the builder UI
     */
    public function getBuilderProperties(): array
    {
        return array_merge(parent::getBuilderProperties(), [
            'content' => [
                'type' => 'textarea',
                'label' => 'Text Content',
                'description' => 'The text content to display',
                'required' => true,
                'rows' => 4,
                'placeholder' => 'Enter your text content...'
            ],
            'allow_html' => [
                'type' => 'checkbox',
                'label' => 'Allow HTML',
                'description' => 'Allow basic HTML tags in the content (p, br, strong, em, a, etc.)'
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
                    'text-end' => 'Right',
                    'text-justify' => 'Justify'
                ]
            ],
            'font_size' => [
                'type' => 'select',
                'label' => 'Font Size',
                'description' => 'Set the font size',
                'options' => [
                    '' => 'Default size',
                    'fs-1' => 'Extra Large',
                    'fs-2' => 'Large',
                    'fs-3' => 'Medium Large',
                    'fs-4' => 'Medium',
                    'fs-5' => 'Small',
                    'fs-6' => 'Extra Small'
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
            'line_height' => [
                'type' => 'select',
                'label' => 'Line Height',
                'description' => 'Set the line height for better readability',
                'options' => [
                    '' => 'Default',
                    'lh-1' => 'Tight (1)',
                    'lh-sm' => 'Small (1.25)',
                    'lh-base' => 'Base (1.5)',
                    'lh-lg' => 'Large (2)'
                ]
            ],
            'font_style' => [
                'type' => 'select',
                'label' => 'Font Style',
                'description' => 'Set the font style',
                'options' => [
                    '' => 'Default',
                    'fst-normal' => 'Normal',
                    'fst-italic' => 'Italic'
                ]
            ],
            'text_decoration' => [
                'type' => 'select',
                'label' => 'Text Decoration',
                'description' => 'Add text decoration',
                'options' => [
                    '' => 'Default',
                    'text-decoration-none' => 'None',
                    'text-decoration-underline' => 'Underline',
                    'text-decoration-line-through' => 'Strike Through'
                ]
            ],
            'margin_top' => [
                'type' => 'select',
                'label' => 'Top Margin',
                'description' => 'Add margin above the text',
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
                'description' => 'Add margin below the text',
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
