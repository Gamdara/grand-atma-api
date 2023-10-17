<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RoomTypeController extends Controller
{
    public function index()
    {
        //
        try{
            $data = RoomType::all();
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

            $RoomType = RoomType::create($validated);

            return $this->baseResponse(
                true,'Insert success',$RoomType, 200
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
    public function show(RoomType $RoomType)
    {
        //
        try{
            return $this->baseResponse(
                true,'get success',$RoomType, 200
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
    public function update(Request $request, RoomType $RoomType)
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

            $RoomType->update($validated);

            return $this->baseResponse(
                true,'Update success',$RoomType, 200
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
    public function destroy(RoomType $RoomType)
    {
        //
        try{
            $RoomType->delete();
            return $this->baseResponse(
                true,'delete success',$RoomType, 200
            );
        }
        catch(\Exception $e ){
            return $this->baseResponse(
                false,$e->getMessage(),null, 400
            );
        }
    }
}
