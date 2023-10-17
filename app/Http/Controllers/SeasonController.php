<?php

namespace App\Http\Controllers;

use App\Models\Season;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SeasonController extends Controller
{
    public function index()
    {
        //
        try{
            $data = Season::with('roomType')->get();
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
                'name' => 'required',
                'unit' => 'required',
                'fare' => 'required|integer|gt:0',
            ]);

            $Season = Season::create($validated);

            return $this->baseResponse(
                true,'Insert success',$Season, 200
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
    public function show(Season $Season)
    {
        //
        try{
            return $this->baseResponse(
                true,'get success',Season::with('roomType')->find($Season->season_id), 200
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
    public function update(Request $request, Season $Season)
    {
        //
        try{
            $validated = $request->validate([
                'name' => 'required',
                'unit' => 'required',
                'fare' => 'required|integer|gt:0',
            ]);

            $Season->update($validated);

            return $this->baseResponse(
                true,'Update success',$Season, 200
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
    public function destroy(Season $Season)
    {
        //
        try{
            $Season->delete();
            return $this->baseResponse(
                true,'delete success',$Season, 200
            );
        }
        catch(\Exception $e ){
            return $this->baseResponse(
                false,$e->getMessage(),null, 400
            );
        }
    }

    public function addFare(Request $request, Season $Season)
    {
        //
        try{
            $validated = $request->validate([
                'room_type_id' => 'required|integer',
                'discount_amount' => 'required|integer|gt:0',
            ]);

            $Season->roomType()->attach(
                [$validated['room_type_id'] => ['discount_amount' => $validated['discount_amount']]]
            );

            return $this->baseResponse(
                true,'Add fare success',$Season->roomType(), 200
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

    public function updateFare(Request $request, Season $Season)
    {
        //
        try{
            $validated = $request->validate([
                'room_type_id' => 'required|integer',
                'discount_amount' => 'required|integer|gt:0',
            ]);

            $Season->roomType()->updateExistingPivot($validated['room_type_id'],
                ['discount_amount' => $validated['discount_amount']]
            );

            return $this->baseResponse(
                true,'update fare success',$Season->roomType(), 200
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

    public function deleteFare(Request $request, Season $Season)
    {
        //
        try{
            $validated = $request->validate([
                'room_type_id' => 'required|integer',
            ]);

            $Season->roomType()->detach($validated['room_type_id']);

            return $this->baseResponse(
                true,'Sync fare success',$Season->roomType(), 200
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


}
