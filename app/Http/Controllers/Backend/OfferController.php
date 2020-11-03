<?php

namespace App\Http\Controllers\Backend;

use App\Events\OfferStatusChanged;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\City;
use App\Models\Lang;
use App\Models\Offer;
use App\Models\OfferContent;
use App\Models\OfferPhoto;
use App\Models\OfferPrice;
use App\Models\Program;
use App\Models\ProgramContent;
use App\Models\Region;
use App\Traits\Exploding;
use App\Traits\LangTrait;
use App\Traits\RespondBack;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\DataTables;

class OfferController extends Controller
{
    use RespondBack;
    use Exploding;
    use LangTrait;

    public function __construct()
    {
        $this->middleware('authAdmin');
    }



    ######################################   Begin Indexing   ####################################################
    public function index(){
        return view('backend.offers.index', ['pageTitle' => 'All offers']);
    }

    public function allData(){

        $allOffers = Offer::select('id', 'program_id', 'user_id', 'status')
            ->with(['offerContents' => function($q){
                $q->select('id', 'name', 'address', 'offer_id')->whereIn('lang_id', [$this->mainLang(), $this->secondaryLang()])->orderBy('lang_id', 'desc');
            },
                'photos' => function($q){
                    $q->select('photo', 'offer_id');
                },
                'prices' => function($q){
                    $q->select('price', 'hint', 'offer_id');
                }])->get()
            ->transform(function ($offer) {
                $newOffer = [];
                $newOffer ['offer_id'] = $offer->id;
                $newOffer ['program_name'] = $this->getProgramName($offer->program_id);
                $newOffer ['user_name'] = User::select('name')->where('id', $offer->user_id)->first()->name;
                $newOffer ['status'] = $offer->status;
                $newOffer['offer_content'] = $offer->offerContents[0];
                $newOffer['name'] = $offer->offerContents[0]->name;
                $newOffer['main_photo'] =  $offer->photos[0]->photo;            // first photo to become the main
                $newOffer ['prices'] = $offer->prices;
                return $newOffer;
            });

            return Datatables::of($allOffers)
                ->addColumn('actions', 'backend.offers.actions')
                ->editColumn('main_photo', '<img src="{{ url(\'uploads/backend/offers/images/75x75\')}}/{{$main_photo}}" style="height: 75px; width: 75px" class="mx-auto d-block">')
                ->editColumn('status', '{{$status==1?"Active":"unActive"}}')
                ->rawColumns(['actions', 'main_photo', 'status'])
                ->make(true);

    }

    ######################################   End Indexing   ####################################################

    ######################################   Begin Creation   ####################################################

    public function create(){

        $city_lang = (currentLang() == "ar")? "ar": "en";           // cause city only available in (en && ar)
        $programs = $this->getAllProgramsNames();
        $activeLangs = Lang::select('id', 'name')->where('status', 1)->get();

        $cities = City::select('id', 'name_'.$city_lang.' as name')->get();                   // replace it with currentLang() or app()->getLocale

        return view('backend.offers.create', ['pageTitle' => 'Create New offer', 'programs' => $programs, 'activeLangs' => $activeLangs, 'cities' => $cities]);
    }

    public function insert(Request $request){
        //return var_dump($request->input('offer_price'));
        $langs = explode(',', $request->offer_langs);
        $validation = Validator::make($request->all(), $this->getCreateRules($request, $langs), $this->getCreateMessages());
        if($validation->fails())
            return $this->ResponseFail($validation->errors());

                                                /////     insert offer //////
        $newOfferID = DB::table('offers')->insertGetId([
            'program_id' => $request->input('program_id'),
            'user_id' => $request->input('user_id'),
            'city_id' => $request->input('city_id'),
            'region_id' => $request->input('region_id'),
            'status' => 1,
            'lat' => $request->input('offer_lat'),
            'lng' => $request->input('offer_lng'),
        ]);                                                                             // need to check if $newOfferID is inserted success before continue

                                        //////// add multi prices ///////
        foreach($request->input('offer_price') as $price){
            OfferPrice::create([
                'offer_id' => $newOfferID,
                'price' => $price,
                'hint' => 'price for single person'
            ]);
        }
                                            //// insert offer contents /////////
        foreach ($langs as $lang){
            OfferContent::create([
                'offer_id' => $newOfferID,
                'lang_id' => $lang,
                'name' => $request->input('offer_name_'.$lang),
                'slug' => $this->slugging($request->input('offer_name_'.$lang)),
                'description' => $request->input('offer_description_'.$lang),
                'address' => $request->input('offer_address_'.$lang),
            ]);
        }

                                                  /////////////////////// upload photos ////////////////////
        if($request->hasFile('offer_photo')){

            for($x=0; $x < count($request->file('offer_photo')); $x++){           // loop for every photo
                $photo = $request->file('offer_photo.'.$x);
                $photoName = 'offer_'.$x.time().'.'.$photo->getClientOriginalExtension();
                Image::make($photo)->resize(75, 75)->save(base_path('uploads/backend/offers/images/75x75/'.$photoName));
                Image::make($photo)->resize(250, 250)->save(base_path('uploads/backend/offers/images/250x250/'.$photoName));
                Image::make($photo)->resize(1200, 700)->save(base_path('uploads/backend/offers/images/1200x700/'.$photoName));

                OfferPhoto::create([                // assign photo name to offer (separate table)
                    'offer_id' => $newOfferID,
                    'photo' => $photoName,
                ]);
            }
        }

        return $this->ResponseSuccessMessage('Offer Inserted, wanna Add another one ?');

    }

