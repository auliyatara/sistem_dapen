<?php

namespace App\Http\Controllers\App;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController as Base;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index(Base $Base){
        $operation = $Base->UserAccess('riwayat');
        if($operation['view']){
            $user = Auth::user();
            $roleName = $user && $user->role
                ? strtolower(preg_replace('/[^a-z0-9]/i', '', $user->role->role_name))
                : '';
            $canResetHistory = in_array($roleName, ['admin', 'superadmin'], true);

            return view('layout.Riwayat.index', array_merge($operation, compact('canResetHistory')));
        }
        return abort(403);
    }
}
