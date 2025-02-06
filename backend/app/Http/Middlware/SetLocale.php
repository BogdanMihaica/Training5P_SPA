<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Change the session lang variable
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('lang')) {
            $lang = $request->get('lang');

            if (in_array($lang, ['en', 'ro'])) {
                App::setLocale($lang);
                session()->put('lang', $lang);
            }
        } else {
            $lang = session()->get('lang');
            App::setLocale($lang);
        }

        return $next($request);
    }
}
