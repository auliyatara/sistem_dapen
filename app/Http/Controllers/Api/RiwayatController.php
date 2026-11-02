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
        $query = DB::table('activity_log')
            ->join('users', 'users.user_id', '=', 'activity_log.causer_id')
            ->join('roles', 'roles.role_id', '=', 'users.user_role_id')
            ->select(
                'users.user_nama',
                'roles.role_name',
                'activity_log.description',
                'activity_log.created_at',
                'activity_log.properties'
            )
            ->orderByDesc('activity_log.created_at');

        if (!$this->isSuperAdmin()) {
            $query->where('activity_log.causer_id', Auth::id());
        }

        $operation = $query->get();
        return $this->response($operation);
    }

    public function destroy($id){
        if (!$this->isSuperAdmin()) {
            return response()->json([
                'status' => false,
                'message' => 'Hanya Super Admin yang dapat mereset riwayat.',
            ], 403);
        }

        try {
            DB::table('activity_log')->delete();
            $operation = 1;
            return $this->responseDelete(1);
        } catch (\Exception $e) {
            return $this->responseDelete($e->getMessage());
        }
    }

    private function isSuperAdmin(): bool
    {
        $user = User::with('role')->find(Auth::id());

        if (!$user || !$user->role) {
            return false;
        }

        $roleName = strtolower(preg_replace('/[^a-z0-9]/i', '', $user->role->role_name));

        return in_array($roleName, ['admin', 'superadmin'], true);
    }
}
