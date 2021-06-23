<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DmgCoin extends Model
{
    use HasFactory;
    protected $fillable = [
        'start_date',
        'end_date',
        'price_update',
        'display_status'
    ];
}
