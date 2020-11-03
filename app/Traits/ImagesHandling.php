<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

Trait ImagesHandling{

    function uploadMultiImage($photo, $photoName, $folder_name){

        Image::make($photo)->resize('75', '75')->save(base_path('uploads/backend/'.$folder_name.'/images/75x75/'.$photoName));
        Image::make($photo)->resize('250', '250')->save(base_path('uploads/backend/'.$folder_name.'/images/250x250/'.$photoName));
        Image::make($photo)->resize('1200', '700')->save(base_path('uploads/backend/'.$folder_name.'/images/images/1200x700/'.$photoName));

    }
    function deleteMultiImage($disk, $photoName){
        Storage::disk($disk)->delete('images/75x75/'.$photoName);
        Storage::disk($disk)->delete('images/250x250/'.$photoName);
        Storage::disk($disk)->delete('images/1200x700/'.$photoName);
    }
}
