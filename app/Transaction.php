<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //

    protected $fillable = [
        'description',
        'amount',
        'varying',
        'planned_at',
        'actual_at',
        'user_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
