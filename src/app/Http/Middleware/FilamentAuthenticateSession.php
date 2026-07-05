<?php

namespace App\Http\Middleware;

use Filament\Http\Middleware\AuthenticateSession;

class FilamentAuthenticateSession extends AuthenticateSession
{
    protected function redirectTo($request): ?string
    {
        return route('login');
    }
}
