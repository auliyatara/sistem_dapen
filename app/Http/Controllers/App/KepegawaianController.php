<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KepegawaianController extends Controller
{
    public function index(){
        return view('layout.Kepegawaian.index');
    }
}
