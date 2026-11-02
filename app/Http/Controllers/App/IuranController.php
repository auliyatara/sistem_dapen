<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IuranController extends Controller
{
    public function index(Request $request){
        $request->validate(['year' => 'nullable|integer|between:1900,2099']);
        $iuranYear = (int) $request->input('year', now('Asia/Jakarta')->year);

        return view('layout.Iuran.index', compact('iuranYear'));
    }
}
