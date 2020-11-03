<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Offer;
use App\Models\ProgramContent;
use App\Models\Region;
use App\Traits\LangTrait;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class WebsiteController extends Controller
{
    use LangTrait;

    public function index(){
        
        $txt = '';
        $startdate = strtotime("Saturday");
        $enddate = strtotime("+6 weeks", $startdate);

        while ($startdate < $enddate) {
            $txt.= date("Y-M-d", $startdate) .' - '.date("Y-M-d", strtotime("+6 days" ,$startdate)) . "<br>";
            $startdate = strtotime("+1 week", $startdate);
        }
        return $txt;
        
        $city_lang = (currentLang() == "ar")? "ar": "en";

        $allOffers = Offer::where('status', 1)->select('id', 'program_id', 'city_id', 'region_id', 'user_id', 'status',  'created_at')
            ->with(['offerContents' => function($q){
                $q->select('id', 'name', 'address',  'slug', 'offer_id')->whereIn('lang_id', [$this->mainLang(), $this->secondaryLang()])->orderBy('lang_id', 'desc');
            },
                'photos' => function($q){
                    $q->select('photo', 'offer_id');
                },
                'prices' => function($q){
                    $q->select('price', 'hint', 'offer_id');
                }])->get()
            ->transform(function ($offer) use($city_lang){
                $newOffer = [];
                $newOffer ['offer_id'] = $offer->id;
                $newOffer ['user_id'] = $offer->user_id;
                $newOffer ['user_name'] = User::find($offer->user_id)->name;
                $newOffer ['program_name'] = $this->getProgramName($offer->program_id);
                $newOffer ['city'] = City::select('name_'.$city_lang.' as name')->where('id', $offer->city_id)->first()->name;
                $newOffer ['region'] = Region::select('name_'.$city_lang.' as name')->where('id', $offer->region_id)->first()->name;
                $newOffer['name'] = $offer->offerContents[0]->name;
                $newOffer['main_photo'] = $offer->photos[rand(0, $offer->photos->count()-1)]->photo;            // first photo to become the main
                $newOffer ['price'] = $offer->prices[0]->price;
                $newOffer ['created_at'] = $offer->created_at->diffForHumans();
                $newOffer['url'] = 'offers/'.$this->getProgramName($offer->program_id).'/'.$offer->id.'/'.$offer->offerContents[0]->slug;
                return $newOffer;
            });

        return view('layouts.website', ['offers' => $allOffers]);
    }



    ///////////////////////////////////////////////////////////////
    public function getProgramName($program_id){

        $name = ProgramContent::where('program_id', $program_id)->whereIn('lang_id', [$this->mainLang(), $this->secondaryLang()])->orderBy('lang_id', 'desc')->first()->name;
        return $name;
    }
}
