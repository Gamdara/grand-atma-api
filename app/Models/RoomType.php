<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoomType extends Model
{
    use HasFactory;
    protected  $primaryKey = 'room_type_id';
    protected $guarded = [];

    public function season($start_date, $end_date): BelongsToMany
    {
        return $this->belongsToMany(Season::class, 'season_fares', 'room_type_id', 'season_id')->withPivot('discount_amount')->whereDate('start_date','<=',$start_date)->whereDate('end_date','>=',$end_date);
    }

    public function room(): HasMany
    {
        return $this->hasMany(Room::class, 'room_type_id', 'room_type_id');
    }

    public function reservationRooms(): HasMany
    {
        return $this->hasMany(ReservationRoom::class, 'room_type_id', 'room_type_id');
    }
}
