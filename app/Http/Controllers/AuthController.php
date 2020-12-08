<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\User;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function login(Request $request){
        try {
          $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|string',
          ]);
  
          $user = User::where('username', $request->username)->first();
          if (!$user) {
              return $this->errorResponse('Username does not exist', 400);
          }
  
          if (Hash::check($request->password, $user->password)) {
              return $this->successResponse($user);  
          }
          
          return $this->errorResponse('Password or username wrong', 400);
  
        } catch (\Exception $e) {
          return $this->errorResponse($e->getMessage(), 500);
        }
      }

      public function register(Request $request){
        try {
          $input = $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|string',
            'name' => 'required|string'
          ]);
  
  
          $user = new User();
          $user->username = $input['username'];
          $user->password =  Hash::make($input['password']);
          $user->name = $input['name'];
          $user->save();
  

          return $this->successResponse($user); 

        } catch (\Exception $e) {
          return $this->errorResponse($e->getMessage(), 500);
        }
        
    }


    //
}
