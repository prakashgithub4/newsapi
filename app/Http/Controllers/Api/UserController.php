<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\User;
use Auth;
use Validator;

class UserController extends Controller
{
    public function login(Request $request){
     
    $data = $request->all();
    
   $validation = Validator::make($data,[
            
           'email' => ['required', 'string', 'email'],
           'password' => ['required'],

        ]);
 
    if ($validation->fails()) {
            return response()->json(['error'=>$validation->errors()],400);
        }
       



    	 $data['email'] = $request->input('email');
    	 $data['password'] =  $request->input('password');

    	  if (Auth::attempt(array('email' => $data['email'], 'password' => $data['password']))){


              $user = User::find(Auth::id());
              $accessToken =  str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data['email'].date('y-m-d')));
              $user->login_token = $accessToken;
              $user->save();
              return response()->json(['msg'=>"successfully login ","status"=>true,'accesstoken'=>$accessToken],200);
            }
            else {        
              return response()->json(['msg'=>"something wrong with this api ","status"=>false],400);
            }
    
    }
  

      public function register(Request $request){
          $data = $request->all();

       $validation = Validator::make($data,[
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed'],
             'avator' => ['image','mimes:jpeg,png,jpg','max:2048']


        ]);
      
      if ($validation->fails()) {
            return response()->json(['error'=>$validation->errors()],400);
        }
        else{

      if ($request->hasFile('avator')) {
		        
		        $image = $request->file('avator');
		        $data['avatar'] = time().'.'.$image->getClientOriginalExtension();
		        $destinationPath = public_path('uploads/avator/');
		        $image->move($destinationPath,$data['avatar']);
             }


             $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'avatar'=>url('public/uploads/avator/'.$data['avatar']),
            'created_on'=>date('y-m-d h:m:s')
        ]);
              return response()->json(['msg'=>'register user successfully','data'=>$user],200);
        }

    }


}
