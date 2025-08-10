<?php

namespace App\Services;

use App\View\Components\Builder\BaseComponent;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Component Registry for the Page Builder
 * 
 * Manages registration, discovery, and metadata of all builder components.
 * Supports both built-in and custom components.
 */
class ComponentRegistry
{
    /**
     * Registered components
     */
    protected Collection $components;

    /**
     * Component categories
     */
    protected array $categories = [
        'layout' => [
            'name' => 'Layout',
            'icon' => 'ti ti-layout',
            'description' => 'Basic layout components like containers, rows, and columns'
        ],
        'content' => [
            'name' => 'Content',
            'icon' => 'ti ti-file-text',
            'description' => 'Text and media content components'
        ],
        'cards' => [
            'name' => 'Cards',
            'icon' => 'ti ti-cards',
            'description' => 'Card-based components for organizing content'
        ],
        'advanced' => [
            'name' => 'Advanced',
            'icon' => 'ti ti-code',
            'description' => 'Advanced components and widgets'
        ],
        'templates' => [
            'name' => 'Templates',
            'icon' => 'ti ti-template',
            'description' => 'Pre-built section templates'
        ],
        'custom' => [
            'name' => 'Custom',
            'icon' => 'ti ti-puzzle',
            'description' => 'Custom user-defined components'
        ]
    ];

    public function __construct()
    {
        $this->components = collect();
        $this->loadComponents();
    }

    /**
     * Load all components from configuration
     */
    protected function loadComponents(): void
    {
        $components = config('builder-components.components', []);

        foreach ($components as $key => $config) {
            try {
                $this->register($key, $config);
            } catch (\Exception $e) {
                \Log::warning("Failed to register component {$key}: " . $e->getMessage());
            }
        }

        // Auto-discover custom components
        $this->discoverCustomComponents();
    }

    /**
     * Register a component
     */
    public function register(string $key, array $config): void
    {
        // Validate configuration
        if (!isset($config['class']) || !class_exists($config['class'])) {
            throw new \InvalidArgumentException("Component class {$config['class']} not found for {$key}");
        }

        if (!is_subclass_of($config['class'], BaseComponent::class)) {
            throw new \InvalidArgumentException("Component {$key} must extend BaseComponent");
        }

        // Create component instance to get metadata
        $instance = new $config['class']();
        $meta = $instance->getMeta();

        $this->components->put($key, [
            'key' => $key,
            'class' => $config['class'],
            'enabled' => $config['enabled'] ?? true,
            'custom' => $config['custom'] ?? false,
            'meta' => $meta,
            'config' => $instance->getBuilderConfig()
        ]);
    }

    /**
     * Get all registered components
     */
    public function all(): Collection
    {
        return $this->components->where('enabled', true);
    }

    /**
     * Get components by category
     */
    public function getByCategory(string $category): Collection
    {
        return $this->all()->where('meta.category', $category);
    }

    /**
     * Get component by key
     */
    public function get(string $key): ?array
    {
        return $this->components->get($key);
    }

    /**
     * Check if component exists
     */
    public function has(string $key): bool
    {
        return $this->components->has($key);
    }

    /**
     * Create component instance
     */
    public function make(string $key, array $properties = []): ?BaseComponent
    {
        $component = $this->get($key);
        
        if (!$component) {
            return null;
        }

        return new $component['class']($properties);
    }

    /**
     * Get all categories with their components
     */
    public function getCategorizedComponents(): array
    {
        $categorized = [];

        foreach ($this->categories as $categoryKey => $categoryInfo) {
            $components = $this->getByCategory($categoryKey);
            
            if ($components->isNotEmpty()) {
                $categorized[$categoryKey] = [
                    'info' => $categoryInfo,
                    'components' => $components->values()->all()
                ];
            }
        }

        return $categorized;
    }

    /**
     * Get component metadata for builder UI
     */
    public function getBuilderData(): array
    {
        return [
            'categories' => $this->categories,
            'components' => $this->getCategorizedComponents()
        ];
    }

    /**
     * Auto-discover custom components
     */
    protected function discoverCustomComponents(): void
    {
        $customPath = app_path('View/Components/Builder/Custom');
        
        if (!is_dir($customPath)) {
            return;
        }

        $files = glob($customPath . '/*.php');
        
        foreach ($files as $file) {
            $className = pathinfo($file, PATHINFO_FILENAME);
            $fullClassName = "App\\View\\Components\\Builder\\Custom\\{$className}";
            
            if (class_exists($fullClassName) && 
                is_subclass_of($fullClassName, BaseComponent::class) &&
                !$this->has("custom.{$className}")) {
                
                try {
                    $this->register("custom.{$className}", [
                        'class' => $fullClassName,
                        'enabled' => true,
                        'custom' => true
                    ]);
                } catch (\Exception $e) {
                    // Skip invalid custom components
                    continue;
                }
            }
        }
    }

    /**
     * Search components by name or description  
     */
    public function searchComponents(string $query, ?string $category = null): Collection
    {
        $components = $category ? $this->getByCategory($category) : $this->all();
        
        if (empty($query)) {
            return $components;
        }
        
        return $components->filter(function ($component) use ($query) {
            $name = strtolower($component['meta']['name'] ?? '');
            $description = strtolower($component['meta']['description'] ?? '');
            $searchTerm = strtolower($query);
            
            return str_contains($name, $searchTerm) || str_contains($description, $searchTerm);
        });
    }

    /**
     * Get all available categories
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * Get all components
     */
    public function getAllComponents(): Collection
    {
        return $this->all();
    }

    /**
     * Get components by category
     */
    public function getComponentsByCategory(string $category): Collection
    {
        return $this->getByCategory($category);
    }

    /**
     * Get component by key
     */
    public function getComponent(string $key): ?array
    {
        return $this->get($key);
    }

    /**
     * Get statistics about registered components
     */
    public function getStats(): array
    {
        $total = $this->components->count();
        $enabled = $this->all()->count();
        $disabled = $total - $enabled;
        $categories = $this->all()->groupBy('meta.category')->keys()->count();

        return [
            'total' => $total,
            'enabled' => $enabled,
            'disabled' => $disabled,
            'categories' => $categories
        ];
    }
}