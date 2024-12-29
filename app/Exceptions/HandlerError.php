<?php

namespace App\Exceptions;

use App\Traits\ResponseTrait;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class HandlerError
{
    use ResponseTrait;

    public function handle(Throwable $exception, Request $request): ?JsonResponse
    {
        return match (true) {
            $exception instanceof NotFoundHttpException => $this->handleNotFoundHttpException($exception, $request),
            $exception instanceof AuthenticationException => $this->handleAuthenticationException($exception, $request),
            default => self::failResponse(500, message: 'error')
        };
    }

    protected function handleNotFoundHttpException(NotFoundHttpException $exception, Request $request): ?JsonResponse
    {
        if ($request->is(['api', 'api/*'])) {
            $statusCode = $exception->getStatusCode();

            return match ($statusCode) {
                400 => self::failResponse(400,'bad_request'),
                403 => self::failResponse(403, 'forbidden'),
                404 => self::failResponse(404, 'not_found'),
                405 => self::failResponse(405, 'method_not_allowed'),
                503 => self::failResponse(405, 'service_unavailable'),
                default => self::failResponse(500, 'Whoops, server error'),
            };
        }

        return null;
    }

    protected function handleAuthenticationException(AuthenticationException $exception, Request $request): ?JsonResponse
    {
        if ($request->is('api/*')) {
            return self::failResponse(401, __('unauthenticated'));
        }

        return null;
    }
}
