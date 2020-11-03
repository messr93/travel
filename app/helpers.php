<?php

use App\Models\Lang;

if(!function_exists('activeLangs')){
    function activeLangs(){
        if(!session('activeLangs'))
            session()->put('activeLangs', Lang::where('status', 1)->pluck('code')->toArray());
        return session('activeLangs')?: ['en'];
    }
}


if(!function_exists('changeLang')){
    function changeLang($newLang){
        if(in_array($newLang, activeLangs())){
            session(['newLang' => $newLang]);
            app()->setLocale(currentLang());
        }
        return back();
    }
}

if(!function_exists('currentLang')){
    function currentLang(){
        $current = session('newLang', 'en');
        return in_array($current, activeLangs())? $current: 'en' ;
    }
}

if(!function_exists('langDir')){
    function langDir(){
        return (currentLang() == "ar")? "rtl": "ltr";
    }

}

if(!function_exists('getLocaleLang')){
    function getLocaleLang(){
        return (currentLang() == "ar")? "rtl": "ltr";
    }

}

