<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;
//    protected $table = 'currencies';
    public function currencyName()
    {
        return $this->belongsTo(Leverage_Wallet::class);
    }

    protected $fillable = [
        'name',
        'is_custom',
        'status',
    
    ];
    
    
    protected $dates = [
        'created_at',
        'updated_at',
    
    ];
    
    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/currencies/'.$this->getKey());
    }

}
