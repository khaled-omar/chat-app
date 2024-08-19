<?php

namespace App\Http\Middleware;

use App\Exceptions\ApiException;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     *
     * @throws \App\Exceptions\ApiException
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->header('Accept-Language');
        $locale = ! filled($locale) ? config('app.locale') : $locale;

        if (! in_array($locale, config('constants.available_locales'))) {
            throw new ApiException(__('Unsupported language.'), 422);
        }

        App::setLocale($locale);

        return $next($request);
    }
}
