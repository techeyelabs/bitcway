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


    public function derivativeAutoSell(Request $request)
    {
        $Bitfinex = new Bitfinex();
        $users = Leverage_Wallet::select('user_id')->groupBy('user_id')->get();
        foreach ($users as $user){
            $equivalentSellAmount = 0;
            $leverageWalletCurrency = Leverage_Wallet::where('user_id', $user->user_id)->orderBy('id', 'desc')->get();
            foreach($leverageWalletCurrency as $lwc){
                $equivalentSellAmount += $Bitfinex->getRate($lwc->currencyName->name) * $lwc->amount;
            }
            $userInvestment = Leverage_Wallet::where('user_id', $user->user_id)->sum('derivativeUserMoney');
            $userLoan = Leverage_Wallet::where('user_id', $user->user_id)->sum('derivativeLoan');
            $userProfit = $equivalentSellAmount - $userLoan;
            if (($userProfit) < ($userInvestment * 0.20)){
                foreach ($leverageWalletCurrency as $item) {
                    // Data preparation
                    $equivalentSellAmount = $Bitfinex->getRate($item->currencyName->name) * $item->amount;
                    $leverageRequestSellAmount = $item->amount;
                    $currency = Currency::where('name', $item->currencyName->name)->first();

                    DB::beginTransaction();
                    try {
                        $leverageWalletCurrency = Leverage_Wallet::where('user_id', Auth::user()->id)->where('currency_id', $currency->id)->orderBy('id', 'desc')->get();
                        $leverageSellAmount = $leverageRequestSellAmount;

                        foreach ($leverageWalletCurrency as $item) {
                            $derivativeSells = new DerivativeSell();
                            $derivativeSells->type = $item->type;
                            $derivativeSells->status = $item->status;
                            $derivativeSells->user_id = $item->user_id;
                            $derivativeSells->currency_id = $item->currency_id;
                            $derivativeSells->leverage = $item->leverage;

                            if ($item->amount <= $leverageSellAmount) {
                                $sellTimeValue = ($equivalentSellAmount * $item->amount) / $leverageRequestSellAmount;
                                $sellAmountToUserWallet = $sellTimeValue - ($item->equivalent_amount * (($item->leverage - 1) / $item->leverage));

                                $derivativeSells->amount = $item->amount;
                                $derivativeSells->equivalent_amount = $item->equivalent_amount;
                                $derivativeSells->priceAtSell = $sellTimeValue;
                                $derivativeSells->profit = $sellTimeValue - $item->equivalent_amount;
                                $derivativeSells->save();

                                Auth::user()->derivative = Auth::user()->derivative + $sellAmountToUserWallet;
                                Auth::user()->save();

                                $leverageSellAmount -= $item->amount;
                                $item->delete();
                            } else {
                                $sellTimeValue = ($equivalentSellAmount * $leverageSellAmount) / $leverageRequestSellAmount;
                                $buyTimeValue = ($item->equivalent_amount * $leverageSellAmount) / $item->amount;
                                $sellAmountToUserWallet = $sellTimeValue - ($buyTimeValue * (($item->leverage - 1) / $item->leverage));

                                $derivativeSells->amount = $leverageSellAmount;
                                $derivativeSells->equivalent_amount = $buyTimeValue;
                                $derivativeSells->priceAtSell = $sellTimeValue;
                                $derivativeSells->profit = $sellTimeValue - $buyTimeValue;
                                $derivativeSells->save();

                                $item->amount = $item->amount - $leverageSellAmount;
                                $item->equivalent_amount = $item->equivalent_amount - $buyTimeValue;
                                $item->save();
                                $leverageSellAmount = 0;
                            }
                            if ($leverageSellAmount == 0) {
                                break;
                            }
                        }

                        $TransactionHistory = new TransactionHistory();
                        $TransactionHistory->amount = $leverageRequestSellAmount;
                        $TransactionHistory->equivalent_amount = $equivalentSellAmount;
                        $TransactionHistory->type = 2;
                        $TransactionHistory->user_id = Auth::user()->id;
                        $TransactionHistory->currency_id = $currency->id;
                        $TransactionHistory->save();
                    }
                    catch(\Exception $e)
                    {
                        DB::rollback();
                        return response()->json(['status' => $e]);
                    }
                    DB::commit();
                }
                $victim = User::find($user->user_id);
                $victim->derivative = $victim->derivative + $sellAmountToUserWallet;
                $victim->save();
            }
        }
        exit;
    }
}
