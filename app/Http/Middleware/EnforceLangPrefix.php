<?php

namespace App\Http\Middleware;

use Closure;

class EnforceLangPrefix
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
        if($request->method() !== "POST"){
            /*$url = explode('travel', $request->url());            //replace "travel" with ".com"
            $url = url('/').'/'.currentLang().$url[1];*/
            $url = $request->segments();
            array_unshift($url, currentLang());
            return redirect()->to(implode('/', $url) );
        }
        return $next($request);
    }


}
