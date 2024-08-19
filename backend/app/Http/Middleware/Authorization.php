<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;

/**
 * Class Authorization
 */
class Authorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $entity
     * @return mixed
     */
    public function handle($request, Closure $next, $entity)
    {
        $entity = Str::studly($entity);
        $class = "App\\Http\\Middleware\\Authorization\\{$entity}Authorization";

        return resolve($class)->handle($request, $next);
    }
}
