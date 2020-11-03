<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Http\Controllers\Controller;
use App\Mail\AdminResetPassword;
use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AdminAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    public $guard = 'admin';

    ##########################  Begin Show login form  #############################
    public function showLogin(){
        return view('backend.auth.admins.login');
    }
    ##########################  end Show login form  #############################

    ##########################  Begin login Process  #############################
    public function login(Request $request){
        $request->validate([                //validate mail and password
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $cords = $request->only(['email', 'password']);
        $remeber = $request->remember == 1? true: false;
        if(auth()->guard($this->guard)->attempt($cords, $remeber)){         // if correct email & pass
            return redirect()->route('admin.dashboard');
        }
        return redirect()->back()->withInput()->with(['message' => 'Wrong Mail or Password', 'alert_type' => 'danger']);
    }
    ##########################  End login Process  #############################

    ##########################  Begin Logout Process  #############################
    public function logout(){
        auth()->guard($this->guard)->logout();
        return redirect()->route('admin.showLoginForm');
    }
    ##########################  End Logout Process  #############################

    ##########################  Begin Forget Password Process  #############################
    public function showForgetForm(){
        return view('backend.auth.admins.forget');
    }

    public function sendForgetToken(Request $request){
        $request->validate([
            'email' => 'required|email'
        ]);
        $admin = Admin::where('email', $request->input('email'))->first();
        if(!isset($admin)) {                                                                //if email not registered
            return redirect()->back()->withInput()->with(['message' => 'This Email Not registered', 'alert_type' => 'danger']);
        }else{                                                                                  //if not
            $token = app('auth.password.broker')->createToken($admin);
            DB::table('admin_password_resets')->insert([
                'email' => $admin->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);
            $email_data = ['name' => $admin->name, 'token' => $token];
            Mail::to($admin->email)->send(new AdminResetPassword($email_data));
            return redirect()->back()->with(['message' => 'Done, Reset password link send to your email', 'alert_type' => 'success']);
        }

    }

    ##########################  End Forget Password Process  #############################

    ##########################  Begin Reset Password Process  #############################
    public function showResetForm($token){
        $data = DB::table('admin_password_resets')->orderBy('id', 'desc')
            ->where('token', $token)
            ->where('created_at', '>', Carbon::now()->subHours(2))
            ->first();
        if(!isset($data))
            return redirect()->route('admin.showForgetForm');                       //expired token

        return view('backend.auth.admins.reset', compact('data'));              /// valid token

    }

    public function updatePassword(Request $request){

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ],[],[
            'password' => 'Password',
            'password_confirmation' => 'Confirmation Password'
        ]);

        $data = DB::table('admin_password_resets')->orderBy('id', 'desc')
            ->where('email', request('email'))
            ->where('token', request('token'))
            ->where('created_at', '>', Carbon::now()->subHours(2))
            ->first();
        if(!isset($data))                                                                           //session ended
            return redirect()->route('admin.showForgetForm');

        DB::table('admin_password_resets')->where('email', request('email'))->delete();          //delete any row has this E-mail
        $admin = Admin::where('email', $data->email)->first();      // get admin acc
        $admin->update(['password' => bcrypt(request('password'))]);        // update admin password
        auth()->guard('admin')->login($admin);                              //login admin with new pass
        return redirect()->route('admin.dashboard');                // redirect to dashboard

    }
    ##########################  End Reset Password Process  #############################

}
