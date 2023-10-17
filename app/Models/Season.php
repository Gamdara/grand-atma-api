<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Season extends Model
{
    use HasFactory;
    protected  $primaryKey = 'season_id';
    protected $guarded = [];

    public function roomType(): BelongsToMany
    {
        return $this->belongsToMany(RoomType::class, 'season_fares', 'season_id', 'room_type_id')->withPivot('discount_amount');
    }
}
