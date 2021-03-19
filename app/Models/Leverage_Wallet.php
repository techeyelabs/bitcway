<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leverage_Wallet extends Model
{
    use HasFactory;
    protected $table = 'leverage_wallets';
    protected $primaryKey = 'id';

    public function currencyName()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }
    public function leveragehistory()
    {
        return $this->belongsTo(UserWallet::class);
    }
}
