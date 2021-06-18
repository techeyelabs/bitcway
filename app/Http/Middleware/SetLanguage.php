<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $selectedLanguage = $request->language;
        if ($selectedLanguage == "jp" || $selectedLanguage == "en" || $selectedLanguage == "cn" || $selectedLanguage == "JP" || $selectedLanguage == "EN" || $selectedLanguage == "CN") {
            \App::setLocale($request->language);
        }else{
            \App::setLocale("jp");
        }

        return $next($request);
    }
}
