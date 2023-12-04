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
        <h3 class="my-1 text-center font-bold">TANDA TERIMA PESANAN</h3>
        <hr class="mb-4" />
        <table class="w-full table-auto">
            <tr>
                <td>
                    <table class="w-full">
                        <tr>
                            <td class="max-w-56">ID Booking</td>
                            <td class="">{{$res->booking_id}}</td>
                        </tr>
                        <tr>
                            <td class="max-w-56">PIC</td>
                            <td class="">{{$res->sales->name}}</td>
                        </tr>
                        <tr>
                            <td class="max-w-56">Nama</td>
                            <td class="">{{$res->customer->user->name}}</td>
                        </tr>
                        <tr>
                            <td class="max-w-56">Alamat</td>
                            <td class="">{{ explode('.',$res->customer->address)[0]}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="w-full">
                        <tr>
                            <td class="max-w-56">Tanggal</td>
                            <td class="">{{ \Carbon\Carbon::parse($res->created_at)->format('d/m/Y')}}</td>
                        </tr>
                    </table>
                </td>
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
            <tr>
                <td>Tanggal Pembayaran</td>
                {{-- <td>{{
                    $res->transaction->where('type','bail')->first()
                }}</td> --}}
                <td>{{\Carbon\Carbon::parse($res->transaction->where('type','bail')->first()->created_at)->format('d/m/Y')}}</td>
            </tr>
        </table>
        {{-- {{$rooms[0+]}} --}}
        <hr class="my-4" />
        <hr class="my-6" />
        <table class="table-auto w-full divide-y divide-gray-200" >
          <thead class="text-center">
            <tr>
              <th class="py-3">Jenis Kamar</th>
              <th>Bed</th>
              <th>Jumlah</th>
              <th>Malam</th>
              <th>Harga</th>
              <th>Total</th>
            </tr>
          </thead>

          <tbody class="divide-y divide-gray-200">
            @foreach ($res->reservationRooms as $room)
                <tr class="text-center divide-y ">
                <td class="py-3">{{$room->roomType->name}}</td>
                <td>
                    @foreach(explode(',',$room->roomType->bed_options ) as $options)
                        @php
                            if ($room->rooms->where('bed_type',$options)->count() < 1) continue;
                        @endphp
                        {{ $options }}<br/>
                    @endforeach
                </td>
                <td>
                    @foreach(explode(',',$room->roomType->bed_options ) as $options)
                        @php
                            if ($room->rooms->where('bed_type',$options)->count() < 1) continue;
                        @endphp
                        {{$room->rooms->where('bed_type',$options)->count()}} <br>
                    @endforeach
                </td>
                <td>{{ (\Carbon\Carbon::parse( $res->start_date )->diffInDays( \Carbon\Carbon::parse( $res->end_date ) ))+1 }}</td>
                <td class="text-right">{{$room->fare}}</td>
                <td class="text-right">{{$room->total}}</td>
                </tr>

            @endforeach

          </tbody>
          <tfoot>
            <tr>
              <td class="py-3"></td>
              <td></td>
              <td></td>
              <td></td>
              <td class="text-right font-bold+"></td>
              <td class="text-right font-bold py-3">Rp {{$res->reservationRooms->sum('total')}}</td>
            </tr>
            <tr>
                <td class="py-3"></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="text-right font-bold+">Uang Jaminan</td>
                <td class="text-right font-bold py-3">Rp {{$res->transaction->where('type','bail')->first()->amount}}</td>
              </tr>
          </tfoot>
        </table>
        <div class="flex mb-1">
          <p class="w-44">Permintaan Khusus:</p>
        </div>
        @foreach (explode(',',$res->request ) as $req)
            <p class="mb-1">- {{$req}}</p>
        @endforeach
      </div>
</body>
</html>