    ######################################   End Creation   ####################################################

    ######################################   Begin Updating   ####################################################

    public function edit($oldLang, $id){

        $city_lang = (currentLang() == "ar")? "ar": "en";           // cause city only available in (en && ar)
        $offer = Offer::with(['user', 'offerContents', 'photos', 'prices'])->find($id);
        if(!isset($offer))
            return $this->ResponseFail('sry offer not found');

        $programs = $this->getAllProgramsNames();
        $allLangs = Lang::select('id', 'name')->where('status', 1)->get();
        $offerLangs = $offer->offerContents->flatten()->pluck('lang_id')->toArray();
        $cities = City::select('id', 'name_'.$city_lang.' as name')->get();                                 // replace it with currentLang() or app()->getLocale
        $regions = Region::select('id', 'name_'.$city_lang.' as name')->where('city_id', $offer->city_id)->get();         // replace it with currentLang() or app()->getLocale

        return view('backend.offers.edit', ['pageTitle' => 'Edit Offer', 'offer' => $offer,
            'programs' => $programs, 'allLangs' => $allLangs, 'offerLangs' => $offerLangs,
            'cities' => $cities, 'regions'=> $regions]);
    }

                                            ///////////////// updating ///////////////////////////
    public function update(Request $request, $offerId){
        //return $request->all();
        $offer = Offer::find($offerId);
        if(!isset($offer))
            return $this->ResponseFail('Sry this offer not found');

        $offerOldLangs = explode(',', $request->offer_old_langs);
        $offerNewLangs = explode(',', $request->offer_new_langs);

        $validation = Validator::make($request->all(), $this->getUpdateRules($request, $offerNewLangs, $offer), $this->getUpdateMessages());
        if($validation->fails())
            return $this->ResponseFail($validation->errors());


        $offer->program_id = $request->input('program_id');
        $offer->user_id = $request->input('user_id');
        $offer->city_id = $request->input('city_id');
        $offer->region_id = $request->input('region_id');
        $offer->status = $request->input('status');
        $offer->lat = $request->input('offer_lat');
        $offer->lng =$request->input('offer_lng');
        $offer->save();

        /////////////////////////////////////////////// Begin updating Offer Contents //////////////////////////////
        foreach ($offerOldLangs as $offerLang){
            if(!in_array($offerLang, $offerNewLangs)){                                           // remove offer content if un selected
                $offerContentDelete = OfferContent::where('offer_id', $offerId)->where('lang_id', $offerLang)->first();
                if(!isset($offerContentDelete))
                    return $this->ResponseFail('Sry this offer Content not found (deleting)');
                $offerContentDelete->delete();
                continue;
            }

            $offerContentUpdate = OfferContent::where('offer_id', $offerId)->where('lang_id', $offerLang)->first();                   // updated the offer content
            if(!isset($offerContentUpdate))
                return $this->ResponseFail('Sry this offer Content not found (updating)');
            $offerContentUpdate->name = $request->input('offer_name_'.$offerLang);
            $offerContentUpdate->description = $request->input('offer_description_'.$offerLang);
            $offerContentUpdate->address = $request->input('offer_address_'.$offerLang);
            $offerContentUpdate->save();
        }
        foreach($offerNewLangs as $offerLang){              // checking for new offer content && insert if found
            if(!in_array($offerLang, $offerOldLangs)){
                OfferContent::create([
                   'offer_id' => $offerId,
                    'lang_id' => $offerLang,
                    'name' => $request->input('offer_name_'.$offerLang),
                    'slug' => $this->slugging($request->input('offer_name_'.$offerLang)),
                    'description' => $request->input('offer_description_'.$offerLang),
                    'address' => $request->input('offer_address_'.$offerLang),
                ]);
            }
        }
        /////////////////////////////////////////////// Begin updating Offer photos //////////////////////////////
        if($request->deleted_photos !== "empty"){               // delete photos

            $deletedPhotos = explode(',', $request->deleted_photos);     //array of deleted photos ID's
            foreach ($deletedPhotos as $photoId){                                   // delete photos from disk
                $photo = OfferPhoto::find($photoId);
                if(!isset($photo))
                    return $this->ResponseFail('theis photo not found');
                Storage::disk('offers')->delete('images/75x75/'.$photo->photo);
                Storage::disk('offers')->delete('images/250x250/'.$photo->photo);
                Storage::disk('offers')->delete('images/1200x700/'.$photo->photo);
            }

            OfferPhoto::destroy($deletedPhotos);                /// delete record from OfferPhoto
        }

        if($request->hasFile('offer_photo')){                     // upload new photos

            for($x=0; $x < count($request->file('offer_photo')); $x++){           // loop for every photo
                $photo = $request->file('offer_photo.'.$x);
                $photoName = 'offer_'.$x.time().'.'.$photo->getClientOriginalExtension();
                Image::make($photo)->resize(75, 75)->save(base_path('uploads/backend/offers/images/75x75/'.$photoName));
                Image::make($photo)->resize(250, 250)->save(base_path('uploads/backend/offers/images/250x250/'.$photoName));
                Image::make($photo)->resize(1200, 700)->save(base_path('uploads/backend/offers/images/1200x700/'.$photoName));

                OfferPhoto::create([                // assign photo name to offer (separate table)
                    'offer_id' => $offerId,
                    'photo' => $photoName,
                ]);
            }
        }

        /////////////////////////////////////////////// Begin updating Offer prices //////////////////////////////

        foreach($offer->prices as $offerPrice){
            $offerPrice->price = $request->input('offer_price.'.$offerPrice->id);
            $offerPrice->hint = $request->input('offer_price_hint.'.$offerPrice->id);
            $offerPrice->save();
        }
                                        ////// notify user his offer updated by admin
        $user = User::find($offer->user_id);
        $user->notify(new \App\Notifications\OfferChanged('admin updated your offer', $offer));
        return $this->ResponseSuccessMessage('Offer updated successfully, u can go back now :)');


    }


