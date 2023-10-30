<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try{
            $data = Customer::with('user')->get();
            return $this->baseResponse(
                true,'get success',$data, 200
            );
        }
        catch(\Exception $e ){
            return $this->baseResponse(
                false,$e->getMessage(),null, 400
            );
        }
    }

    public function getCustomerReservationGroup(Customer $Customer)
    {
        //
        try{
            return $this->baseResponse(
                true,'get success',$Customer->reservation()->where('type','group')->get(), 200
            );
        }
        catch(\Exception $e ){
            return $this->baseResponse(
                false,$e->getMessage(),null, 400
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        try{
            $request->validate([
                'name' => 'required',
                'email' => 'required|email',
                'phone_number' => 'required|min:10',
                'institution' => 'required',
                'no_identity' => 'required',
                'password' => 'required',
                'address' => 'required'
            ]);
            $request['password'] = Hash::make($request['password']);
            $request['role_id'] = Role::where('name', 'customer')->first()->role_id;

            $user = User::create($request->only('name','email','password','role_id'));
            $user->customer()->create($request->only('phone_number','no_identity','address','institution'));

            return $this->baseResponse(
                true,'Insert success',$user, 200
            );
        }
        catch(ValidationException $e ){
            return $this->baseResponse(
                false,$e->getMessage(),$e->errors(), 400
            );
        }
        catch(\Exception $e ){
            return $this->baseResponse(
                false,$e->getMessage() ,[], 400
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $Customer)
    {
        //
        try{
            return $this->baseResponse(
                true,'get success', Customer::with('user')->find($Customer->customer_id) , 200
            );
        }
        catch(\Exception $e ){
            return $this->baseResponse(
                false,$e->getMessage(),null, 400
        );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $Customer)
    {
        //
        try{
            $request->validate([
                'name' => 'required',
                'email' => 'required|email',
                'phone_number' => 'required|min:10',
                'institution' => 'required',
                'no_identity' => 'required',
                'password' => 'required_if:change_pass,true',
                'change_pass' => 'required|boolean',
                'address' => 'required'
            ]);

            if($request['change_pass'] == true)
                $request['password'] = Hash::make($request['password']);

            $Customer->update($request->only('phone_number','no_identity','address','institution'));

            $userData = !$request['change_pass']
                        ? $request->only('email','name')
                        : $request->only('email','name','password');

            $Customer->user()->update($userData);

            return $this->baseResponse(
                true,'update success',$Customer, 200
            );
        }
        catch(ValidationException $e ){
            return $this->baseResponse(
                false,$e->getMessage(),$e->errors(), 400
            );
        }
        catch(\Exception $e ){
            return $this->baseResponse(
                false,$e->getMessage(),null, 400
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $Customer)
    {
        //
        try{
            $Customer->delete();
            return $this->baseResponse(
                true,'delete success',$Customer, 200
            );
        }
        catch(\Exception $e ){
            return $this->baseResponse(
                false,$e->getMessage(),null, 400
            );
        }
    }
}
