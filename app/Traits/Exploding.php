<?php

namespace App\Traits;

Trait Exploding{

    function slugging($name){
        $arr = explode(' ', $name);
        return implode('-', $arr);
    }
}
