<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    /**
     * Send a success response.
     *
     * @param  string  $message
     * @param  mixed  $data
     * @param  int  $status
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successResponse($message = '', $data = null, $status = 200): JsonResponse
    {
        $response = [
            'message' => $message,
            'data' => $data,
        ];

        return response()->json($response, $status);
    }

    /**
     * Send a success response.
     *
     * @param  string  $message
     * @param  mixed  $data
     * @param  int  $status
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successResponseWithResourceCollection($message = '', $data = null, $status = 200): JsonResponse
    {
        $response = $data->response()->getData();
        $response->message = $message;

        return response()->json($response, $status);
    }

    /**
     * Send a created response.
     *
     * @param  string  $message
     * @param  mixed  $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createdResponse($message = '', $data = null): JsonResponse
    {
        return $this->successResponse($message, $data, 201);
    }

    /**
     * Send an error response.
     *
     * @param  string  $message
     * @param  mixed  $data
     * @param  int  $status
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorResponse($message = '', $status = 400): JsonResponse
    {
        $response = [
            'message' => $message,
        ];

        return response()->json($response, $status);
    }

    /**
     * Send a not found response.
     *
     * @param  string  $message
     * @param  mixed  $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function notFoundResponse($message = '', $data = null): JsonResponse
    {
        return $this->errorResponse($message, $data, 404);
    }
}
