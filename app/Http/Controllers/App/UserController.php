<?php

namespace App\Http\Controllers\App;
use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


class UserController extends Controller
{    
    public function index(){
        return view('layout.User.index');
    }
}
