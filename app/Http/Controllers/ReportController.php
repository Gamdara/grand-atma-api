<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Reservation;
use App\Models\RoomType;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public $month = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    public function getCustomerBaru(Request $req){
        try{
            $val = $req->validate([
                'year' => 'required|integer'
            ]);
            $data = [];
            foreach ($this->month as $i => $m) {
                $data[] = [
                    'month' => $m,
                    'count' => Customer::whereYear('created_at',$val["year"])->whereMonth('created_at',$i + 1)->count()
                ];
            }

            $html = view('report.customer')->with('data', [ 'year' => $val["year"], 'table' => $data])->render();

            return $this->baseResponse(
                true,'get success',[
                    'html' => $html,
                    'data' => $data
                ], 200
            );
        }
        catch(\Exception $e ){
            return $this->baseResponse(
                false,$e->getMessage(),null, 400
            );
        }
    }

    public function cetakCustomerBaru(Request $req){
        try{
            $val = $req->validate([
                'year' => 'required|integer'
            ]);
            $data = [];
            foreach ($this->month as $i => $m) {
                $data[] = [
                    'month' => $m,
                    'count' => Customer::whereYear('created_at',$val["year"])->whereMonth('created_at',$i + 1)->count()
                ];
            }

            $html = view('report.customer')->with('data', [ 'year' => $val["year"], 'table' => $data])->render();

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
                false,$e->getMessage(),null, 400
            );
        }
    }


    public function getPendapatan(Request $req){
        try{
            $val = $req->validate([
                'year' => 'required|integer'
            ]);
            $data = [];
            foreach ($this->month as $i => $m) {
                $group = Transaction::whereYear('created_at',$val["year"])->whereMonth('created_at',$i + 1)->whereHas('reservation', function($r){
                    return $r->where('type','group');
                })->sum('amount');
                $personal = Transaction::whereYear('created_at',$val["year"])->whereMonth('created_at',$i + 1)->whereHas('reservation', function($r){
                    return $r->where('type','personal');
                })->sum('amount');
                $data[] = [
                    'month' => $m,
                    'group' => $group,
                    'personal' => $personal,
                    'total' => $group + $personal
                ];
            }

            $html = view('report.pendapatan')->with('data', [ 'year' => $val["year"], 'table' => $data])->render();

            return $this->baseResponse(
                true,'get success',[
                    'html' => $html,
                    'data' => $data
                ], 200
            );
        }
        catch(\Exception $e ){
            return $this->baseResponse(
                false,$e->getMessage(),null, 400
            );
        }
    }

    public function cetakPendapatan(Request $req){
        try{
            $val = $req->validate([
                'year' => 'required|integer'
            ]);
            $data = [];
            foreach ($this->month as $i => $m) {
                $group = Transaction::whereYear('created_at',$val["year"])->whereMonth('created_at',$i + 1)->whereHas('reservation', function($r){
                    return $r->where('type','group');
                })->sum('amount');
                $personal = Transaction::whereYear('created_at',$val["year"])->whereMonth('created_at',$i + 1)->whereHas('reservation', function($r){
                    return $r->where('type','personal');
                })->sum('amount');
                $data[] = [
                    'month' => $m,
                    'group' => $group,
                    'personal' => $personal,
                    'total' => $group + $personal
                ];
            }

            $html = view('report.pendapatan')->with('data', [ 'year' => $val["year"], 'table' => $data])->render();

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
                false,$e->getMessage(),null, 400
            );
        }
    }

    public function getJumlahTamu(Request $req){
        try{
            $val = $req->validate([
                'year' => 'required|integer',
                'month' => 'required|integer',
            ]);
            $jk = RoomType::all();
            $data = [];
            foreach ($jk as $roomType) {
                $group = Reservation::whereMonth('start_date',$val["month"])->whereYear('start_date',$val["year"])
                ->whereHas('reservationRooms',function($rr)use($roomType){
                    return $rr->whereHas('rooms',function($r)use($roomType){
                        return $r->where('room_type_id', $roomType->room_type_id);
                    });
                })
                ->where('type','group')->count();

                $personal = Reservation::whereYear('start_date',$val["year"])->whereMonth('start_date',$val["month"])->whereHas('reservationRooms',function($rr)use($roomType){
                    return $rr->whereHas('rooms',function($r)use($roomType){
                        return $r->where('room_type_id', $roomType->room_type_id);
                    });
                })->where('type','personal')->count();

                $data[] = [
                    'room_type' => $roomType->name,
                    'group' => $group,
                    'personal' => $personal,
                    'total' => $group + $personal
                ];
            }

            $html = view('report.tamu')->with('data', [ 'year' => $val["year"],'month' => $val["month"], 'table' => $data])->render();

            return $this->baseResponse(
                true,'get success',[
                    'html' => $html,
                    'data' => $data
                ], 200
            );
        }
        catch(\Exception $e ){
            return $this->baseResponse(
                false,$e->getMessage(),null, 400
            );
        }
    }

    public function cetakJumlahTamu(Request $req){
        try{
            $val = $req->validate([
                'year' => 'required|integer',
                'month' => 'required|integer',
            ]);
            $jk = RoomType::all();
            $data = [];
            foreach ($jk as $roomType) {
                $group = Reservation::whereYear('created_at',$val["year"])->whereMonth('created_at',$val["month"])
                ->whereHas('reservationRooms',function($rr)use($roomType){
                    return $rr->whereHas('rooms',function($r)use($roomType){
                        return $r->where('room_type_id', $roomType->room_type_id);
                    });
                })
                ->where('type','group')->count();

                $personal = Reservation::whereYear('created_at',$val["year"])->whereMonth('created_at',$val["month"])->whereHas('reservationRooms',function($rr)use($roomType){
                    return $rr->whereHas('rooms',function($r)use($roomType){
                        return $r->where('room_type_id', $roomType->room_type_id);
                    });
                })->where('type','personal')->count();

                $data[] = [
                    'room_type' => $roomType->name,
                    'group' => $group,
                    'personal' => $personal,
                    'total' => $group + $personal
                ];
            }

            $html = view('report.tamu')->with('data', [ 'year' => $val["year"],'month' => $val["month"], 'table' => $data])->render();

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
                false,$e->getMessage(),null, 400
            );
        }
    }

    public function getReservasiTerbanyak(Request $req){
        try{
            $val = $req->validate([
                'year' => 'required|integer',
            ]);

            $cust = Customer::whereHas('reservation',function($res) use($val) {
                return $res->whereYear('created_at',$val["year"]);
            })->with('user')->withCount('reservation')->orderBy('reservation_count', 'desc')->take(5)->get();

            $data = [];

            foreach ($cust as $c) {
                $data[] = [
                    'customer' => $c->user->name,
                    'reservation_count' => $c->reservation_count,
                    'total' => Reservation::where('customer_id',$c->customer_id)->withSum('transaction','amount')->get()->sum('transaction_sum_amount')
                ];
            }

            $html = view('report.reservasi')->with('data', [ 'year' => $val["year"], 'table' => $data])->render();

            return $this->baseResponse(
                true,'get success',[
                    'html' => $html,
                    'data' => $data
                ], 200
            );
        }
        catch(\Exception $e ){
            return $this->baseResponse(
                false,$e->getMessage(),null, 400
            );
        }
    }

    public function cetakReservasiTerbanyak(Request $req){
        try{
            $val = $req->validate([
                'year' => 'required|integer',
            ]);

            $cust = Customer::whereHas('reservation',function($res) use($val) {
                return $res->whereYear('created_at',$val["year"]);
            })->with('user')->withCount('reservation')->orderBy('reservation_count', 'desc')->take(5)->get();

            $data = [];

            foreach ($cust as $c) {
                $data[] = [
                    'customer' => $c->user->name,
                    'reservation_count' => $c->reservation_count,
                    'total' => Reservation::where('customer_id',$c->customer_id)->withSum('transaction','amount')->get()->sum('transaction_sum_amount')
                ];
            }

            $html = view('report.reservasi')->with('data', [ 'year' => $val["year"], 'table' => $data])->render();

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
                false,$e->getMessage(),null, 400
            );
        }
    }

}
