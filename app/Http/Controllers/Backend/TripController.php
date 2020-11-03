<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TripController extends Controller
{

    public function __construct()
    {
        $this->middleware('authAdmin');
    }

    public function index(){
       return view('backend.trips.index', ['pageTitle' => 'All Trips']);
    }

    public function create(){
        return view('backend.trips.create', ['pageTitle' => 'Create New trip']);
    }

    public function insert(Request $request){

    }
}