    ######################################   End Updating   ####################################################

    ######################################   Begin Deletion   ####################################################

    public function delete(Request $request){
        $offer = Offer::find($request->id);
        if(!isset($offer))
            return $this->ResponseFail('sry this offer not found');

        if(isset($offer->photos) && $offer->photos->count() > 0){
            foreach ($offer->photos as $offerPhoto){
                Storage::disk('offers')->delete('images/75x75/'.$offerPhoto->photo);
                Storage::disk('offers')->delete('images/250x250/'.$offerPhoto->photo);
                Storage::disk('offers')->delete('images/1200x700/'.$offerPhoto->photo);
            }
        }
        $offer->delete();  // offer_contents && offer_photos && offer_prices  all automitaccly removed because there relations (foreign keys) in  mysql
        return $this->ResponseSuccessMessage('deleted successfully');

    }

    ######################################   End Deletion   ####################################################

    ######################################   Begin Status Changing   ####################################################


    public function changeStatus(Request $request){

        $offer = Offer::find($request->id);
        if(!isset($offer))
            return $this->ResponseFail('sry this offer not found');

        $offer->status = ($offer->status == 1)? 0: 1;
        $offer->save();

        ////// notify user his offer updated by admin
        $user = User::find($offer->user_id);
        $user->notify(new \App\Notifications\OfferChanged('admin updated your offer', $offer));

        return $this->ResponseSuccessMessage('status changed successfully');
    }

    ######################################   End Status Changing   ####################################################






    ////////////////////////////////////////////////////// Begin  validation Arrays //////////////////////////////////////////////////
    protected function getCreateRules($request, $langs){
        $arr = [
            'offer_price.*' => 'required|numeric',
            'offer_lat' => 'required|numeric',
            'offer_lng' => 'required|numeric',
            'program_id' => 'required|numeric',
            'user_id' => 'required|numeric',
            'offer_photo' => 'required',
            'city_id' => 'required|numeric',
            'region_id' => 'required'
        ];
        foreach($langs as $lang){
            $arr['offer_name_'.$lang] = 'required';
            $arr['offer_description_'.$lang] = 'required';
            $arr['offer_address_'.$lang] = 'required';
            if($request->hasFile('offer_photo')){
                for($x=0; $x < count($request->file('offer_photo')); $x++){
                    $arr['offer_photo.'.$x] = 'image|dimensions:min_width=1200,min_height=700,max_width=1300,max_height=800';
                }
            }
        }
        return $arr;
    }

