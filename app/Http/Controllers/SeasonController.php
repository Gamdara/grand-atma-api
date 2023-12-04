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
                'discount_type' => 'required',
                'start_date' => 'required|date|after:2 months',
                'end_date' => 'required|date|after:start_date',
            ]);
            $dates = [$validated['start_date'], $validated['end_date']];

            $existingSeason = Season::where(function ($query) use($dates) {
                $query->where(function($query)use($dates){
                    $query->whereBetween('start_date',$dates)
                    ->orWhereBetween('end_date',$dates);
                })->orWhere(function($query)use($dates){
                    $query->whereDate('start_date','<=',$dates[0])
                    ->whereDate('end_date','>=',$dates[1]);
                });
            })->count();

            if($existingSeason > 0){
                throw ValidationException::withMessages([
                    'start_date' => ['Sudah ada season di tanggal terkait'],
                ]);
            }


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
                'discount_type' => 'required',
                'start_date' => 'required|date|after_or_equal:'.$Season->start_date,
                'end_date' => 'required|date|after:start_date',
            ]);

            $dates = [$validated['start_date'], $validated['end_date']];

            $existingSeason = Season::where(function ($query) use($dates) {
                $query->where(function($query)use($dates){
                    $query->whereBetween('start_date',$dates)
                    ->orWhereBetween('end_date',$dates);
                })->orWhere(function($query)use($dates){
                    $query->whereDate('start_date','<=',$dates[0])
                    ->whereDate('end_date','>=',$dates[1]);
                });
            })->whereNot('season_id',$Season->season_id)->count();

            if($existingSeason > 0){
                throw ValidationException::withMessages([
                    'start_date' => ['Sudah ada season di tanggal terkait'],
                ]);
            }

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
                'discount_amount' => 'required|integer',
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
                'discount_amount' => 'required|integer',
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
