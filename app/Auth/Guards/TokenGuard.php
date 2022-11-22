<?php

namespace App\Auth\Guards;
use Illuminate\Auth\TokenGuard as BaseTokenGuard;

class TokenGuard extends BaseTokenGuard
{
    public function user()
    {
        $token = parent::user();
        if (! $token) return $token;

        if ($token->driver) return $token->driver;
        if ($token->client) return $token->client;
        return null;
    }
}