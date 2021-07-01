<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DerivativeSell;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Models\Currency;
use App\Models\UserWallet;
use App\Models\TransactionHistory;
use App\Models\LockedSavingsSetting;
use App\Models\LockedSaving;
use App\Models\Leverage_Wallet;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;
use function PHPUnit\Framework\countOf;
use App\Libraries\Bitfinex;

class CronController extends Controller
{
    public function myaction(Request $request)
    {
        $Bitfinex = new Bitfinex();
        $CurrentDate = date('Y-m-d'); /* "2021-06-30" */
        $redemptionData = LockedSaving::where('redemption_date', '=' , $CurrentDate)->get();
        foreach ($redemptionData as $item){
            $totalCoin = $item->lot_count * $item->lot_size;
            $redemptionValue = $totalCoin + $item->expected_interest;
            $UserWallet = UserWallet::where('user_id', $item->user_id)->where('currency_id', $item->currency_id)->with("currency")->first();
            if(!$UserWallet) {
                $UserWallet = new UserWallet();
                $UserWallet->balance = $redemptionValue;
                $UserWallet->equivalent_trade_amount = $totalCoin * ($Bitfinex->getRate($item->currency->name));
            }else{
                $UserWallet->balance = $UserWallet->balance + $redemptionValue;
                $UserWallet->equivalent_trade_amount = $UserWallet->equivalent_trade_amount + ($totalCoin * $Bitfinex->getRate($item->currency->name));
            }
            $UserWallet->currency_price = $Bitfinex->getRate($item->currency->name);
            $UserWallet->user_id = $item->user_id;
            $UserWallet->currency_id = $item->currency_id;
            $UserWallet->save();

            $item->status = 2;
            $item->update();
        }
    }
}
