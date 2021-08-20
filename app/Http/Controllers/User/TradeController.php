<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DerivativeSell;
use App\Models\LimitBuySell;
use App\Models\LeverageSettlementLimit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;

use App\Traits\CurrentMABPrice;

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
use Validator;
use function PHPUnit\Framework\countOf;

class TradeController extends Controller
{
    use CurrentMABPrice;
    public function index(Request $request)
    {

        $data['userInfo'] = UserWallet::where('user_id', Auth::user()->id)->get();
        $currency = array();
        $data['currency'] = 0;
        $resp = $this->getCurrentPrice();
        $data['current_price'] = $resp['lastval'];
        $data['change'] = $resp['change'] / 100;

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
            return view('user.trade.index', $data);
        }
        return view('user.trade.index', $data);
    }

    public function finance(Request $request)
    {
        $data['settings'] = LockedSavingsSetting::get();
        //dummy coin data grab
        //$id = Currency::where('name', 'ADA')->pluck('id');
        $wallet = UserWallet::where('user_id', Auth::id())->groupBy('currency_id')->get();
        $coinBalance = array();
        for ($i = 0; $i<count($wallet); $i++){
            $coinBalance[$wallet[$i]->currency_id] = $wallet[$i]->balance;
        }
        $data['dummy_coin_balance'] = $coinBalance;
        $data['history'] = LockedSaving::where('user_id', Auth::user()->id)->orderBy('redemption_date', 'DESC')->get();
        $data['total'] = 0;
        $data['lockedFinanceSettings'] = LockedSavingsSetting::with('currency')->get();
        return view('user.trade.finance', $data);
    }

    public function insertFinance(Request $request)
    {
        try {

            $date = date('Y-m-d');
            $authUserId = Auth::user()->id;
            $currency = Currency::where('id', $request->coinId)->first();
            $UserWallet = UserWallet::where('user_id', $authUserId)->where('currency_id', $currency->id)->first();
            $UserWalletBalance = UserWallet::select('balance', 'currency_id')->where('user_id', $authUserId)->where('currency_id', $currency->id)->first();

            $lockedInvest = new LockedSaving();
            $lockedInvest->user_id = $authUserId;
            $lockedInvest->plan_id = $request->plan;
            $lockedInvest->lot_count = $request->lot;
            $lockedInvest->value_date = $date;
            $lockedInvest->redemption_date = date('Y-m-d', strtotime($date . ' + ' . $request->redemptionTime . ' days'));
            $lockedInvest->expected_interest = $request->expected_interest;
            $lockedInvest->status = 1;
            $lockedInvest->currency_id = $request->coinId;
            $lockedInvest->lot_size = $request->coinLotSize;
            $lockedInvest->save();

            $UserWallet->balance = $UserWalletBalance->balance - $request->totalCoin;
            $UserWallet->save();

        } catch (Exception $e){
            return route('user-wallets', app()->getLocale());
        }
        return redirect()->back();
    }

    public function getChartData(Request $request)
    {
        if($request->user_currency == 'MAB'){
             $currency = 'ADA';
        }
        else{
            $currency = $request->user_currency;
        }
        if($request->interval && $request->interval!=""){

                $interval_value = $request->interval;
                $start ='';
                $end   ='';
        }
        else{
            $interval_value='';
             if(($request->start && $request->end) && ($request->start !="" && $request->end !="") ){
                 $start =  $request->start;
                 $end =  $request->end;
             }
             else{
                 $start = '';
                 $end = '';
             }
        }
        if ($request->interval == null && $request->range == null){
            $interval_value='1m';
        }
        if(empty($request->currency)) return response()->json(['status' => false]);
        $Bitfinex = new Bitfinex();
        if($request->currency == 'tMABUSD'){
            if($interval_value != ""){
                $response = $Bitfinex->getCandle('tADAUSD', $interval_value,'','','' );
            }
            elseif ($start != "" && $end != ""){
                $response = $Bitfinex->getCandle('tADAUSD', '',$start,$end,$request->range );

            }
            else{
                $response = $Bitfinex->getCandle('tADAUSD', '','','','' );
            }
        }
        else{
            if($interval_value != ""){

                $response = $Bitfinex->getCandle($request->currency,$interval_value,'','','');

            }
            elseif ($start != "" && $end != ""){

                $response = $Bitfinex->getCandle($request->currency,'', $start, $end,$request->range );
            }
            else{

                $response = $Bitfinex->getCandle($request->currency,'','','','');
            }
           // $response = $Bitfinex->getCandle($request->currency,$interval_value,$start,$end);
        }

        $balance = UserWallet::whereHas('currency', function($query) use ($currency){
            return $query->where('name', $currency);
        })->where('user_id', Auth::user()->id)->first();
        if($balance) $balance = $balance->balance;
        else $balance = 0;
        return response()->json(['status' => true, 'chartData' => $response, 'balance' => $balance]);
    }

    public function buy(Request $request)
    {
        if ($request->currency == 'MAB'){
            $request->currency = 'ADA';
        }
        $currency = Currency::where('name', $request->currency)->first();
        if(!$currency) return response()->json(['status' => false]);
        $UserWallet = UserWallet::where('user_id', Auth::user()->id)->where('currency_id', $currency->id)->first();
        //For saving Trade data in User Wallet table
        if (!isset($request->leverage)) {
            if (!$UserWallet) {
                $UserWallet = new UserWallet();
                $UserWallet->balance = $request->buyAmount;
                $UserWallet->equivalent_trade_amount = $request->calcBuyAmount;
                $UserWallet->currency_price = $request->currency_price;
            } else {
                $UserWallet->balance = $UserWallet->balance + $request->buyAmount;
                $UserWallet->equivalent_trade_amount = $UserWallet->equivalent_trade_amount + $request->calcBuyAmount;
                $UserWallet->currency_price = $UserWallet->equivalent_trade_amount / $UserWallet->balance;
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
        // derivativeLoan
        try{
            $TransactionHistory= new TransactionHistory();
            $TransactionHistory->amount = $request->buyAmount;
            $TransactionHistory->equivalent_amount = $request->calcBuyAmount;
            $TransactionHistory->derivativeUserMoney = $request->derivativeUserMoney;
            $TransactionHistory->entry_price = $request->currency_price;
            if ($request->derivativeLoan == 0){
                $TransactionHistory->derivativeLoan = 0;
            }else{
                $TransactionHistory->derivativeLoan = $request->calcBuyAmount - $request->derivativeUserMoney;
            }
            $TransactionHistory->type = 1;
            $TransactionHistory->profit = 0;
            $TransactionHistory->is_settlement = 0;
            $TransactionHistory->leverage = $leverage;
            $TransactionHistory->user_id = Auth::user()->id;
            $TransactionHistory->currency_id = $currency->id;
            $TransactionHistory->save();

            if(isset($request->leverage)) {
                $LeverageWallet = new Leverage_Wallet();
                $LeverageWallet->amount = $request->buyAmount;
                $LeverageWallet->trade_type = $request->tradeType;
                $LeverageWallet->equivalent_amount = $request->calcBuyAmount;
                $LeverageWallet->derivative_currency_price = $request->currency_price;
                $LeverageWallet->derivativeUserMoney = $request->derivativeUserMoney;
                $LeverageWallet->derivativeLoan = $request->calcBuyAmount - $request->derivativeUserMoney;
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
        if ($request->currency == 'MAB'){
            $request->currency = 'ADA';
        }
        $currency = Currency::where('name', $request->currency)->first();
        if(!$currency){
            return response()->json(['status' => true]);
        }
        DB::beginTransaction();
        try {
            $TransactionHistory = new TransactionHistory();  // History object initiation
            if (isset($request->derivativeType)) {
                $leverageWalletCurrency = Leverage_Wallet::where('id', $request->id)->get();
                $leverageSellAmount = $leverageRequestSellAmount;

                foreach ($leverageWalletCurrency as $item) {
                    $derivativeSells = new DerivativeSell();
                    $derivativeSells->type = $item->type;
                    $derivativeSells->status = $item->status;
                    $derivativeSells->user_id = $item->user_id;
                    $derivativeSells->currency_id = $item->currency_id;
                    $derivativeSells->leverage = $item->leverage;

                    if ($item->amount <= $leverageSellAmount) {
                        $buyTimeValue = $item->equivalent_amount;
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
                    $TransactionHistory->profit = $sellAmountToUserWallet - ($buyTimeValue / $item->leverage) ;
                    $TransactionHistory->entry_price = $item->derivative_currency_price;
                    $TransactionHistory->type = ($item->trade_type == 'buy')? 1: 2;
                    if ($leverageSellAmount == 0) {
                        break;
                    }
                }
            } else {
                $UserWallet = UserWallet::where('user_id', Auth::user()->id)->where('currency_id', $currency->id)->first();
                if (!$UserWallet) return response()->json(['status' => false]);

                $UserWallet->balance = $UserWallet->balance - $request->sellAmount;
                $UserWallet->save();
                if ($UserWallet->balance == 0.00000000) {
                    $UserWallet->delete();
                }

                Auth::user()->balance = Auth::user()->balance + $equivalentSellAmount;
                Auth::user()->save();
                $TransactionHistory->profit = $request->calcSellAmount - ($UserWallet->currency_price * $request->sellAmount);
                $TransactionHistory->entry_price = $UserWallet->currency_price;
                $TransactionHistory->type = 2;
            }
            $TransactionHistory->amount = $request->sellAmount;
            $TransactionHistory->equivalent_amount = $request->calcSellAmount;
            $TransactionHistory->is_settlement = (isset($request->derivativeType))? 1: 0;
            $TransactionHistory->user_id = Auth::user()->id;
            $TransactionHistory->currency_id = $currency->id;
            $TransactionHistory->save();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => false]);
        }
        DB::commit();
        return response()->json(['status' => true]);
    }

    public function derivativeSellAll(Request $request)
    {
        $Bitfinex = new Bitfinex();
            $leverageWalletCurrency = Leverage_Wallet::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get();
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

            Auth::user()->derivative = 0;
            Auth::user()->save();

        return response()->json(['status' => true]);
    }

    public function getBuyOrders(Request $request)
    {
        $Bitfinex = new Bitfinex();
        return $Bitfinex->getOrderBook($request->currency);
    }

    public function limitBuy(Request $request)
    {
        $Bitfinex = new Bitfinex();
        $getCurrentRate = $Bitfinex->getRateBuySell('sell', $request->currency);
        if ($request->currency == 'MAB'){
            $request->currency = 'ADA';
            $getCurrentRate = $this->getCurrentPrice()['lastval'];
        }
        $currency = Currency ::where('name', $request -> currency) -> first();
        $limitBuy = new LimitBuySell();
        $limitBuy -> limitType = $request -> limitType;
        $limitBuy -> priceLimit = $request -> priceLimit;
        $limitBuy -> currencyAmount = $request -> currencyAmount;
        $limitBuy -> transactionStatus = $request -> transactionStatus;
        $limitBuy -> user_id = Auth ::user() -> id;
        $limitBuy -> currency_id = $currency -> id;
        $limitBuy -> price_at_time_of_creation = $getCurrentRate;
        if (isset($request -> type)){
            $limitBuy -> type = $request -> type;
        }
        if ($request -> derivative) {
            $limitBuy -> derivative = $request -> derivative;
        }
        $limitBuy -> save();
        return response() -> json(['status' => true]);
    }
    public function limitSell(Request $request){
        $Bitfinex = new Bitfinex();
        $getCurrentRate = $Bitfinex->getRateBuySell('sell', $request->currency);
        if ($request->currency == 'MAB'){
            $request->currency = 'ADA';
            $getCurrentRate = $this->getCurrentPrice()['lastval'];
        }

        $currency = Currency::where('name', $request->currency)->first();
        $balance = UserWallet::select('balance')->where('user_id', Auth::user()->id)->where('currency_id', $currency->id)->first();
        $totalSpent = LimitBuySell::where('derivative', 0)->where('transactionStatus', 1)->where('user_id', Auth::user()->id)->where('currency_id', $currency->id)->sum('currencyAmount');
        if ($request->currencyAmount > ($balance->balance - $totalSpent)){
            return response()->json(['status' => false]);
        }

        $limitSell = new LimitBuySell();
        $limitSell->limitType = $request->limitType;
        $limitSell->priceLimit = $request->priceLimit;
        $limitSell->currencyAmount = $request->currencyAmount;
        $limitSell->transactionStatus = $request->transactionStatus;
        if (isset(Auth::user()->id)){
            $limitSell->user_id = Auth::user()->id;
        } else {
            $limitSell->user_id = $request->user_id;
        }

        $limitSell->currency_id = $currency->id;
        if(isset($request->lastPrice)){
            $limitSell->price_at_time_of_creation = $request->lastPrice;
        } else {
            $limitSell->price_at_time_of_creation = $getCurrentRate;
        }
        if($request->derivative){
            $limitSell->derivative = $request->derivative;
            $limitSell->type = "sell";
        }
        if ($request->is_from_asset) {
            $limitSell->is_from_asset = 1;
        }
        $limitSell->save();

        return response()->json(['status' => true]);
    }

    public function limitDerivativeSettlement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'currency' => 'required|string',
            'currencyAmount' => 'required',
            'priceLimit' => 'required',
            'itemId' => 'required|integer'
        ]);
        $total_booked = LeverageSettlementLimit::where('leverage_wallet_id', $request->itemId)->where('settlement_status', 0)->sum('amount');
        $totalHas = Leverage_Wallet::where('id', $request->itemId)->first();
        if ($request->currencyAmount > ($totalHas->amount - $total_booked)){
            return response()->json(['status' => false]);
        }
        if ($validator->fails()) {
            return response()->json(['status' => false]);
        }
        $Bitfinex = new Bitfinex();
        try {
            $getCurrentRate = $Bitfinex->getRateBuySell('sell', $request->currency);
            if ($request->currency == 'MAB') {
                $request->currency = 'ADA';
            }
            $currency = Currency::where('name', $request->currency)->first();  // Get currency
            $settlement = new LeverageSettlementLimit();  // Object initiation
            $settlement->user_id = Auth::user()->id;  // Assign user
            $settlement->amount = $request->currencyAmount;  // Assign crypto amount
            $settlement->limit_rate = $request->priceLimit;  // Assign limit
            $settlement->currency_id = $currency->id;  // Assign currency Id
            $settlement->price_at_time_of_creation = $request->lastPrice;  // Get rate at time of creation
            $settlement->type = $request->derivativeTradeType;  // Assign currency Id
            $settlement->leverage_wallet_id = $request->itemId;  // Assign leverage wallet id
            $settlement->save();

            return response()->json(['status' => true]);
        } catch (Exception $e) {
            return response()->json(['status' => false]);
        }
    }

    public function deleteSettlementLimit(Request $request){
        $id = $request->id;
        try{
            LeverageSettlementLimit::where('id', $id)->delete();
            return response()->json(['status' => true]);
        } catch(Exception $e) {
            return response()->json(['status' => false]);
        }
    }

    public function deleteTradeAsset(Request $request){
        $id = $request->id;
        try{
            LimitBuySell::where('id', $id)->delete();
            return response()->json(['status' => true]);
        } catch(Exception $e) {
            return response()->json(['status' => false]);
        }
    }

    public function getBuySellPendingData(Request $coinid)
    {

        if ($coinid -> coin == 'MAB') {
            $coinname = 'ADA';
        } else {
            $coinname = $coinid -> coin;
        }
        $currency = Currency ::where('name', $coinname) -> first();
        if ($coinid -> type === 'derivative') {
            $data = limitBuySell ::select('id', 'priceLimit', 'currencyAmount', 'limitType') -> where('user_id', Auth ::user() -> id) -> where('currency_id', $currency -> id) -> where('transactionStatus', 1) -> where('derivative', '>', 0) -> orderBy('created_at', 'DESC') -> get();
        } else {
            $data = limitBuySell ::select('id', 'priceLimit', 'currencyAmount', 'limitType') -> where('user_id', Auth ::user() -> id) -> where('currency_id', $currency -> id) -> where('transactionStatus', 1) -> orderBy('created_at', 'DESC') -> get();
        }
        if ($data && count($data) > 0) {
            foreach ($data as $key => $val) {
                $table_data[$key] = $val;
            }
            return response() -> json($table_data);
        } else {
            return "no data";

        }
    }

    public function limitDelete(Request $request)
    {
        $limitBuy = LimitBuySell ::find($request -> limitId);
        $limitBuy -> delete();
        return response() -> json(['status' => true]);
    }

    public function sellFromAsset(Request $request){
        dd($request);
    }

}
