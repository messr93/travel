<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    ############################################################### Edit Info ####################################################

    public function editProfileInfo($oldLang, Request $request){
        $user = $request->user();
        if(!isset($user))
            return 'Ops, something wrong';

        return view('frontend.profiles.edit-profile-info', ['pageTitle' => 'Edit profile info', 'user' => $user]);
    }

    public function updateProfileInfo(Request $request){

        $request->validate($this->getUpdateInfoRules($request));

        $user = $request->user();
        $photoName = $user->photo;

        if($request->hasFile('photo')){
            $photo = $request->file('photo');
            $photoName = 'user_profile_'.time().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->resize(250, 250)->save(base_path('uploads/frontend/users/profile_picture/'.$photoName));

            if($user->photo != 'zoro_admin.jpg')           // delete old photo from disk
                Storage::disk('users_profile')->delete($user->photo);
        }

        $user->name = $request->name;
        $user->gender = $request->gender;
        $user->photo = $photoName;
        if(isset($request->address))
            $user->address = $request->address;
        $user->save();

        return redirect()->back()->with('success', 'Your ino updated successfully');
    }

    ############################################################### Edit Email ####################################################

    public function editProfileEmail($oldLang, Request $request){

        $user = $request->user();

        if(!isset($user->password)) {             // if social user
            session()->put(['social_user_note'=> 'you need to set new password first to this account', 'from_change_email' => 'from_change_email']);
            return redirect()->route('editProfilePassword');
        }
        return view('frontend.profiles.edit-email', ['pageTitle' => 'Change Email']);
    }

    public function updateProfileEmail(Request $request){

        $request->validate(['new_email' => 'required|string|min:8|different:old_email']);
        $request->user()->update(['email' => $request->input('new_email')]);                            /// need to resend verification email
        return redirect()->back()->with('success', 'Your email changed successfully');

    }


    ############################################################### Edit Password ####################################################

    public function editProfilePassword($oldLang, Request $request){
        $title = isset($request->user()->password)? 'Change Your password': 'Set new Password';
        return view('frontend.profiles.edit-password', ['pageTitle' => $title]);
    }

    public function updateProfilePassword(Request $request){

        $user = $request->user();
        if($request->filled('current_password') || isset($user->password)){               // not social user

            $request->validate([
                'current_password' => 'required|min:8|string',
                'new_password' => 'required|min:8|string|different:current_password|confirmed',
                'new_password_confirmation' => 'required|min:8|string',
                ]);

            if(!Hash::check($request->input('current_password'), $user->password) )       // check if current password is not matching
                return redirect()->back()->withErrors(['current_password'=> 'This not your password !']);

            $user->update(['password' => bcrypt($request->input('new_password'))]);
            return redirect()->back()->with('success', 'Your password updated successfully');

        }
        else if(!isset($user->password)){                                                   // social user

            $request->validate([
                'new_password' => 'required|min:8|string|confirmed',
                'new_password_confirmation' => 'required|min:8|string',
            ]);
            $user->update(['password' => bcrypt($request->input('new_password'))]);

            if($request->input('submit_type') == 'from_change_email'){                      // if coming from changing mail for social fkking user
                session()->forget('from_change_email');
                session()->put('success', 'Your new password, now you can change email');
                return redirect()->route('editProfileEmail');
            }
            return redirect()->back()->with('success', 'Your new password created successfully');
        }

        return redirect()->back()->with('fails', 'Something wrong');

    }





    ////////////////////////////////////////////////////// Begin Validation Rules //////////////////////////////////////////////
    public function getUpdateInfoRules($request){
        $arr['name'] = 'required|string|max:50';
        $arr['gender'] = 'required|numeric|min:0|max:1';
        if(isset($request->address))
            $arr['address'] = 'string';
        if($request->hasFile('photo'))
            $arr['photo'] = 'image|dimensions:max_width=900,max_height=900';

        return $arr;

    }

    ////////////////////////////////////////////////////// End Validation Rules //////////////////////////////////////////////

}
