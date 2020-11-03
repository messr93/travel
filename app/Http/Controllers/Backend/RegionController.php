<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Region;
use App\Traits\RespondBack;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    use RespondBack;

    public function getRegions(Request $request){
        $region_lang = (currentLang() == "ar")? "ar": "en";

        $regions = Region::select('id', 'name_'.$region_lang.' as name')->where('city_id',$request->cityId)->get();
        if(!isset($regions) && $regions->count <1)
            return $this->ResponseFail('sry those regions not found');
        return $this->ResponseSuccessData($regions);
    }
}
