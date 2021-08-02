<?php

namespace App\Http\Controllers;

use App\Libraries\Bitfinex;
use App\Models\Currency;
use App\Models\Leverage_Wallet;
use App\Models\LimitBuySell;
use App\Models\TransactionHistory;
use App\Models\User;
use App\Models\UserWallet;
use App\Models\CronTrack;
use App\Models\LeverageSettlementLimit;
use App\Models\Message; //for cron testing, remove later
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;
use App\Traits\CurrentMABPrice;
use GuzzleHttp\Client;


class LimitCronController extends Controller
{
    use CurrentMABPrice;
    public function limitCronJob(){
        //test if the cron works
        $test = CronTrack::where('id', 1)->first();
        $test->limitcron = date('Y-m-d H:i:s');
        $test->save();

        $mabLast = $this->getCurrentPrice()['lastval'];
        $Bitfinex = new Bitfinex();
        $limitPrice = LimitBuySell::where("transactionStatus", 1)->with("currency")->get();
        for ($i = 0 ; $i < count($limitPrice); $i++){
            $data = $limitPrice[$i];
            if ($data->derivative > 0){
                if($data->type == 'buy'){
                    $getCurrentRate = $Bitfinex->getRateBuySell('buy', $data->currency->name);
                    if ($data->currency->name == 'ADA'){
                        $getCurrentRate = $mabLast;
                    }
                    if ($getCurrentRate <= $data->priceLimit){
                        $user = User::where('id', $data->user_id)->first();
                        //For saving Trade & derivative Amount in User table(derivative/balance)
                        $leverage = 1;
                        if(isset($data->derivative)){
                            if (($user->derivative - ($data->currencyAmount * $getCurrentRate) / $data->derivative) < 0){
                                continue;
                            }
                            $leverage = $data->derivative;
                            $user->derivative = $user->derivative - ($data->currencyAmount * $getCurrentRate) / $data->derivative;
                        }
                        $user->save();
                        try{
                            $TransactionHistory= new TransactionHistory();
                            $TransactionHistory->amount = $data->currencyAmount;
                            $TransactionHistory->equivalent_amount = $data->currencyAmount * $getCurrentRate;
                            $TransactionHistory->derivativeUserMoney = ($data->currencyAmount * $getCurrentRate) / $data->derivative;
                            $TransactionHistory->derivativeLoan = ($data->currencyAmount * $getCurrentRate) * (1 - (1 / $data->derivative));
                            if ($data->type == 'buy'){
                                $TransactionHistory->type = 1;
                            } else {
                                $TransactionHistory->type = 2;
                            }
                            $TransactionHistory->leverage = $data->derivative;
                            $TransactionHistory->user_id = $user->id;
                            $TransactionHistory->currency_id = $data->currency_id;
                            $TransactionHistory->save();

                            if(isset($data->derivative)) {
                                $LeverageWallet = new Leverage_Wallet();
                                $LeverageWallet->amount = $data->currencyAmount;
                                $LeverageWallet->trade_type = $data->type;
                                $LeverageWallet->equivalent_amount = $data->currencyAmount * $getCurrentRate;
                                $LeverageWallet->derivative_currency_price = $getCurrentRate;
                                $LeverageWallet->derivativeUserMoney = ($data->currencyAmount * $getCurrentRate) / $data->derivative;
                                $LeverageWallet->derivativeLoan = ($data->currencyAmount * $getCurrentRate) * (1 - (1 / $data->derivative));
                                $LeverageWallet->type = 1;
                                $LeverageWallet->leverage = $data->derivative;
                                $LeverageWallet->user_id = $user->id;
                                $LeverageWallet->currency_id = $data->currency_id;
                                $LeverageWallet->save();
                            }
                        } catch(Exception $e){
                            echo "problem";
                        }
                        $data->transactionStatus = 2;  // Transaction completion
                        $data->save();
                    }
                } else {
                    $getCurrentRate = $Bitfinex->getRateBuySell('sell', $data->currency->name);
                    if ($data->currency->name == 'ADA'){
                        $getCurrentRate = $mabLast;
                    }
                    if ($getCurrentRate >= $data->priceLimit){
                        $user = User::where('id', $data->user_id)->first();
                        //For saving Trade & derivative Amount in User table(derivative/balance)
                        $leverage = 1;
                        if(isset($data->derivative)){
                            if (($user->derivative - ($data->currencyAmount * $getCurrentRate) / $data->derivative) < 0){
                                continue;
                            }
                            $leverage = $data->derivative;
                            $user->derivative = $user->derivative - ($data->currencyAmount * $getCurrentRate) / $data->derivative;
                        }
                        $user->save();
                        try{
                            $TransactionHistory= new TransactionHistory();
                            $TransactionHistory->amount = $data->currencyAmount;
                            $TransactionHistory->equivalent_amount = $data->currencyAmount * $getCurrentRate;
                            $TransactionHistory->derivativeUserMoney = ($data->currencyAmount * $getCurrentRate) / $data->derivative;
                            $TransactionHistory->derivativeLoan = ($data->currencyAmount * $getCurrentRate) * (1 - (1 / $data->derivative));
                            if ($data->type == 'buy'){
                                $TransactionHistory->type = 1;
                            } else {
                                $TransactionHistory->type = 2;
                            }
                            $TransactionHistory->leverage = $data->derivative;
                            $TransactionHistory->user_id = $user->id;
                            $TransactionHistory->currency_id = $data->currency_id;
                            $TransactionHistory->save();

                            if(isset($data->derivative)) {
                                $LeverageWallet = new Leverage_Wallet();
                                $LeverageWallet->amount = $data->currencyAmount;
                                $LeverageWallet->trade_type = $data->type;
                                $LeverageWallet->equivalent_amount = $data->currencyAmount * $getCurrentRate;
                                $LeverageWallet->derivative_currency_price = $getCurrentRate;
                                $LeverageWallet->derivativeUserMoney = ($data->currencyAmount * $getCurrentRate) / $data->derivative;
                                $LeverageWallet->derivativeLoan = ($data->currencyAmount * $getCurrentRate) * (1 - (1 / $data->derivative));
                                $LeverageWallet->type = 1;
                                $LeverageWallet->leverage = $data->derivative;
                                $LeverageWallet->user_id = $user->id;
                                $LeverageWallet->currency_id = $data->currency_id;
                                $LeverageWallet->save();
                            }
                        } catch(Exception $e){
                            echo "problem";
                        }
                        $data->transactionStatus = 2; // Transaction completion
                        $data->save();
                    }
                }
            } else {
                $flag = ($data->limitType == 1) ? 'buy' : 'sell';
                $getCurrentRate = $Bitfinex->getRateBuySell($flag, $data->currency->name);
                //For Buy
                if ($data->limitType == 1){
                    if($getCurrentRate <= $data->priceLimit){
                        $this->updateLimitBuyTable($data->currency->id, $data->id, $data->user_id);
                    }
                    //For Sell
                }else if($data->limitType == 2){
                    if($getCurrentRate >= $data->priceLimit){
                        $this->updateLimitSellTable($data->currency->id, $data->id, $data->user_id);
                    }
                }
            }
        }

        $settlmentLimits = LeverageSettlementLimit::where('settlement_status', 0)->get();
        foreach ($settlmentLimits as $index => $item){
            try{
                if ($item->type == 'buy'){
                    $getCurrentRate = $Bitfinex->getRateBuySell('sell', $item->currency->name);
                } else {
                    $getCurrentRate = $Bitfinex->getRateBuySell('buy', $item->currency->name);
                }
                if ($item->limit_rate <= $item->price_at_time_of_creation && $item->price_at_time_of_creation <= $getCurrentRate || $item->limit_rate >= $item->price_at_time_of_creation && $item->price_at_time_of_creation >= $getCurrentRate){
                    $client = new Client();
                    $res = $client->request('POST', '/limit-sell', [
                        'form_params' => [
                            'currency'          => $item->currency->name,
                            'limitType'         => 2,
                            'priceLimit'        => $item->limit_rate,
                            'currencyAmount'    => $item->amount,
                            'transactionStatus' => 1,
                            'derivative'        => $item->leverage,
                        ]
                    ]);

                    if ($res->getStatusCode() == 200){
                        $item->status = 1;
                        $item->save();
                    }
                }
            } catch (Exception $e){
                continue;
            }
        }
    }

