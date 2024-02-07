<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class RiwayatController extends Controller
{
    public function index(){
        $auth = Auth::id();
        $operation = DB::select("
            SELECT user_nama,role_name,description, activity_log.created_at,properties FROM activity_log
            JOIN users ON user_id = causer_id
            JOIN roles ON user_role_id = role_id
            WHERE causer_id = '".$auth ."'
        ");
        return $this->response($operation);
    }

    public function destroy($id){
        try {            
            $operation = DB::statement("DELETE FROM activity_log");
            return $this->responseDelete(1);
        } catch (\Exception $e) {
            return $this->responseDelete($e->getMessage());
        }
    }
}
