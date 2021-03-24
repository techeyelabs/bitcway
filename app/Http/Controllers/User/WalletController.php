<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AdminWithdrawMessage;
use App\Models\Leverage_Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Libraries\Bitfinex;
use App\Models\Currency;
use App\Models\DepositHistory;
use App\Models\WithdrawHistory;
use App\Models\UserWallet;
use App\Models\TransactionHistory;
use App\Models\User;
use App\Models\LockedSaving;
use Carbon;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        if (isset($request->id)){
            $data['withdrawFlag'] = 1;
        }
        $data['deposit'] = DepositHistory::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get();
        $data['withdraw'] = WithdrawHistory::where('user_id', Auth::user()->id)->orderBy('id','desc')->get();
        return view('user.wallet.index', $data);
    }
    public function deposit(Request $request)
    {
        $Bitfinex = new Bitfinex();
         $data['rate'] = $Bitfinex->getRate('BTC');
//        $data['rate'] = 46000;
        return view('user.wallet.deposit', $data);
    }
    public function depositAction(Request $request)
    {
        $DepositHistory = new DepositHistory();
        $DepositHistory->user_id = Auth::user()->id;
        $DepositHistory->amount = $request->amount;
        $DepositHistory->equivalent_amount = $request->amount*$request->rate;
        $DepositHistory->save();
        return response()->json(['status' => true]);
    }
    public function withdraw(Request $request)
    {

        $withdrawnotification["notification"] = AdminWithdrawMessage::first();
        return view('user.wallet.withdraw', $withdrawnotification);
    }
    public function withdrawAction(Request $request)
    {
        $WithdrawHistory = new WithdrawHistory();
        $WithdrawHistory->user_id = Auth::user()->id;
        $WithdrawHistory->amount = $request->amount;
        $WithdrawHistory->save();

        Auth::user()->balance = Auth::user()->balance-$request->amount;
        Auth::user()->save();
        
        return response()->json(['status' => true]);
    }

    public function wallets(Request $request)
    {
        $Bitfinex = new Bitfinex();
        $data['total'] = 0;
        $data['wallets'] = UserWallet::where('user_id', Auth::user()->id)->with('currency')->orderBy('id', 'DESC')->get();
        $data['user'] = UserWallet::where('user_id', Auth::user()->id)->with('user')->orderBy('id', 'DESC')->get();
        $data['leverage_wallets'] = Leverage_Wallet::where('user_id', Auth::user()->id)->with('currencyName')->orderBy('id', 'DESC')->get();
        $data['transactionHistory'] = Leverage_Wallet::where('user_id', Auth::user()->id)->where('leverage','>',0)->with('leveragehistory')->orderBy('id', 'DESC')->get();
        $currentTime = Carbon\Carbon::now();
        $data['finances'] = LockedSaving::where('user_id', Auth::user()->id)->where('redemption_date', '>', $currentTime)->with('LockedSaving')->orderBy('id', 'DESC')->get();

        foreach($data['wallets'] as $item){
            $data['total'] += $item->balance * (is_numeric($Bitfinex->getRate($item->currency->name)?$Bitfinex->getRate($item->currency->name): 1));
        }
        return view('user.wallet.wallets', $data);
    }

    public function derivativedeposit(Request $request)
    {
        $derivative = User::find(Auth::user()->id);
        if($request->flag == 1){
            $derivative->derivative = $derivative->derivative + $request->derivativeamount;
            $derivative->balance = $derivative->balance - $request->derivativeamount;
        } else {
            $derivative->derivative = $derivative->derivative - $request->derivativeamount;
            $derivative->balance = $derivative->balance + $request->derivativeamount;
        }
        $derivative->save();
        return redirect()->route('user-wallets');
    }
}
