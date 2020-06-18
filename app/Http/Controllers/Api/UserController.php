<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\User;
use Auth;

class UserController extends Controller
{
    public function login(Request $request){
    	 $data['email'] = $request->input('email');
    	 $data['password'] =  $request->input('password');

    	  if (Auth::attempt(array('email' => $data['email'], 'password' => $data['password']))){

    	  	$accessToken =  str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data['email'].$data['password']));
             return response()->json(['msg'=>"successfully login ","status"=>true,'accesstoken'=>$accessToken],200);
            }
            else {        
             return response()->json(['msg'=>"something wrong with this api ","status"=>false],400);
            }
    
    }




}
