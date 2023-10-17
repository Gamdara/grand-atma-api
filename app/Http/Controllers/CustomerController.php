<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Controllers\Controller;
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
            $data = Customer::all();
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        try{
            $validated = $request->validate([
                'user_id' => 'required|users',
                'name' => 'required',
                'no_identity' => 'required',
                'institution' => 'required',
                'phone_number' => 'required',
                'address' => 'required',
            ]);

            $Customer = Customer::create($validated);

            return $this->baseResponse(
                true,'Insert success',$Customer, 200
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
                true,'get success',$Customer, 200
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
            $validated = $request->validate([
                'user_id' => 'required|users',
                'name' => 'required',
                'no_identity' => 'required',
                'institution' => 'required',
                'phone_number' => 'required',
                'address' => 'required',
            ]);

            $Customer->update($validated);

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
