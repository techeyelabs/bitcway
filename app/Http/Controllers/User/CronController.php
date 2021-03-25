<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DerivativeSell;
use Carbon\Carbon;
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

class CronController extends Controller
{
    public function myaction(Request $request)
    {
        $date = date('Y-m-d H:i:s');
        $data = LockedSaving::where('status', 1)->where('redemption_date', '<=' , $date)->get();
        $currency = Currency::select('id')->where('name', 'ADA')->first();
        $id = $currency->id;
        foreach($data as $item){
            $value = ((($item->lot_count)*5)+$item->expected_interest);
            $newdata = new UserWallet();
            $newdata->balance = $value;
            $newdata->user_id = $item->user_id;
            $newdata->currency_id = $id;
            $newdata->save();
            
            LockedSaving::where('id', $item->id)
                ->update(['status' => '0']);
        }
    }
}
