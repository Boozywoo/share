<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/order/notice_pay',
        '/api/telegram/web_hook',
        '/api/v1/monitoring/auth/login',
        '/api/v1/client/*',
        '/api/v1/call/*',
        '/api/sms/web_hook'
    ];
}
