<?php
namespace App\Helpers\Response;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response as HttpResponse;

class Response
{
    public static function HTTP_OK(mixed $args): JsonResponse
    {
        if ($args instanceof JsonResource) {
            return $args->response()->setStatusCode(HttpResponse::HTTP_OK);
        }
        return response()->json([...$args], HttpResponse::HTTP_OK);
    }

    public static function HTTP_CREATED(mixed $args): JsonResponse
    {
        if ($args instanceof JsonResource) {
            return $args->response()->setStatusCode(HttpResponse::HTTP_CREATED);
        }
        return response()->json([...$args], HttpResponse::HTTP_CREATED);
    }

    public static function HTTP_LOCKED(mixed $args): JsonResponse
    {
        if ($args instanceof JsonResource) {
            return $args->response()->setStatusCode(HttpResponse::HTTP_LOCKED);
        }
        return response()->json([...$args], HttpResponse::HTTP_LOCKED);
    }
}
