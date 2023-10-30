<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;
    protected  $primaryKey = 'room_id';
protected $guarded = [];

public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class, 'room_type_id','room_type_id');
    }

    public function reservations(): BelongsToMany
    {
        return $this->belongsToMany(Reservation::class, 'reservation_rooms', 'room_id','reservation_id');
    }
}
