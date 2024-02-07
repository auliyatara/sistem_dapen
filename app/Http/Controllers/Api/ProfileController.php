<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;


class ProfileController extends Controller
{
    public function index(){
        $auth = Auth::id();
        $operation = User::with('role')->where('user_id',$auth)->get();
        return $this->responseFirst($operation);
    }
}
