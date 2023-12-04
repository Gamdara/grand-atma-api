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
        </div>
        <hr class="mt-4" />
        <h3 class="my-1 text-center font-bold">LAPORAN CUSTOMER PEMESAN TERBANYAK</h3>
        {{-- <hr class="mb-4" /> --}}
        <p class="my-2">Tahun {{$data['year']}}</p>

        <table class="table-auto w-full border-collapse border border-slate-500 justify-self-center">
          <thead class=" text-center">
            <tr>
              <th class="border border-slate-600 py-3">No</th>
              <th class="border border-slate-600">Nama Customer</th>
              <th class="border border-slate-600">Jumlah Reservasi</th>
              <th class="border border-slate-600">Total Pembayaran</th>
            </tr>
          </thead>

          <tbody>
            @php
                $total = 0;
            @endphp
            @foreach ($data['table'] as $key => $room)
                @php
                    $total += $room['total'];
                @endphp
                <tr class="text-center divide-y ">
                    <td class="border border-slate-600 py-3">{{$key + 1}}</td>
                    <td class="px-4  border border-slate-600 text-left">{{$room['customer']}}</td>
                    <td class="border border-slate-600">{{$room['reservation_count']}}</td>
                    <td class="px-4 text-right border border-slate-600">{{$room['total']}}</td>
                </tr>
            @endforeach

          </tbody>
          <tfoot>
            <tr class="text-center divide-y ">
              <td></td>
              <td></td>
              <td>Total</td>
              <td class="py-1 px-4 text-right font-bold">{{$total}}</td>
            </tr>
          </tfoot>
        </table>

        <table class="mt-6 w-72 table-auto float-right">
            <tr>
                <td></td>
                <td class="text-right">dicetak tanggal  {{\Carbon\Carbon::now()->format('d F Y')}}</td>
            </tr>
        </table>

      </div>
</body>
</html>
