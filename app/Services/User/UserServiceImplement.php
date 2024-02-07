<?php

namespace App\Services\User;

use LaravelEasyRepository\Service;
use App\Repositories\User\UserRepository;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserServiceImplement extends Service implements UserService{

     /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
     protected $mainRepository;

    public function __construct(UserRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    public function index(){
      return $this->mainRepository->index();
    }

    public function store($data){
      $data->merge(['user_status'=>1,'password'=>Hash::make($data->password)]);
      $this->validation('store',$data);
      return $this->mainRepository->create($data->all());
    }

    public function validation($type,$request){
      if($type=='store'){
        $check = $request->validate([
            'user_username' => 'required|unique:users',
            'user_email' => 'required|unique:users',
            'password' => 'required',
        ]);
      } else{
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
      }
    }
}
