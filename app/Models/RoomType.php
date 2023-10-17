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

    public function season(): BelongsToMany
    {
        return $this->belongsToMany(Season::class, 'season_fares', 'room_type_id', 'season_id')->withPivot('discount_amount');
    }

    public function room(): HasMany
    {
        return $this->hasMany(Room::class, 'room_type_id', 'room_type_id');
    }
}
