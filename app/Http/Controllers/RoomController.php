<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\ReservationRoom;
use App\Models\Room;
use App\Models\RoomType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RoomController extends Controller
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
    public function show(RoomType $room)
    {
        //
        try{
            return $this->baseResponse(
                true,'get success',RoomType::with('room')->find($room->room_type_id), 200
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
    public function update(Request $request, RoomType $room)
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

            $room->update($validated);

            return $this->baseResponse(
                true,'Update success',$room, 200
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
    public function destroy(RoomType $room)
    {
        //
        try{
            $room->delete();
            return $this->baseResponse(
                true,'delete success',$room, 200
            );
        }
        catch(\Exception $e ){
            return $this->baseResponse(
                false,$e->getMessage(),null, 400
            );
        }
    }

    public function addRoom(Request $request, RoomType $Room)
    {
        //
        try{
            $validated = $request->validate([
                'number' => 'required|integer',
                'bed_type' => 'required',
                'is_smoking' => 'required|boolean',
                'capacity' => 'required|integer',
            ]);
            $validated["room_type_id"] = $Room->room_type_id;
            $Room->room()->insert( $validated );

            return $this->baseResponse(
                true,'Add Room success',$Room->room(), 200
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

    public function updateRoom(Request $request, Room $Room)
    {
        //
        try{
            $validated = $request->validate([
                'number' => 'required|integer',
                'bed_type' => 'required',
                'is_smoking' => 'required|boolean',
                'capacity' => 'required|integer',
            ]);

            $Room->update( $validated );

            return $this->baseResponse(
                true,'update Room success',$Room, 200
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

    public function deleteRoom(Request $request, Room $Room)
    {
        //
        try{

            $Room->delete();

            return $this->baseResponse(
                true,'Sync fare success',$Room, 200
            );
        }
        catch(\Exception $e ){
            return $this->baseResponse(
                false,$e->getMessage(),null, 400
            );
        }
    }

    public function getRoomAvailability(Request $request){
        try{
            $validated = $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
            ]);

            $dates = [$validated['start_date'], $validated['end_date']];

            $reservation = Reservation::where(function($query)use($dates){
                $query->whereBetween('start_date',$dates)
                ->orWhereBetween('end_date',$dates);
            })->orWhere(function($query)use($validated){
                $query->whereDate('start_date','<=',$validated['start_date'])
                ->whereDate('end_date','>=',$validated['end_date']);
            })->whereNot('status','cancelled')->with('reservationRooms')->get();

            $roomTypes = RoomType::with([])->get();
            foreach($roomTypes as $roomType){
                $roomType->available_rooms = [];
                $roomType->season = $roomType->season($validated['start_date'],$validated['end_date'])->get();
                $bedType = explode(',',$roomType->bed_options);
                foreach ($bedType as $type) {
                    $roomType->available_rooms = array_merge($roomType->available_rooms,
                        [[
                            'is_smoking' => true, 'bed_type' => $type, 'count' => $roomType->room()->where('bed_type',$type)->where('is_smoking',true)->count()
                        ]]
                    );
                    $roomType->available_rooms = array_merge($roomType->available_rooms,
                        [[
                            'is_smoking' => false, 'bed_type' => $type, 'count' => $roomType->room()->where('bed_type',$type)->where('is_smoking',false)->count()
                        ]]
                    );
                }
            }

            $reservation->mapWithKeys(function (Reservation $res,$item) use(&$roomTypes){
                $res->reservationRooms->mapWithKeys(function (ReservationRoom $reservationRoom) use(&$roomTypes) {
                    $bedType = explode(',',$reservationRoom->roomType->bed_options);
                    $room_type_id = $reservationRoom->roomType->room_type_id;
                    foreach ($bedType as $type) {
                        $tempAvailable_rooms = $roomTypes->find($room_type_id)->available_rooms;
                        foreach($roomTypes->find($room_type_id)->available_rooms as $available_room) {

                            if($available_room['is_smoking']  && $available_room['bed_type'] == $type){
                                $newSmoking = [[
                                    'is_smoking' => true,
                                    'bed_type' => $type,
                                    'count' => $available_room['count'] - $reservationRoom->rooms()->where('bed_type',$type)->where('is_smoking',true)->count()
                                ]];
                                $tempAvailable_rooms = array_filter($tempAvailable_rooms, function($obj)use($type){
                                    if($obj['is_smoking'] && $obj['bed_type'] == $type) return false;
                                    return true;
                                });
                                $tempAvailable_rooms = array_merge($tempAvailable_rooms, $newSmoking);

                            }
                            if(!$available_room['is_smoking'] && $available_room['bed_type'] == $type){
                                $newNonSmoking = [[
                                    'is_smoking' => false,
                                    'bed_type' => $type,
                                    'count' => $available_room['count'] - $reservationRoom->rooms()->where('bed_type',$type)->where('is_smoking',false)->count()
                                ]];
                                $tempAvailable_rooms = array_filter($tempAvailable_rooms, function($obj)use($type){
                                    if(!$obj['is_smoking'] && $obj['bed_type'] == $type) return false;
                                    return true;
                                });
                                $tempAvailable_rooms = array_merge($tempAvailable_rooms, $newNonSmoking);
                            }

                        }
                        $roomTypes->find($room_type_id)->available_rooms = $tempAvailable_rooms;
                    }
                    return [];
                });
                return [];
            });



            return $this->baseResponse(
                true,'get success',$roomTypes, 200
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
