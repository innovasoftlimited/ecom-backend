<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class BaseController
{
    /**
     * @param array $result
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function success(array $result, string $message, int $code = Response::HTTP_OK): JsonResponse
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];
        return response()->json($response, $code);
    }

    /**
     * @param array $result
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function successWithPagination(array $result, string $message, int $code = Response::HTTP_OK): JsonResponse
    {
        $response = [
            'success'      => true,
            'data'         => $result['data'] ?? [],
            'total'        => $result['total'] ?? 0,
            'per_page'     => $result['per_page'] ?? 10,
            'current_page' => $result['current_page'] ?? 1,
            'total_pages'  => isset($result['total_pages']) ? $result['total_pages'] : 1,
            'message'      => $message,
        ];
        return response()->json($response, $code);
    }

    /**
     * @param string $errorMessage
     * @param array $errors
     * @param int $code
     * @return JsonResponse
     */
    public function error(string $errorMessage, array $errors = [], int $code = Response::HTTP_UNPROCESSABLE_ENTITY): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $errorMessage,
            'errors'  => $errors,
        ];
        return response()->json($response, $code);
    }

    /**
     * Service Status
     *
     * @return JsonResponse
     */
    public function serviceStatus(): JsonResponse
    {
        return $this->success([
            'timezone' => date_default_timezone_get(),
        ], 'Service is running');
    }

}
