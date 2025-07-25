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
            'bulkDestroy' => 'DELETE',
            'bulkDelete' => 'DELETE',
            'datatable' => 'GET',
            'uploadAvatar' => 'POST',
            'deleteAvatar' => 'DELETE',
            'moveOrder' => 'POST',
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
            'bulkDestroy' => '/bulk-delete',
            'bulkDelete' => '/bulk-delete',
            'datatable' => '/datatable',
            'uploadAvatar' => '/{id}/upload-avatar',
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
