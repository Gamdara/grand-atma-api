<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ReservationController extends Controller
{
    public function index()
    {
        //
        try{
            $data = Reservation::with('customer','customer.user')->get();
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

    public function getReservationGroup()
    {
        //
        try{
            $data = Reservation::with('customer','customer.user')->where('type','group')->get();
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
    public function addReservation(Request $request)
    {
        //
        try{
            $validatedRes = $request->validate([
                'customer_id' => 'required',
                'adults' => 'required|integer',
                'kids' => 'required|integer',
                'type' => 'required',
                'start_date' => 'required|date',
                'pic' => 'nullable|integer',
                // 'start_date' => 'required|date|after:2 weeks',
                'end_date' => 'required|after:start_date'
            ]);
            $arr = $request->validate([
                'rooms' => 'required|array',
                'services' => 'nullable|array',
            ]);

            $validatedRes['status'] = "pending";
            $validatedRes['booking_id'] = ($validatedRes['type'] == "group" ? "G" : "P") . date('dmy')."-". Reservation::count() + 1 ;

            $reservation = Reservation::create($validatedRes);
            $dates = [$validatedRes['start_date'], $validatedRes['end_date']];

            $invalidRoom = Reservation::whereNot('status','cancelled')->where(function ($query) use($dates) {
                $query->where(function($query)use($dates){
                    $query->whereBetween('start_date',$dates)
                    ->orWhereBetween('end_date',$dates);
                })->orWhere(function($query)use($dates){
                    $query->whereDate('start_date','<=',$dates[0])
                    ->whereDate('end_date','>=',$dates[1]);
                });
            })->join('reservation_rooms', 'reservations.reservation_id','=','reservation_rooms.reservation_id')
            ->join('reservation_room_details', 'reservation_room_details.reservation_room_id','=','reservation_rooms.reservation_room_id')
            ->join('rooms', 'reservation_room_details.room_id','=','rooms.room_id')->get()->pluck('room_id');

            foreach ($arr['rooms'] as $key => $value) {
                $rr = $reservation->reservationRooms()->create(['room_type_id' => $value['room_type_id'], 'fare' => $value['fare']]);

                foreach ($value['rooms'] as $rt => $room) {
                    $validRoom = Room::where('is_smoking',$room['is_smoking'])->where('bed_type',$room['bed_type'])->whereNotIn('room_id',$invalidRoom)->limit($room['count'])->get();
                    $rr->rooms()->attach($validRoom);
                }
            }

            if( !empty($arr['services']))
                foreach ($arr['services'] as $key => $value) {
                    unset($value['name']);
                    $reservation->services()->attach($value['service_id'], $value);
                }

            return $this->baseResponse(
                true,'Insert success',$invalidRoom, 200
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
                true,'get success',Reservation::with('customer','customer.user','reservationExtend','reservationRooms','reservationRooms.roomType','reservationRooms.rooms','services','transaction','frontOffice','pic')->find($Reservation->reservation_id), 200
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
