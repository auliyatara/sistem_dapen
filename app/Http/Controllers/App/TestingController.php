<?php

namespace App\Http\Controllers\App;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class TestingController extends Controller
{
    public function index(){
        return view('layout.Program.pdf');
    }
}
