<?php

namespace App\Http\Controllers;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use HttpResponses;

    public function login(LoginUserRequest $request){
        // return "this is my login page";
        $request->validated($request->all());

        if(!Auth::attempt([$request->only('email', 'password')])){
                return $this->error(' ', ' Credential do not match', 401);
        }

        $user = User::where('email', $request->email)->first();

        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API Token of ' . $user->name)->plainTextToken
        ]);

    }

    public function register(StoreUserRequest $request){

        $request->validated($request->all());

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // return response()->json("this is my register page");
        return $this->success([
            'user' => $user,
            'token' => $user->createToken("API Token of " . $user->name)->plainTextToken
        ]);
    }

    public function logout(){
        // return response()->json("this is my logout page");

        Auth::user()->currentAccessToken()->delete();
        return $this->success([
            'message' => 'You have successfully been logged out and your token has been deleted'
        ]);
    }
}
