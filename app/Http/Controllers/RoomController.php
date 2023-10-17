<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RoomController extends Controller
{
    public function index()
    {
        //
        try{
            $data = Room::all();
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
                'img' => 'required',
                'fare' => 'required|integer|gt:0',
                'bed_options' => 'required',
                'description' => 'required',
                'details' => 'required',
            ]);

            $Room = Room::create($validated);

            return $this->baseResponse(
                true,'Insert success',$Room, 200
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
    public function show(Room $Room)
    {
        //
        try{
            return $this->baseResponse(
                true,'get success',$Room, 200
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
    public function update(Request $request, Room $Room)
    {
        //
        try{
            $validated = $request->validate([
                'name' => 'required',
                'img' => 'required',
                'fare' => 'required|integer|gt:0',
                'bed_options' => 'required',
                'description' => 'required',
                'details' => 'required',
            ]);

            $Room->update($validated);

            return $this->baseResponse(
                true,'Update success',$Room, 200
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
    public function destroy(Room $Room)
    {
        //
        try{
            $Room->delete();
            return $this->baseResponse(
                true,'delete success',$Room, 200
            );
        }
        catch(\Exception $e ){
            return $this->baseResponse(
                false,$e->getMessage(),null, 400
            );
        }
    }
}
