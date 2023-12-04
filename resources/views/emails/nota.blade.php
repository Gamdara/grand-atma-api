<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" integrity="sha512-wnea99uKIC3TJF7v4eKk4Y+lMz2Mklv18+r4na2Gn1abDRPPOeef95xTzdwGD9e6zXJBteMIhZ1+68QC5byJZw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="p-5">
        <div class="flex flex-col text-center">
          <img class="w-96 self-center" src="https://github.com/Gamdara/gudang/blob/main/logo.png?raw=true" />
          <p>Jl. P. Mangkubumi No.18, Yogyakarta 55233</p>
          <p>Telp. (0274) 487711</p>
        </div>
        <hr class="mt-4" />
        <h3 class="my-1 text-center font-bold">INVOICE</h3>
        <hr class="mb-4" />
        <table class="mt-6 w-72 table-auto float-right">
            <tr>
                <td>Tanggal </td>
                <td>{{\Carbon\Carbon::parse($res->transaction->where('type','settle')->first()->created_at)->format('d/m/Y')}}</td>
            </tr>
            <tr>
                <td>No. Invoice</td>
                <td>{{$res->transaction->where('type','settle')->first()->no_invoice}}</td>
            </tr>
            <tr>
                <td>Front Office</td>
                <td>{{$res->frontOffice->name}}</td>
            </tr>
        </table>
        <table class="mt-6 w-72 table-auto ">
            <tr>
                <td class="">ID Booking</td>
                <td class="">{{$res->booking_id}}</td>
            </tr>
            <tr>
                <td class="">Nama</td>
                <td class="">{{$res->customer->user->name}}</td>
            </tr>
            <tr>
                <td class="">Alamat</td>
                <td class="">{{ explode('.',$res->customer->address)[0]}}</td>
            </tr>
        </table>

        <hr class="mt-4" />
        <h3 class="my-1 text-center font-bold">DETAIL PEMESANAN</h3>
        <hr class="mb-4" />
        <table class="mt-6 w-72 table-auto ">
            <tr>
                <td>Check In</td>
                <td>{{$res->start_date}}</td>
            </tr>
            <tr>
                <td>Check Out</td>
                <td>{{$res->end_date}}</td>
            </tr>
            <tr>
                <td>Dewasa</td>
                <td>{{$res->adults}}</td>
            </tr>
            <tr>
                <td>Anak-anak</td>
                <td>{{$res->kids}}</td>
            </tr>
        </table>
        {{-- {{$rooms[0+]}} --}}
        <hr class="mt-4" />
        <h3 class="my-1 text-center font-bold">KAMAR</h3>
        <hr class="mb-4" />
        <table class="table-auto w-full divide-y divide-gray-200" >
          <thead class="text-center">
            <tr>
              <th class="border border-slate-600 py-3">Jenis Kamar</th>
              <th class="border border-slate-600">Bed</th>
              <th class="border border-slate-600">Jumlah</th>
              <th class="border border-slate-600">Malam</th>
              <th class="border border-slate-600">Harga</th>
              <th class="border border-slate-600">Total</th>
            </tr>
          </thead>

          <tbody class="divide-y divide-gray-200">
            @foreach ($res->reservationRooms as $room)
                <tr class="border border-slate-600 text-center divide-y ">
                <td class="border border-slate-600 py-3">{{$room->roomType->name}}</td>
                <td class="border border-slate-600">
                    @foreach(explode(',',$room->roomType->bed_options ) as $options)
                        @php
                            if ($room->rooms->where('bed_type',$options)->count() < 1) continue;
                        @endphp
                        {{ $options }}<br/>
                    @endforeach
                </td>
                <td class="border border-slate-600">
                    @foreach(explode(',',$room->roomType->bed_options ) as $options)
                        @php
                            if ($room->rooms->where('bed_type',$options)->count() < 1) continue;
                        @endphp
                        {{$room->rooms->where('bed_type',$options)->count()}} <br>
                    @endforeach
                </td>
                <td class="border border-slate-600">{{ \Carbon\Carbon::parse( $res->start_date )->diffInDays( \Carbon\Carbon::parse( $res->end_date ) ) }}</td>
                <td class="border border-slate-600 text-right px-3">{{$room->fare}}</td>
                <td class="border border-slate-600 text-right px-3">{{$room->total}}</td>
                </tr>

            @endforeach

          </tbody>
          <tfoot>
            <tr class="border border-slate-600">
              <td class="py-3"></td>
              <td></td>
              <td></td>
              <td></td>
              <td class="font-bold text-center">Total</td>
              <td class="border border-slate-600 text-right font-bold p-3">Rp {{$res->reservationRooms->sum('total')}}</td>
            </tr>
          </tfoot>
        </table>

        <hr class="mt-4" />
        <h3 class="my-1 text-center font-bold">LAYANAN</h3>
        <hr class="mb-4" />
        <table class="table-auto w-full divide-y divide-gray-200" >
          <thead class="text-center">
            <tr>
              <th class="border border-slate-600 py-3">Layanan</th>
              <th class="border border-slate-600">Tanggal</th>
              <th class="border border-slate-600">Jumlah</th>
              <th class="border border-slate-600">Harga</th>
              <th class="border border-slate-600">Sub Total</th>
            </tr>
          </thead>


          <tbody class="divide-y divide-gray-200">
            @foreach ($res->services as $service)
                {{-- {{$service}} --}}
                <tr class="text-center divide-y ">
                    <td class="border border-slate-600 py-3">{{$service->name}}</td>
                    <td class="border border-slate-600">{{\Carbon\Carbon::parse($service->pivot->created_at)->format('d M Y')}}</td>
                    <td class="border border-slate-600">{{$service->pivot->amount}}</td>
                    <td class="border border-slate-600 text-right px-3">{{$service->pivot->fare}}</td>
                    <td class="border border-slate-600 text-right px-3">{{$service->pivot->amount * $service->pivot->fare}}</td>
                </tr>
            @endforeach

          </tbody>
          <tfoot >
            <tr class="border border-slate-600">
              <td class="py-3"></td>
              <td></td>
              <td></td>
              <td class="text-center font-bold">Total</td>
              <td class="border border-slate-600 text-right font-bold p-3">Rp {{$res->services->sum(function ($s){
                return $s->pivot->amount * $s->pivot->fare;
              })}}</td>
            </tr>
          </tfoot>
        </table>
        @php
            $tax = $res->services->sum(function ($s){
                return $s->pivot->amount * $s->pivot->fare;
              }) * 10/100
        @endphp
        <table class="mt-6 w-72 table-auto float-right">
            <tr>
                <td>Tax </td>
                <td class="text-right">Rp. {{$tax}}</td>
            </tr>
            @php
                $total = $res->total + ($tax);
            @endphp
            <tr>
                <td class="font-bold">TOTAL</td>
                <td  class="text-right font-bold">Rp. {{$total}}</td>
            </tr>
            @php
                $jaminan = $res->transaction->where('type','bail')->first()->amount;
            @endphp
            <tr>
                <td>Jaminan</td>
                <td  class="text-right">Rp. {{$jaminan}}</td>
            </tr>
            <tr>
                <td>Deposit</td>
                <td  class="text-right">Rp {{ 300000}}</td>
            </tr>
            <tr>
                <td class="font-bold">Cash</td>
                <td  class="text-right font-bold">Rp. {{$total - $jaminan - 300000}}</td>
            </tr>
        </table>

        <div class="flex mb-1 w-full">
          <p class="w-44">Permintaan Khusus:</p>
        </div>
        @foreach (explode(',',$res->request ) as $req)
            <p class="mb-1">- {{$req}}</p>
        @endforeach
      </div>
</body>
</html>
