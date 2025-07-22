<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class ResponseHelper
{
    /**
     * Return success JSON response
     */
    public static function success(string $message, $data = null, int $code = 200): JsonResponse
    {
        $response = [
            'status' => 'success',
            'message' => $message,
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }

    /**
     * Return error JSON response
     */
    public static function error(string $message, int $code = 400, $errors = null): JsonResponse
    {
        $response = [
            'status' => 'error',
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * Return redirect response with flash message
     */
    public static function redirect(string $route, string $message, string $type = 'success'): RedirectResponse
    {
        return redirect()->route($route)->with($type, $message);
    }

    /**
     * Return redirect back with flash message
     */
    public static function back(string $message, string $type = 'success'): RedirectResponse
    {
        return redirect()->back()->with($type, $message);
    }

    /**
     * Handle both JSON and redirect responses based on request
     */
    public static function handle($request, string $route, string $message, $data = null, string $type = 'success')
    {
        if ($request->expectsJson() || $request->ajax()) {
            return $type === 'success' 
                ? self::success($message, $data)
                : self::error($message);
        }

        return self::redirect($route, $message, $type);
    }

    /**
     * Handle both JSON and back redirect responses based on request
     */
    public static function handleBack($request, string $message, $data = null, string $type = 'success')
    {
        if ($request->expectsJson() || $request->ajax()) {
            return $type === 'success' 
                ? self::success($message, $data)
                : self::error($message);
        }

        return self::back($message, $type);
    }
}
