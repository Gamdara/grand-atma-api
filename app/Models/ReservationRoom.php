<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ReservationRoom extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected  $primaryKey = 'reservation_room_id';
    public function test(){
        return "aw";
    }
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class,'reservation_id','reservation_id');
    }

    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'reservation_room_details', 'reservation_room_id','room_id');
    }

    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class, 'room_type_id','room_type_id');
    }
}
