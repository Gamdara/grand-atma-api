<?php

namespace App\Http\Controllers;

use App\Mail\CustomerMail;
use App\Models\Transaction;
use Illuminate\Support\Facades\Mail;
use App\Models\Reservation;
use App\Models\ReservationExtend;
use App\Http\Controllers\Controller;
use App\Models\Service;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CheckInOutController extends Controller
{
    public function getCheckinAble(Request $req){
        try{
            $data = Reservation::with('customer','customer.user')->whereNotIn('status',['pending','cancelled','settled','checked'])->get();
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

    public function getCheckoutAble(Request $req){
        try{
            $data = Reservation::with('customer','customer.user')->whereIn('status',['checked-in','settled'])->get();
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

    public function checkIn(Reservation $reservation){
        try{
            $reservation->update([
                'status' => 'checked-in',
                'check_in' => now()
            ]);
            return $this->baseResponse(
                true,'get success',$reservation, 200
            );
        }
        catch(\Exception $e ){
            return $this->baseResponse(
                false,$e->getMessage(),null, 400
            );
        }
    }

    public function checkOut(Request $req, Reservation $reservation){
        try{

            $reservation->transaction()->create([
                'type' => 'settle',
                'amount' => $req['total'],
                'no_invoice' => ($reservation->type == "personal" ? 'P' : 'G').date('dmy').'-'.Transaction::count(),

            ]);
            $reservation->update(['status' => 'settled', 'user_id' => $req["user_id"], 'check_out' => now()]);
            $res = Reservation::with('customer','customer.user','reservationExtend','reservationRooms','reservationRooms.roomType','reservationRooms.rooms','services','transaction','frontOffice','sales')->find($reservation->reservation_id);


            $html = view('emails.nota')->with('res', $res)->render();
            // return $html;

            $pdf = Pdf::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadHTML($html);
            $pdf->getDomPDF()->setHttpContext(
                stream_context_create([
                    'ssl' => [
                        'allow_self_signed'=> TRUE,
                        'verify_peer' => FALSE,
                        'verify_peer_name' => FALSE,
                    ]
                ])
            );

            Mail::to('uiop7703@gmail.com')->send(new CustomerMail($pdf->output()));
            return $this->baseResponse(
                true,'confirmed success',
                $res
                , 200
            );
        }
        catch(\Exception $e ){
            return $this->baseResponse(
                false,$e->getMessage(),'', 400
            );
        }
    }

    public function getNota(Reservation $reservation){
        try{

            $res = Reservation::with('customer','customer.user','reservationExtend','reservationRooms','reservationRooms.roomType','reservationRooms.rooms','services','transaction','frontOffice','sales')->find($reservation->reservation_id);

            $html = view('emails.nota')->with('res', $res)->render();

            $pdf = Pdf::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadHTML($html);
            $pdf->getDomPDF()->setHttpContext(
                stream_context_create([
                    'ssl' => [
                        'allow_self_signed'=> TRUE,
                        'verify_peer' => FALSE,
                        'verify_peer_name' => FALSE,
                    ]
                ])
            );

            return $pdf->stream();
        }
        catch(\Exception $e ){
            return $this->baseResponse(
                false,$e->getMessage(),'', 400
            );
        }
    }

    public function addLayanan(Request $request, Reservation $reservation){
        try{
            $validatedRes = $request->validate([
                'service_id' => 'required',
                'amount' => 'required|integer',
                'fare' => 'required|integer',
            ]);
            // dd($validatedRes);
            $old = $reservation->services()->where('services.service_id',$validatedRes["service_id"])->first();
            if($old) {
                $validatedRes["amount"] += $old["pivot"]["amount"];
                $reservation->services()->updateExistingPivot($validatedRes['service_id'], $validatedRes);
            }
            else
            $reservation->services()->attach($validatedRes['service_id'], $validatedRes);

            return $this->baseResponse(
                true,'get success',$old, 200
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
