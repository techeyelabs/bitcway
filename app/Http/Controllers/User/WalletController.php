<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Libraries\Bitfinex;
use App\Models\Currency;
use App\Models\DepositHistory;
use App\Models\WithdrawHistory;
use App\Models\UserWallet;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        $data['deposit'] = DepositHistory::where('user_id', Auth::user()->id)->get();
        $data['withdraw'] = WithdrawHistory::where('user_id', Auth::user()->id)->get();
        return view('user.wallet.index', $data);
    }
    public function deposit(Request $request)
    {
        $Bitfinex = new Bitfinex();
        // $data['rate'] = $Bitfinex->getRate('BTC');
        $data['rate'] = 46000;
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
        return view('user.wallet.withdraw');
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
        $data['wallets'] = UserWallet::where('user_id', Auth::user()->id)->with('currency')->get();
        foreach($data['wallets'] as $item){         
            $data['total'] += $item->balance*$Bitfinex->getRate($item->currency->name);
        }
        return view('user.wallet.wallets', $data);
    }
}
