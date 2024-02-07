<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function response($data=''){
        if($data){
            $operation= array(
                'status' => true,
                'message'=>'Success To Load Data',
                'total' => is_array($data) || is_object($data)?count($data):1,
                'data'=>$data
            );
        } else{
            $operation= array(
                'status' => false,
                'message'=> 'Failed To Load Data',
            );
        }

        return $operation;
    }

    public function responseFirst($data=''){
        if($data){
            $operation= array(
                'status' => true,
                'message'=>'Success To Load Data',
                'data'=>$data
            );
        } else{
            $operation= array(
                'status' => false,
                'message'=> 'Failed To Load Data',
            );
        }

        return $operation;
    }

    public function responseCreate($data='',$show=false){
        if(isset($data->wasRecentlyCreated) || isset($data['success'])){
            $operation= array(
                'status' => true,
                'message'=>'Success To Save Data',
            );

            if($show){
                $operation['data'] = $data;
            }
        } else{
            $operation= array(
                'status' => false,
                'message'=> $show?$data:'Failed To Save Data',
            );
        }

        return $operation;
    }

    public function responseUpdate($data='',$show=false){
        if($data==1){
            $operation= array(
                'status' => true,
                'message'=>'Success To Update Data',
            );

        } else{
            $operation= array(
                'status' => false,
                'message'=> $show?$data:'Failed To Update Data',
            );
        }

        return $operation;
    }

    public function responseDelete($data='',$show=false){
        if($data==1 || isset($data['success'])){
            $operation= array(
                'status' => true,
                'message'=>'Success To Delete Data',
            );

        } else{
            $operation= array(
                'status' => false,
                'message'=> $show?$data:'Failed To Delete Data',
            );
        }

        return $operation;
    }
}
