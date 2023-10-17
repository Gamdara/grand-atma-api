<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected  $primaryKey = 'customer_id';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id','user_id');
    }

    public function reservation(): HasMany
    {
        return $this->hasMany(Reservation::class,'reservation_id','reservation_id');
    }
}
