<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;

class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $langs = activeLangs();
        $defaultLang = 'en';
        $segment = $request->segment(1);

        if ($request->method() === 'GET' || $segment == "/api") {             // not POST , not api

            if (!in_array($segment, $langs)) {              // if first segment not in active langs ['en', 'ar', ...]

                $registered_routes = [];
                $routeCollection = Route::getRoutes();
                foreach ($routeCollection as $value) {
                    $registered_routes[] =  substr($value->uri(), 7);    // 7 cause {locale?} is 7 characters
                }

                $segments = $request->segments();
                $fallback = session('locale') ?: $defaultLang;
                $copy_segments =$segments;
                array_splice($copy_segments, 0, 1);
                $url = '/'.implode('/', $copy_segments);

                if(in_array($url, $registered_routes)){   // if url after first segment registered route
                    $segments[0] = $fallback;                         // edit prefix lang
                }else{  // any case else
                    array_unshift($segments, $fallback);        // add prefix lang
                }

                return redirect()->to(implode('/', $segments));
            }

            session(['locale' => $segment]);
            app()->setLocale($segment);
        }

        return $next($request);
    }
}
