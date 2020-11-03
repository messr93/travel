<?php

namespace App\Http\Middleware;

use Closure;

class LangLocaleMonitor
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

        app()->setLocale(currentLang());

        $firstSegment = $request->segment(1);
        if($firstSegment !== app()->getLocale()){
            if(in_array($firstSegment, activeLangs())){
                session(['newLang' => $firstSegment]);
                app()->setLocale($firstSegment);
                return $next($request);
            }else{
                $url = explode($firstSegment, $request->url());            //replace "travel" with ".com"
                $url = url('/').'/'.app()->getLocale().$url[1];
                return redirect($url);
            }
        }

        return $next($request);
    }

}


