<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class KepegawaianController extends Controller
{
    public function index(BaseController $base){
        $access = $base->UserAccess('kepegawaian');
        abort_unless($access['view'], 403);
        return view('layout.Kepegawaian.index', $access);
    }
}
