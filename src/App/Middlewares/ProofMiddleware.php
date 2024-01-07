<?php

namespace Src\App\Middlewares;

use Closure;
use Src\Http\Request;

class ProofMiddleware
{
    public function handle(Closure $next, Request $passable, string $name)
    {
        return $next($passable);
    }
}