    protected function getCreateMessages(){
        return [
            'program_id.required' => __('backend.Select program pls'),
            'program_id.numeric' => __('backend.Select program pls'),
            'user_id.required' => __('backend.Select owner'),
            'user_id.numeric' => __('backend.Select owner'),
            'offer_name_*.required' => __('backend.Offer name is required'),
            'offer_price.*.required' => __('backend.Offer price is required'),
            'offer_price.*.numeric' => __('backend.Offer price must be numbers'),
            'offer_description_*.required' => __('backend.Offer description is required'),
            'offer_address_*.required' => __('backend.Offer address is required'),
            'offer_lat.required' => __('backend.Please select offer location on map'),
            'offer_lat.numeric' => __('backend.Please select offer location on map'),
            'offer_lng.required' => __('backend.Please select offer location on map'),
            'offer_lng.numeric' => __('backend.Please select offer location on map'),
            'offer_photo.required' => __('backend.Please select at least one photo'),
            'offer_photo.*.image' => __('backend.photo must be an image'),
            'offer_photo.*.dimensions' => __('backend.photo size must be 1200x700'),
            'city_id.required' => __('backend.Please select City'),
            'city_id.numeric' => __('backend.Please select City'),
            'region_id.required' => __('backend.please select region'),

        ];
    }

    /////////////////////////////////////////////////////////// update Rules && messages /////////////////////////////////////////////

    protected function getUpdateRules($request, $langs, $offer){
        $arr = [
            'offer_price.*' => 'required|numeric',
            'offer_price_hint.*' => 'required',
            'offer_lat' => 'required|numeric',
            'offer_lng' => 'required|numeric',
            'program_id' => 'required|numeric',
            'user_id' => 'required|numeric',
            'city_id' => 'required|numeric',
            'region_id' => 'required'

        ];
        foreach($langs as $lang){
            $arr['offer_name_'.$lang] = 'required';
            $arr['offer_description_'.$lang] = 'required';
            $arr['offer_address_'.$lang] = 'required';
        }
        if($request->hasFile('offer_photo')){
            for($x=0; $x < count($request->file('offer_photo')); $x++){
                $arr['offer_photo.'.$x] = 'image|dimensions:min_width=1200,min_height=700,max_width=1300,max_height=800';
            }
        }
        if($request->deleted_photos !== "empty") {         // if all old photos deleted  add photo required Rule
            $deletedPhotos = explode(',', $request->deleted_photos);
            if(count($deletedPhotos) >= $offer->photos->count())
                $arr['offer_photo'] = 'required';
        }

        return $arr;
    }

    protected function getUpdateMessages(){
        return [
            'program_id.required' => __('backend.Select program pls'),
            'program_id.numeric' => __('backend.Select program pls'),
            'user_id.required' => __('backend.Select owner'),
            'user_id.numeric' => __('backend.Select owner'),
            'offer_name_*.required' => __('backend.Offer name is required'),
            'offer_price.*.required' => __('backend.Offer price is required'),
            'offer_price.*.numeric' => __('backend.Offer price must be numbers'),
            'offer_price_hint.*.required' => __('backend. Offer price hint required'),
            'offer_description_*.required' => __('backend.Offer description is required'),
            'offer_address_*.required' => __('backend.Offer address is required'),
            'offer_lat.required' => __('backend.Please select offer location on map'),
            'offer_lat.numeric' => __('backend.Please select offer location on map'),
            'offer_lng.required' => __('backend.Please select offer location on map'),
            'offer_lng.numeric' => __('backend.Please select offer location on map'),
            'offer_photo.required' => __('backend.Please select at least one photo'),
            'offer_photo.*.image' => __('backend.photo must be an image'),
            'offer_photo.*.dimensions' => __('backend.photo size must be 1200x700'),
            'city_id.required' => __('backend.Please select City'),
            'city_id.numeric' => __('backend.Please select City'),
            'region_id.required' => __('backend.please select region'),

        ];
    }

    ////////////////////////////////////////////////////// End  validation Arrays //////////////////////////////////////////////////

    ////////////////////////////////////////////////////// Begin other functions /////////////////////////////////////////////////////////

    public function getAllProgramsNames(){

        return Program::where('status', 1)->with(['programContents' => function($q) {
            $q->whereIn('lang_id', [$this->mainLang(), $this->secondaryLang()])->orderBy('lang_id', 'desc');
            }])->get()
            ->transform(function($programs){
                $newPrograms = [];
                $newPrograms['id'] = $programs->id;
                //dd($programs->programContents);
                $newPrograms['name'] = $programs->programContents[0]->name;
                return $newPrograms;

            });

    }

    public function getProgramName($program_id){
        $name = ProgramContent::where('program_id', $program_id)->whereIn('lang_id', [$this->mainLang(), $this->secondaryLang()])->orderBy('lang_id', 'desc')->first()->name;
        return $name;
    }
    ////////////////////////////////////////////////////// End other functions /////////////////////////////////////////////////////////
}
