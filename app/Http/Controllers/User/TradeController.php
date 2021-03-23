<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DerivativeSell;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Libraries\Bitfinex;
use App\Models\Currency;
use App\Models\UserWallet;
use App\Models\TransactionHistory;
use App\Models\LockedSavingsSetting;
use App\Models\LockedSaving;
use App\Models\Leverage_Wallet;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;
use function PHPUnit\Framework\countOf;

class TradeController extends Controller
{
    public function index(Request $request)
    {
        $currency = array();
        $data['currency'] = 0;
        if(isset($request->type)){
            $data['type'] = $request->type;
            $leverageAmount= Leverage_Wallet::select('currency_id')
                                                        ->selectRaw('sum(amount) as total')
                                                        ->where('user_id', Auth::id())
                                                        ->groupBy('currency_id')
                                                        ->with('currencyName')
                                                        ->get();

            for ($i = 0 ; $i < count($leverageAmount); $i++){
                $currencyInfo = array();
                $currencyInfo["id"] = $leverageAmount[$i]->currency_id;
                $currencyInfo["amount"] = $leverageAmount[$i]->total;
                $currency[$leverageAmount[$i]->currencyName->name] = $currencyInfo;

            }
            $data['currency'] = $currency;
//            dd($data);
            return view('user.trade.index', $data);
        }

        return view('user.trade.index', $data);
    }

    public function finance(Request $request)
    {
        $data['settings'] = LockedSavingsSetting::get();
        //dummy coin data grab
        $id = Currency::where('name', 'ADA')->pluck('id');
        $data['dummy_coin_balance'] = UserWallet::where('user_id', Auth::id())->where('currency_id', $id)->sum('balance');
        $data['history'] = LockedSaving::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
        $data['total'] = 0;

        return view('user.trade.finance', $data);
    }

    public function insertFinance(Request $request)
    {
        try {
            $date = date('Y-m-d');
            $plan = LockedSavingsSetting::where('id', $request->plan)->first();
            $lockedInvest = new LockedSaving();
            $lockedInvest->user_id = Auth::user()->id;
            $lockedInvest->plan_id = $request->plan;
            $lockedInvest->lot_count = $request->lot;
            $lockedInvest->value_date = $date;
            $lockedInvest->redemption_date = date('Y-m-d', strtotime($date . ' + ' . $plan->duration . ' days'));
            $lockedInvest->expected_interest = $plan->interest_per_lot * $request->lot;
            $lockedInvest->save();
        } catch (Exception $e){
            return route('user-trade-finance');
        }
        return redirect()->back();
    }

    public function getChartData(Request $request)
    {
        if(empty($request->currency)) return response()->json(['status' => false]);
        $Bitfinex = new Bitfinex();
        $response = $Bitfinex->getCandle($request->currency);
        $balance = UserWallet::whereHas('currency', function($query) use ($request){
            return $query->where('name', $request->user_currency);
        })->where('user_id', Auth::user()->id)->first();
        if($balance) $balance = $balance->balance;
        else $balance = 0;
        return response()->json(['status' => true, 'chartData' => $response, 'balance' => $balance]);
    }

    public function buy(Request $request)
    {
        $currency = Currency::where('name', $request->currency)->first();
        if(!$currency) return response()->json(['status' => false]);

        $UserWallet = UserWallet::where('user_id', Auth::user()->id)->where('currency_id', $currency->id)->first();

        //For saving Trade data in User Wallet table
        if (!isset($request->leverage)){
            if(!$UserWallet) {
                $UserWallet = new UserWallet();
                $UserWallet->balance = $request->buyAmount;
            }else{
                $UserWallet->balance = $UserWallet->balance+$request->buyAmount;
            }
            $UserWallet->user_id = Auth::user()->id;
            $UserWallet->currency_id = $currency->id;
            $UserWallet->save();
        }

        //For saving Trade & derivative Amount in User table(derivative/balance)
        $leverage = 1;
        if(isset($request->leverage)){
            $leverage = $request->leverage;
            Auth::user()->derivative = Auth::user()->derivative - $request->calcBuyAmount / $leverage;
        }else{
            Auth::user()->balance = Auth::user()->balance - $request->calcBuyAmount;
        }
        Auth::user()->save();

        try{
            $TransactionHistory= new TransactionHistory();
            $TransactionHistory->amount = $request->buyAmount;
            $TransactionHistory->equivalent_amount = $request->calcBuyAmount;
            $TransactionHistory->type = 1;
            $TransactionHistory->leverage = $leverage;
            $TransactionHistory->user_id = Auth::user()->id;
            $TransactionHistory->currency_id = $currency->id;
            $TransactionHistory->save();

            if(isset($request->leverage)) {
                $LeverageWallet = new Leverage_Wallet();
                $LeverageWallet->amount = $request->buyAmount;
                $LeverageWallet->equivalent_amount = $request->calcBuyAmount;
                $LeverageWallet->type = 1;
                $LeverageWallet->leverage = $leverage;
                $LeverageWallet->user_id = Auth::user()->id;
                $LeverageWallet->currency_id = $currency->id;
                $LeverageWallet->save();
            }
        } catch(Exception $e){
            return response()->json(['status' => false]);
        }
       return response()->json(['status' => true]);
    }

    public function sell(Request $request)
    {
        $leverageRequestSellAmount = $request->sellAmount;
        $equivalentSellAmount = $request->calcSellAmount;

        $currency = Currency::where('name', $request->currency)->first();
        if(!$currency){
            return response()->json(['status' => false]);
        }

        DB::beginTransaction();
        try {
            if (isset($request->derivativeType)) {
//                $UserWallet->balance = $UserWallet->balance - $leverageRequestSellAmount;
//                $UserWallet->save();

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

                        Auth::user()->derivative = Auth::user()->derivative + $sellAmountToUserWallet;
                        Auth::user()->save();

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
                $TransactionHistory->amount = $request->sellAmount;
                $TransactionHistory->equivalent_amount = $request->calcSellAmount;
                $TransactionHistory->type = 2;
                $TransactionHistory->user_id = Auth::user()->id;
                $TransactionHistory->currency_id = $currency->id;
                $TransactionHistory->save();

            }else{
                $TransactionHistory= new TransactionHistory();
                $TransactionHistory->amount = $request->sellAmount;
                $TransactionHistory->equivalent_amount = $request->calcSellAmount;
                $TransactionHistory->type = 2;
                $TransactionHistory->user_id = Auth::user()->id;
                $TransactionHistory->currency_id = $currency->id;
                $TransactionHistory->save();

                $UserWallet = UserWallet::where('user_id', Auth::user()->id)->where('currency_id', $currency->id)->first();
                if(!$UserWallet) return response()->json(['status' => false]);

                $UserWallet->balance = $UserWallet->balance-$request->sellAmount;
                $UserWallet->save();
                if ($UserWallet->balance == 0.00000000){
                    $UserWallet->delete();
                }

                Auth::user()->balance = Auth::user()->balance+$equivalentSellAmount;
                Auth::user()->save();
            }
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return response()->json(['status' => false]);
        }
        DB::commit();
        return response()->json(['status' => true]);
    }

    public function getBuyOrders(Request $request)
    {
        $Bitfinex = new Bitfinex();
        return $Bitfinex->getOrderBook($request->currency);
    }

}
