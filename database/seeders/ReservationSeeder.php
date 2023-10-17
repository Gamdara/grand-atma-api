<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\ReservationExtend;
use App\Models\Room;
use App\Models\Service;
use App\Models\Transaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //personal belum
        Reservation::factory()->count(5)->create()
        ->each(function (Reservation $reservation) {
            $rooms = Room::all()->random(rand(1,5))->mapWithKeys(function (Room $room) {
                return [$room->room_id =>
                [
                    'amount' => fake()->numberBetween(1,3),
                    'fare' => $room->roomType->fare
                ]
                ];
            });
            $reservation->rooms()->attach($rooms);

            $services = Service::all()->random(rand(1,5))->mapWithKeys(function (Service $service) {
                return [$service->service_id =>
                [
                    'amount' => fake()->numberBetween(1,3),
                    'fare' => $service->fare
                ]
                ];
            });
            $reservation->services()->attach($services);
        });

        //group belum
        Reservation::factory()->count(5)->group()->create()
        ->each(function (Reservation $reservation) {
            $rooms = Room::all()->random(rand(1,5))->mapWithKeys(function (Room $room) {
                return [$room->room_id =>
                [
                    'amount' => fake()->numberBetween(1,3),
                    'fare' => $room->roomType->fare
                ]
                ];
            });
            $reservation->rooms()->attach($rooms);

            $services = Service::all()->random(rand(1,5))->mapWithKeys(function (Service $service) {
                return [$service->service_id =>
                [
                    'amount' => fake()->numberBetween(1,3),
                    'fare' => $service->fare
                ]
                ];
            });
            $reservation->services()->attach($services);
        });

        // //personal baru bayr
        Reservation::factory()->count(5)
        ->state(function (array $attributes) {
            return ['check_in' => $attributes['start_date'], 'status'=>'bailed'];
        })
        ->create()
        ->each(function (Reservation $reservation) {
            $rooms = Room::all()->random(rand(1,5))->mapWithKeys(function (Room $room) {
                return [$room->room_id =>
                [
                    'amount' => fake()->numberBetween(1,3),
                    'fare' => $room->roomType->fare
                ]
                ];
            });
            $reservation->rooms()->attach($rooms);

            $services = Service::all()->random(rand(1,5))->mapWithKeys(function (Service $service) {
                return [$service->service_id =>
                [
                    'amount' => fake()->numberBetween(1,3),
                    'fare' => $service->fare
                ]
                ];
            });
            $reservation->services()->attach($services);

            Transaction::factory()->count(1)
                ->state(function (array $attributes) use($reservation) {
                return ['reservation_id' => $reservation->reservation_id];
            })->create();
        });

        // //personal baru bayr
        Reservation::factory()->count(5)
        ->state(function (array $attributes) {
            return ['check_in' => $attributes['start_date'], 'status'=>'bailed'];
        })
        ->group()
        ->create()
        ->each(function (Reservation $reservation) {
            $rooms = Room::all()->random(rand(1,5))->mapWithKeys(function (Room $room) {
                return [$room->room_id =>
                [
                    'amount' => fake()->numberBetween(1,3),
                    'fare' => $room->roomType->fare
                ]
                ];
            });
            $reservation->rooms()->attach($rooms);

            $services = Service::all()->random(rand(1,5))->mapWithKeys(function (Service $service) {
                return [$service->service_id =>
                [
                    'amount' => fake()->numberBetween(1,3),
                    'fare' => $service->fare
                ]
                ];
            });
            $reservation->services()->attach($services);

            Transaction::factory()->count(1)
                ->state(function (array $attributes) use($reservation) {
                return ['reservation_id' => $reservation->reservation_id];
            })->create();
        });

        //personal baru bayr
        Reservation::factory()->count(5)
        ->state(function (array $attributes) {
            return ['check_in' => $attributes['start_date'], 'check_out' => $attributes['end_date'],'status'=>'settled'];
        })
        ->create()
        ->each(function (Reservation $reservation) {
            $rooms = Room::all()->random(rand(1,5))->mapWithKeys(function (Room $room) {
                return [$room->room_id =>
                [
                    'amount' => fake()->numberBetween(1,3),
                    'fare' => $room->roomType->fare
                ]
                ];
            });
            $reservation->rooms()->attach($rooms);

            Transaction::factory()->count(1)
            ->state(function (array $attributes) use($reservation) {
                return ['reservation_id' => $reservation->reservation_id];
            })->create();

            $services = Service::all()->random(rand(0,2))->mapWithKeys(function (Service $service) {
                return [$service->service_id =>
                [
                    'amount' => fake()->numberBetween(1,3),
                    'fare' => $service->fare
                ]
                ];
            });
            $reservation->services()->attach($services);

            Transaction::factory()->count(1)
            ->state(function (array $attributes) use($reservation) {
                return ['reservation_id' => $reservation->reservation_id];
            })->lunas()->create();
        });

        //personal baru bayr
        Reservation::factory()->count(5)
        ->state(function (array $attributes) {
            return ['check_in' => $attributes['start_date'], 'check_out' => $attributes['end_date'],'status'=>'settled'];
        })
        ->group()
        ->create()
        ->each(function (Reservation $reservation) {
            $rooms = Room::all()->random(rand(1,5))->mapWithKeys(function (Room $room) {
                return [$room->room_id =>
                [
                    'amount' => fake()->numberBetween(1,3),
                    'fare' => $room->roomType->fare
                ]
                ];
            });
            $reservation->rooms()->attach($rooms);

            $services = Service::all()->random(rand(1,5))->mapWithKeys(function (Service $service) {
                return [$service->service_id =>
                [
                    'amount' => fake()->numberBetween(1,3),
                    'fare' => $service->fare
                ]
                ];
            });
            $reservation->services()->attach($services);

            Transaction::factory()->count(1)
            ->state(function (array $attributes) use($reservation) {
                return ['reservation_id' => $reservation->reservation_id];
            })->create();

            Transaction::factory()->count(1)
            ->state(function (array $attributes) use($reservation) {
                return ['reservation_id' => $reservation->reservation_id];
            })->lunas()->create();

        });


        // extend
        Reservation::factory()->count(5)
        ->state(function (array $attributes) {
            return ['check_in' => $attributes['start_date'], 'check_out' => $attributes['end_date'],'status'=>'settled'];
        })
        ->create()
        ->each(function (Reservation $reservation) {
            $rooms = Room::all()->random(rand(1,5))->mapWithKeys(function (Room $room) {
                return [$room->room_id =>
                [
                    'amount' => fake()->numberBetween(1,3),
                    'fare' => $room->roomType->fare
                ]
                ];
            });
            $reservation->rooms()->attach($rooms);

            Transaction::factory()->count(1)
            ->state(function (array $attributes) use($reservation) {
                return ['reservation_id' => $reservation->reservation_id];
            })->create();

            ReservationExtend::factory()->count(1)
            ->state(function (array $attributes) use($reservation) {
                return [
                    'reservation_id' => $reservation->reservation_id,
                    'start_date' => $reservation->end_date,
                    'end_date' => $reservation->end_date->copy()->addDays(rand(1,5)),
                ];
            })->create();

            Transaction::factory()->count(1)
            ->state(function (array $attributes) use($reservation) {
                return ['reservation_id' => $reservation->reservation_id];
            })->lunas()->create();

            $reservation->update(['check_out' => $reservation->reservationExtend->first()->end_date]);
        });
    }
}