    public function updateLimitBuyTable($currencyId, $id, $userId){
        $updateLimitTable = LimitBuySell::where('id', $id)->where('currency_id', $currencyId)->first();
        $UserWallet = UserWallet::where('user_id', $userId)->where('currency_id', $currencyId)->first();
        $userBalance = User::where('id', $userId)->first();
        $trade_amount_limit = $updateLimitTable->priceLimit;
        $equivalent_trade_balance_limit = $updateLimitTable->currencyAmount;
        $equivalent_trade_amount_limit = ($trade_amount_limit * $equivalent_trade_balance_limit);
        $limit_user_id = $userId;
        $limit_currency_id  = $currencyId;

        if(!$UserWallet) {
            $UserWallet = new UserWallet();
            $UserWallet->balance = $equivalent_trade_balance_limit;
            $UserWallet->equivalent_trade_amount = $equivalent_trade_amount_limit;

        }else{
            $UserWallet->balance = $UserWallet->balance + $equivalent_trade_balance_limit;
            $UserWallet->equivalent_trade_amount = $UserWallet->equivalent_trade_amount + $equivalent_trade_amount_limit;
        }
        $UserWallet->user_id = $limit_user_id;
        $UserWallet->currency_id = $limit_currency_id;
        $UserWallet->save();

        $userBalance->balance = $userBalance->balance - $equivalent_trade_amount_limit;
        $userBalance->save();

        try{
            $TransactionHistory= new TransactionHistory();
            $TransactionHistory->amount = $equivalent_trade_balance_limit;
            $TransactionHistory->equivalent_amount = $equivalent_trade_amount_limit;
            $TransactionHistory->derivativeUserMoney = 0;
            $TransactionHistory->derivativeLoan = 0;
            $TransactionHistory->type = 1;
            $TransactionHistory->status = 1;
            $TransactionHistory->leverage = 1;
            $TransactionHistory->user_id = $limit_user_id;
            $TransactionHistory->currency_id = $limit_currency_id;
            $TransactionHistory->save();

        } catch(Exception $e){
            return response()->json(['status' => false]);
        }

        $updateLimitTable->transactionStatus = 2;
        $updateLimitTable->save();
    }


