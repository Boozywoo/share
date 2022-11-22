<?php

namespace App\Exceptions;

use App\Services\Support\HandlerError;
use Exception;
use Illuminate\Auth\AuthenticationException;
use GrahamCampbell\Exceptions\NewExceptionHandler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    public function report(Exception $exception)
    {
        if (!env('APP_DEBUG')) {
            HandlerError::index($exception);
        }

        parent::report($exception);
    }

    public function render($request, Exception $exception)
    {
        if ($exception instanceof \Bican\Roles\Exceptions\RoleDeniedException) {
            return redirect('admin/login');
        }
        $hideParams = ['DB_PASSWORD', 'PUSHER_APP_SECRET', 'REDIS_PASSWORD', 'SMS_API_PASSWORD', 'MAIL_PASSWORD'];    // Protect dev servers passwords
        foreach ($hideParams as $param) {
            $_SERVER[$param] = '***';
            $_ENV[$param] = '***';
        }

        return parent::render($request, $exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest('login');
    }
}
