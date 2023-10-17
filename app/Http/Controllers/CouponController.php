<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try{
            $data = Coupon::all();
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
                'code' => 'required',
                'discount_type' => 'required',
                'discount_amount' => 'required|integer|gt:0',
                'start_date' => 'required|date',
                'end_date' => 'required|after:start_date',
                'is_valid' => 'required|boolean'
            ]);

            $coupon = Coupon::create($validated);

            return $this->baseResponse(
                true,'Insert success',$coupon, 200
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
    public function show(Coupon $coupon)
    {
        //
        try{
            return $this->baseResponse(
                true,'get success',$coupon, 200
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
    public function update(Request $request, Coupon $coupon)
    {
        //
        try{
            $validated = $request->validate([
                'code' => 'required',
                'discount_type' => 'required',
                'discount_amount' => 'required|integer|gt:0',
                'start_date' => 'required|date',
                'end_date' => 'required|after:start_date',
                'is_valid' => 'required|boolean'
            ]);

            $coupon->update($validated);

            return $this->baseResponse(
                true,'Update success',$coupon, 200
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
    public function destroy(Coupon $coupon)
    {
        //
        try{
            $coupon->delete();
            return $this->baseResponse(
                true,'delete success',$coupon, 200
            );
        }
        catch(\Exception $e ){
            return $this->baseResponse(
                false,$e->getMessage(),null, 400
            );
        }
    }
}
