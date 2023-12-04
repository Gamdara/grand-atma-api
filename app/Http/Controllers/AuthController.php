<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Str;

class AuthController extends Controller
{
    //
    public function login(Request $request){
        try{
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if (! Auth::attempt($request->only('email', 'password'))) {
                throw ValidationException::withMessages([
                    'email' => ['Email/password salah'],
                ]);
            }

            $user = User::where('email', $request->email)->firstOrFail();

            $token = $user->createToken(Str::random(6))->plainTextToken;

            return $this->baseResponse(
                true,'Login Succeess',['user' => User::with('role','customer')->find($request->user()->user_id), 'token' => $token], 200
            );
        }
        catch(ValidationException $e ){
            return $this->baseResponse(
                false,$e->getMessage(),$e->errors(), 400
            );
        }
        catch(\Exception $e ){
            return $this->baseResponse(
                false,$e->getMessage() ,null, 400
            );
        }
    }

    public function register(Request $request){
        try{
            $request->validate([
                'name' => 'required',
                'email' => 'required|email',
                'phone_number' => 'required|min:10',
                'no_identity' => 'required',
                'password' => 'required',
                'address' => 'required'
            ]);
            if(User::where('email', $request->email)->first())
            throw ValidationException::withMessages([
                'email' => ['Email has been taken'],
            ]);
            $request['password'] = Hash::make($request['password']);
            $request['role_id'] = Role::where('name', 'customer')->first()->role_id;

            $user = User::create($request->only('name','email','password','role_id'));
            $user->customer()->create($request->only('phone_number','no_identity','address'));

            return $this->baseResponse(
                true,'Register success',$user, 200
            );
        }
        catch(ValidationException $e ){
            return $this->baseResponse(
                false,$e->getMessage(),$e->errors(), 400
            );
        }
        catch(\Exception $e ){
            return $this->baseResponse(
                false,$e->getMessage() ,null, 400
            );
        }
    }

    public function profile(Request $request){
        try{
            return $this->baseResponse(
                true,'Get profile success',User::with('role','customer')->find($request->user()->user_id), 200
            );
        }
        catch(ValidationException $e ){
            return $this->baseResponse(
                false,$e->getMessage(),$e->errors(), 400
            );
        }
        catch(\Exception $e ){
            return $this->baseResponse(
                false,$e->getMessage() ,null, 400
            );
        }
    }

    public function doUpdateCustomerProfile(Request $request){
        try{
            $request->validate([
                'name' => 'required',
                'phone_number' => 'required',
                'no_identity' => 'required',
                'email' => 'required|email|unique:users,email,'.$request->user()->user_id.',user_id',
                'address' => 'required'
            ]);

            $request->user()->update($request->only('name','email'));
            $request->user()->customer()->update($request->only('phone_number','address','no_identity'));


            return $this->baseResponse(
                true,'Update profile success',User::with('role','customer')->find($request->user()->user_id), 200
            );
        }
        catch(ValidationException $e ){
            return $this->baseResponse(
                false,$e->getMessage(),$e->errors(), 400
            );
        }
        catch(\Exception $e ){
            return $this->baseResponse(
                false,$e->getMessage() ,null, 400
            );
        }
    }

    public function doSendOTP(Request $request){
        try{
            $request->validate(['email' => 'required|email']);

            $status = Password::sendResetLink(
                $request->only('email')
            );
            return $this->baseResponse(
                true,'Update profile success',User::with('role','customer')->find($request->user()->user_id), 200
            );
        }
        catch(\Exception $e ){
            return $this->baseResponse(
                false,$e->getMessage() ,null, 400
            );
        }
    }

    public function doUpdateUserProfile(Request $request){
        try{
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email,'.$request->user()->user_id.',user_id',
            ]);

            $request->user()->update($request->only('name','email'));

            return $this->baseResponse(
                true,'Update profile success',User::with('role','customer')->find($request->user()->user_id), 200
            );
        }
        catch(ValidationException $e ){
            return $this->baseResponse(
                false,$e->getMessage(),$e->errors(), 400
            );
        }
        catch(\Exception $e ){
            return $this->baseResponse(
                false,$e->getMessage() ,null, 400
            );
        }
    }

    public function doChangePassword(Request $request){
        try{
            $request->validate([
                'password' => 'required|confirmed',
                'old_password' => 'required',
            ]);

            if(!Hash::check($request->old_password, $request->user()->password)){
                throw ValidationException::withMessages([
                    'old_password' => ['Password does not match'],
                ]);
            }

            $request->user()->update(['password' => Hash::make($request->password)]);

            return $this->baseResponse(
                true,'Update password success',User::with('role','customer')->find($request->user()->user_id), 200
            );
        }
        catch(ValidationException $e ){
            return $this->baseResponse(
                false,$e->getMessage(),$e->errors(), 400
            );
        }
        catch(\Exception $e ){
            return $this->baseResponse(
                false,$e->getMessage() ,null, 400
            );
        }
    }

    public function doResetPassword(Request $request){
        try{
            $request->validate([
                'email' => 'required',
                'phone_number' => 'required|required',
                'name' => 'required|required',
            ]);

            $user = User::where('email',$request->email)->where('name',$request->name)->first();
            if(!$user){
                throw ValidationException::withMessages([
                    'name' => ['Kredensial salah'],
                ]);
            }
            if(!$user->customer || $user->customer->phone_number != $request->phone_number){
                throw ValidationException::withMessages([
                    'name' => ['Kredensial salah'],
                ]);
            }

            $user->update(['password' => Hash::make($user->customer->no_identity)]);

            return $this->baseResponse(
                true,'Reset password success',$user, 200
            );
        }
        catch(ValidationException $e ){
            return $this->baseResponse(
                false,$e->getMessage(),$e->errors(), 400
            );
        }
        catch(\Exception $e ){
            return $this->baseResponse(
                false,$e->getMessage() ,null, 400
            );
        }
    }

    public function logout(Request $request){
        try{
            $request->user()->currentAccessToken()->delete();

            return $this->baseResponse(
                true,'Logout success',$request->user(), 200
            );
        }
        catch(ValidationException $e ){
            return $this->baseResponse(
                false,$e->getMessage(),$e->errors(), 400
            );
        }
        catch(\Exception $e ){
            return $this->baseResponse(
                false,$e->getMessage() ,null, 400
            );
        }
    }
}
