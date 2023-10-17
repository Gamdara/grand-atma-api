<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Coupon extends Model
{
    use HasFactory;
    protected  $primaryKey = 'coupon_id';
    protected $guarded = [];

    public function reservation(): HasOne
    {
        return $this->hasOne(Reservation::class, 'coupon_id','coupon_id');
    }

}
