<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Libraries\Bitfinex;
use App\Models\Currency;
use App\Models\UserWallet;
use App\Models\TransactionHistory;

class TradeController extends Controller
{
    public function index(Request $request)
    {
        return view('user.trade.index');
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
        
        $UserWallet->user_id = Auth::user()->id;
        $UserWallet->currency_id = $currency->id;
        $UserWallet->save();

        Auth::user()->balance = Auth::user()->balance-$request->calcBuyAmount;
        Auth::user()->save();

        $TransactionHistory= new TransactionHistory();
        $TransactionHistory->amount = $request->buyAmount;
        $TransactionHistory->equivalent_amount = $request->calcBuyAmount;
        $TransactionHistory->type = 1;
        $TransactionHistory->user_id = Auth::user()->id;
        $TransactionHistory->currency_id = $currency->id;
        $TransactionHistory->save();

        return response()->json(['status' => true]);
    }
    public function sell(Request $request)
    {
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
}
