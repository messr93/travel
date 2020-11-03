<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('authAdmin');
    }

    public function index(){
        //return app()->getLocale();
        return view('backend.dashboard');
    }


}
