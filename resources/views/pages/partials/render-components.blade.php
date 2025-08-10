{{-- Partial view untuk render components secara aman --}}
@if(isset($components) && is_array($components))
    @foreach($components as $component)
        @if(isset($component['component_id']))
            @php
                // Render individual component safely
                try {
                    $componentId = $component['component_id'];
                    $properties = $component['properties'] ?? [];
                    
                    // Get component registry
                    $registry = app(\App\Services\ComponentRegistry::class);
                    $componentInfo = $registry->getComponent($componentId);
                    
                    if ($componentInfo) {
                        $componentClass = $componentInfo['class'];
                        
                        // Create component with properties
                        $componentInstance = new $componentClass($properties);
                        
                        // Render the component directly
                        echo $componentInstance->render()->render();
                    } else {
                        echo "<!-- Component not found: {$componentId} -->";
                    }
                } catch (\Exception $e) {
                    echo "<!-- Error rendering component: " . $e->getMessage() . " -->";
                }
            @endphp
        @endif
    @endforeach
@else
    <!-- No components to render -->
@endif