    public function updateLimitSellTable($currencyId, $id, $userId){
        $updateLimitTable = LimitBuySell::where('id', $id)->where('currency_id', $currencyId)->first();
        $UserWallet = UserWallet::where('user_id', $userId)->where('currency_id', $currencyId)->first();
        $userBalance = User::where('id', $userId)->first();
        $trade_amount_limit = $updateLimitTable->priceLimit;
        $equivalent_trade_balance_limit = $updateLimitTable->currencyAmount;
        $equivalent_trade_amount_limit = ($trade_amount_limit * $equivalent_trade_balance_limit);
        $limit_user_id = $userId;
        $limit_currency_id  = $currencyId;

        if($UserWallet) {
            if ($UserWallet->balance < $equivalent_trade_balance_limit){
                $equivalent_trade_balance_limit = $UserWallet->balance;
                $equivalent_trade_amount_limit = ($trade_amount_limit * $equivalent_trade_balance_limit);
            }

            $UserWallet->balance =  $UserWallet->balance - $equivalent_trade_balance_limit;
            $UserWallet->equivalent_trade_amount = $UserWallet->equivalent_trade_amount - $equivalent_trade_amount_limit;
            $UserWallet->save();

            if ($UserWallet->balance == 0.00000000){
                $UserWallet->delete();
            }
        }
        $userBalance->balance = $userBalance->balance + $equivalent_trade_amount_limit;
        $userBalance->save();

        try{
            $TransactionHistory= new TransactionHistory();
            $TransactionHistory->amount = $equivalent_trade_balance_limit;
            $TransactionHistory->equivalent_amount = $equivalent_trade_amount_limit;
            $TransactionHistory->derivativeUserMoney = 0;
            $TransactionHistory->derivativeLoan = 0;
            $TransactionHistory->type = 2;
            $TransactionHistory->status = 1;
            $TransactionHistory->leverage = 1;
            $TransactionHistory->user_id = $limit_user_id;
            $TransactionHistory->currency_id = $limit_currency_id;
            $TransactionHistory->save();

        } catch(Exception $e){
            return response()->json(['status' => false]);
        }

        $updateLimitTable->transactionStatus = 2;
        $updateLimitTable->save();
    }
}
