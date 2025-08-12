<?php

namespace App\Services;

use ReflectionClass;
use ReflectionMethod;
use Illuminate\Support\Facades\Log;

class SimpleControllerScanner
{
    /**
     * Methods yang tidak boleh di-route (excluded)
     */
    protected array $excludedMethods = [
        '__construct',
        '__call',
        '__get',
        '__set',
        '__destruct',
        'middleware',
        'authorize',
        'validate',
        'callAction',
        'getMiddleware',
        'dispatch',
        '__callStatic',
        'authorizeForUser',
        'authorizeResource',
        'validateWith',
        'validateWithBag',
        'dispatchNow',
        'dispatchSync'
    ];

    /**
     * Scan controller class dan return array method dengan info RESTful
     */
    public function scanController(string $controllerClass): array
    {
        try {
            if (!class_exists($controllerClass)) {
                Log::warning("Controller class not found: {$controllerClass}");
                return [];
            }

            $reflection = new ReflectionClass($controllerClass);
            $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);

            $routes = [];
            foreach ($methods as $method) {
                $methodName = $method->getName();

                if ($this->isRoutableMethod($methodName)) {
                    $routes[] = [
                        'method' => $methodName,
                        'http_method' => $this->getHttpMethod($methodName),
                        'route_pattern' => $this->generateRoutePattern($methodName),
                        'needs_parameter' => $this->methodNeedsParameter($method),
                        'view_required' => $this->methodRequiresView($methodName)
                    ];
                }
            }

            return $routes;
        } catch (\Exception $e) {
            Log::error("Error scanning controller {$controllerClass}: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Check apakah method bisa di-route
     */
    public function isRoutableMethod(string $methodName): bool
    {
        // Skip magic methods dan methods yang excluded
        if (in_array($methodName, $this->excludedMethods)) {
            return false;
        }

        // Skip methods yang dimulai dengan underscore
        if (str_starts_with($methodName, '_')) {
            return false;
        }

        return true;
    }

    /**
     * Get HTTP method berdasarkan nama method
     */
    public function getHttpMethod(string $methodName): string
    {
        $httpMethods = [
            'index' => 'GET',
            'create' => 'GET',
            'store' => 'POST',
            'show' => 'GET',
            'edit' => 'GET',
            'update' => 'PUT,PATCH',
            'destroy' => 'DELETE',
            'delete' => 'DELETE',
            'restore' => 'PATCH',
            'forceDestroy' => 'DELETE',
            'duplicate' => 'POST',
            'builder' => 'GET',
            'uploadTemplate' => 'POST',
            'deleteTemplate' => 'POST',
            'createTemplate' => 'POST',
            'createBuilder' => 'POST',
            'bulkDestroy' => 'DELETE',
            'datatable' => 'GET',
            'uploadAvatar' => 'POST',
            'deleteAvatar' => 'DELETE',
            'moveOrder' => 'POST',
            'render' => 'POST',
            'renderComponent' => 'POST',
            'components' => 'GET',
            'getComponentInfo' => 'GET',
            'save' => 'POST',
            'load' => 'POST',
            'preview' => 'POST',
            'stats' => 'GET',
            'export' => 'GET',
        ];

        return $httpMethods[$methodName] ?? 'GET';
    }

    /**
     * Generate route pattern berdasarkan RESTful convention
     */
    public function generateRoutePattern(string $methodName): string
    {
        $patterns = [
            'index' => '',
            'create' => '/create',
            'store' => '',
            'show' => '/{id}',
            'edit' => '/{id}/edit',
            'update' => '/{id}',
            'destroy' => '/{id}',
            'delete' => '/{id}',
            'restore' => '/{id}/restore',
            'forceDestroy' => '/{id}/force',
            'duplicate' => '/{id}/duplicate',
            'builder' => '/{id}/builder',
            'uploadTemplate' => '/upload-template',
            'deleteTemplate' => '/delete-template',
            'createTemplate' => '/create-template',
            'createBuilder' => '/create-builder',
            'bulkDestroy' => '/bulk-delete',
            'datatable' => '/datatable',
            'uploadAvatar' => '/{id}/upload-avatar',
            'render' => '/render',
            'renderComponent' => '/render-component',
            'components' => '',
            'getComponentInfo' => '/{id}/info',
            'save' => '/save',
            'load' => '/load',
            'preview' => '/preview',
            'stats' => '/stats',
            'export' => '/export',
            // 'deleteAvatar' => '/{id}/delete-avatar',
        ];

        // Jika method RESTful standard, gunakan pattern yang sudah didefinisikan
        if (isset($patterns[$methodName])) {
            return $patterns[$methodName];
        }

        // Untuk custom methods, gunakan format: /method_name
        return '/' . $methodName;
    }

    /**
     * Check apakah method membutuhkan parameter (route model binding)
     */
    public function methodNeedsParameter(ReflectionMethod $method): bool
    {
        $parameters = $method->getParameters();

        foreach ($parameters as $param) {
            $type = $param->getType();
            if ($type && $type instanceof \ReflectionNamedType && !$type->isBuiltin()) {
                // Method punya parameter yang bukan builtin type (kemungkinan Model)
                return true;
            }
        }

        return false;
    }

    /**
     * Check apakah method butuh view (GET methods biasanya butuh view)
     */
    public function methodRequiresView(string $methodName): bool
    {
        $viewMethods = ['index', 'create', 'show', 'edit'];

        // RESTful methods yang biasanya return view
        if (in_array($methodName, $viewMethods)) {
            return true;
        }

        // Custom methods yang HTTP method-nya GET juga biasanya butuh view
        return $this->getHttpMethod($methodName) === 'GET';
    }

    /**
     * Get route priority (higher = registered first)
     * Routes dengan parameter harus didaftarkan setelah routes spesifik
     */
    public function getRoutePriority(string $methodName): int
    {
        // Specific routes without parameters get highest priority
        $specificMethods = [
            'index' => 100,
            'create' => 90,
            'store' => 85,
            'datatable' => 80,
            'bulkDestroy' => 75,  // Very important: before destroy
            'bulkDelete' => 75,
            'getPermissions' => 70,
            'uploadAvatar' => 60,
            'moveOrder' => 55,
        ];

        if (isset($specificMethods[$methodName])) {
            return $specificMethods[$methodName];
        }

        // Parameterized routes get lower priority
        $parameterizedMethods = [
            'show' => 30,
            'edit' => 25,
            'update' => 20,
            'destroy' => 15,  // Low priority because it uses {id}
            'delete' => 10,
        ];

        if (isset($parameterizedMethods[$methodName])) {
            return $parameterizedMethods[$methodName];
        }

        // Default priority for custom methods
        return 5;
    }

    /**
     * Generate RESTful route info lengkap untuk method tertentu
     */
    public function generateRestfulRoute(string $methodName, string $baseSlug): array
    {
        $httpMethod = $this->getHttpMethod($methodName);
        $pattern = $this->generateRoutePattern($methodName);
        $fullRoute = rtrim($baseSlug, '/') . $pattern;

        return [
            'http_method' => $httpMethod,
            'route' => $fullRoute,
            'name' => $methodName
        ];
    }
}
