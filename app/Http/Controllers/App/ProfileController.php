<?php

namespace App\Http\Controllers\App;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(){
        return view('layout.Profile.index');
    }
}
