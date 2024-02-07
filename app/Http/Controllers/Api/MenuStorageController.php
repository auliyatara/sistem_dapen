<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MenuStorage;

class MenuStorageController extends Controller
{
    public function index(){
        $operation = MenuStorage::first();
        return $this->responseFirst($operation);
    }
}
