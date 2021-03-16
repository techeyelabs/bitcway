<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Libraries\Bitfinex;
use App\Models\Currency;
use App\Models\UserWallet;
use App\Models\TransactionHistory;
use App\Models\LockedSavingsSetting;
use App\Models\LockedSaving;

class TradeController extends Controller
{
    public function index(Request $request)
    {
        if(isset($request->type)){
            $data = [
                'type' => $request->type
            ];
            return view('user.trade.index', $data);
        }
        return view('user.trade.index');
    }

    public function finance(Request $request)
    {
        $data['settings'] = LockedSavingsSetting::get();
        //dummy coin data grab
        $id = Currency::where('name', 'DSH')->pluck('id');
        $data['dummy_coin_balance'] = UserWallet::where('user_id', Auth::id())->where('currency_id', $id)->sum('balance');
        $data['history'] = LockedSaving::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
        $data['total'] = 0;

        return view('user.trade.finance', $data);
    }

    public function insertFinance(Request $request)
    {
//        dd($request->plan);
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
        if(!$UserWallet) {
            $UserWallet = new UserWallet();
            $UserWallet->balance = $request->buyAmount;
        }else{
            $UserWallet->balance = $UserWallet->balance+$request->buyAmount;
        }

        $leverage = 0;
        if(isset($request->leverage)){
            $leverage = $request->leverage;
            Auth::user()->derivative = Auth::user()->derivative-$request->calcBuyAmount/$leverage;
        }else{
            Auth::user()->balance = Auth::user()->balance-$request->calcBuyAmount;
        }
        Auth::user()->save();

        $UserWallet->user_id = Auth::user()->id;
        $UserWallet->currency_id = $currency->id;
        $UserWallet->save();

        $TransactionHistory= new TransactionHistory();
        $TransactionHistory->amount = $request->buyAmount;
        $TransactionHistory->equivalent_amount = $request->calcBuyAmount;
        $TransactionHistory->type = 1;
        $TransactionHistory->leverage = $leverage;
        $TransactionHistory->user_id = Auth::user()->id;
        $TransactionHistory->currency_id = $currency->id;
        $TransactionHistory->save();

        return response()->json(['status' => true]);
    }

    public function sell(Request $request)
    {
//        return response()->json(['status'=>$request->all()]);
        $currency = Currency::where('name', $request->currency)->first();

        if(!$currency) return response()->json(['status' => false]);

        $UserWallet = UserWallet::where('user_id', Auth::user()->id)->where('currency_id', $currency->id)->first();


        if(!$UserWallet) return response()->json(['status' => false]);
        
        $UserWallet->balance = $UserWallet->balance-$request->sellAmount;
        $UserWallet->save();

        Auth::user()->balance = Auth::user()->balance+$request->calcSellAmount;
        Auth::user()->save();

        $TransactionHistory= new TransactionHistory();
        $TransactionHistory->amount = $request->sellAmount;
        $TransactionHistory->equivalent_amount = $request->calcSellAmount;
        $TransactionHistory->type = 2;
        $TransactionHistory->user_id = Auth::user()->id;
        $TransactionHistory->currency_id = $currency->id;
        $TransactionHistory->save();

        return response()->json(['status' => true]);
    }

    public function getBuyOrders(Request $request)
    {
        $Bitfinex = new Bitfinex();
        return $Bitfinex->getOrderBook($request->currency);
    }
//    Maizied
    public function derivativeTransaction(Request $request)
    {

    }
}
