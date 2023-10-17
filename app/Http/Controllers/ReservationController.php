<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        //
        try{
            $data = Reservation::all();
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

            $Reservation = Reservation::create($validated);

            return $this->baseResponse(
                true,'Insert success',$Reservation, 200
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
    public function show(Reservation $Reservation)
    {
        //
        try{
            return $this->baseResponse(
                true,'get success',$Reservation, 200
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
    public function update(Request $request, Reservation $Reservation)
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

            $Reservation->update($validated);

            return $this->baseResponse(
                true,'Update success',$Reservation, 200
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
    public function destroy(Reservation $Reservation)
    {
        //
        try{
            $Reservation->delete();
            return $this->baseResponse(
                true,'delete success',$Reservation, 200
            );
        }
        catch(\Exception $e ){
            return $this->baseResponse(
                false,$e->getMessage(),null, 400
            );
        }
    }
}
