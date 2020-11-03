<?php

namespace App\Traits;

use App\Models\Lang;

Trait LangTrait
{

    function mainLang(){
        return 1;        // en ID
    }

    function secondaryLang(){
        return  Lang::where('code', currentLang())->first()->id;
    }


}
