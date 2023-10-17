<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reservation extends Model
{
    use HasFactory;
    protected  $primaryKey = 'reservation_id';
    protected $guarded = [];
    public function reservationExtend(): HasMany
    {
        return $this->hasMany(ReservationExtend::class, 'reservation_id','reservation_id');
    }

    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'reservation_rooms', 'reservation_id','room_id')
            ->withPivot('fare','amount');
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'reservation_services', 'reservation_id','service_id')
            ->withPivot('fare','amount');
    }


    public function transaction(): HasMany
    {
        return $this->hasMany(Transaction::class, 'reservation_id','reservation_id');
    }


    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class,'coupon_id','coupon_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(customer::class,'customer_id','customer_id');
    }

    public function frontOffice(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id','user_id');
    }

    public function pic(): BelongsTo
    {
        return $this->belongsTo(User::class,"pic","user_id");
    }

}
