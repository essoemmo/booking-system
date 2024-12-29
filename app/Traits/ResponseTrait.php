<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ResponseTrait
{
    // success response api
    public static function successResponse(string $message = '', $responseData = [], int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'status_code' => $statusCode,
            'message' => $message,
            'data' => $responseData,
        ], $statusCode);
    }

    // success pagination response api
    public static function successResponsePaginate(array $data, string $message = '', int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'status_code' => $statusCode,
            'message' => $message,
            'data' => $data['data'],
            'links' => $data['links'],
            'meta' => $data['meta'],
        ], $statusCode);
    }

    // fail response api
    public static function failResponse(int $statusCode, string $message = ''): JsonResponse
    {
        return response()->json([
            'status_code' => $statusCode,
            'message' => $message,
        ], $statusCode);
    }
}
