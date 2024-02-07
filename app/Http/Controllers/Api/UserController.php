<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
// use App\Services\User\UserService;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{    
    // protected $UserService;

    // public function __construct(UserService $UserService){
    //     $this->UserService = $UserService;
    // }

    public function index(){
        // $operation = $this->UserService->index();
        $operation = User::with('role')->where('user_status',1)->get();
        return $this->response($operation);
    }

    public function show($id){
        // $operation = $this->UserService->find($id);
        $operation = User::where('user_id',$id)->get();
        return $this->responseFirst($operation);
    }

    public function store(Request $request){
        try {            
            // $operation = $this->UserService->store($request);
            $check = $request->validate([
                'user_username' => 'required|unique:users',
                'user_email' => 'required|unique:users',
                'password' => 'required',
            ]);

            $data = $request->all();
            $data['password'] = Hash::make($data['password']);
            $data['user_status'] = 1;
            $operation = User::create($data);
            return $this->responseCreate($operation);
        } catch (\Exception $e) {
            return $this->responseCreate($e->getMessage(),true);
        }
    }

    public function update(Request $request, $id){
        try {
            $check = $request->validate([
                'user_username' => [
                    'required',
                    Rule::unique('users')->ignore($id, 'user_id'),
                ],
                'user_email' => [
                    'required',
                    Rule::unique('users')->ignore($id, 'user_id'),
                ],
            ]);

            $data = $request->all();
            unset($data['_token']);
            if(!isset($data['password']))unset($data['password']);
            else $data['password'] = Hash::make($data['password']);
            
            $operation = User::where('user_id',$id)->update($data);
            return $this->responseUpdate($operation);
        } catch (\Exception $e) {
            return $this->responseUpdate($e->getMessage(),true);
        }
    }

    public function updatealternate(Request $request){
        try {
            $data = $request->all();
            $id = $data['user_id'];
            $check = $request->validate([
                'user_username' => [
                    'required',
                    Rule::unique('users')->ignore($id, 'user_id'),
                ],
                'user_email' => [
                    'required',
                    Rule::unique('users')->ignore($id, 'user_id'),
                ],
                'file' => ['sometimes','mimes:jpeg,png,jpg,gif,svg'],
            ]);

            unset($data['_token']);

            $data['user_foto']= 'foto_user'.time().'.'.request()->file->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('user_foto/', $request->file('file'), $data['user_foto']);
            $request->file('file')->move(public_path('storage/user_foto'), $data['user_foto']);
            if(!isset($data['password']))unset($data['password']);
            else $data['password'] = Hash::make($data['password']);
            unset($data['file']);
            $operation = User::where('user_id',$id)->update($data);
            return $this->responseUpdate($operation);
        } catch (\Exception $e) {
            return $this->responseUpdate($e->getMessage(),true);
        }
    }

    public function destroy($id){
        try {            
            $data['user_status'] = 0;
            $operation = User::where('user_id',$id)->update($data);
            return $this->responseDelete($operation);
        } catch (\Exception $e) {
            return $this->responseDelete($e->getMessage());
        }
    }
}
