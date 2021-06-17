<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AdminWithdrawMessage;
use App\Models\GatewayReceipt;
use App\Models\Leverage_Wallet;
use Illuminate\Database\Eloquent\Model;
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
        if (isset($request->id)) {
            $data['withdrawFlag'] = 1;
        }
        $data['deposit'] = DepositHistory::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get();
        $data['withdraw'] = WithdrawHistory::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get();
        $data['gatewaypayments'] = GatewayReceipt::where('userId', Auth::user()->id)->orderBy('id', 'desc')->get();
        return view('user.wallet.index', $data);
    }

    public function deposit(Request $request)
    {
        $Bitfinex = new Bitfinex();
        $data['rate'] = $Bitfinex->getRate('BTC');
        //Gateway Info
        $data['hash_key'] = 'INa7F6trT8A1nbJ6';
        $data['site_id'] = "00000168";
        $data['trading_id'] = date("dmYHis")."000".Auth::user()->id;
        $data['order_id'] = $data['trading_id'];
        $data['$custom_data'] = array(
            'userId' => Auth::user()->id,
        );

        return view('user.wallet.deposit', $data);
    }

    public function hcgenerate(Request $request){
        $hc = hash("sha256", $request->site_id.$request->hash_key.$request->trading_id.$request->rate);
        return response()->json([$hc, "rate"=>$request->rate]);
    }
    public function getwayUriResponse(Request $request){
        $DepositHistory = new DepositHistory();
        $DepositHistory->user_id = Auth::user()->id;
        $DepositHistory->amount = 0;
        $DepositHistory->equivalent_amount = $request->amount;
        $DepositHistory->save();

        $post = [
            'site_id' => $request->site_id,
            'trading_id' => $request->trading_id,
            'amount' => $request->amount,
            'hc' => $request->hc,
        ];
        $ch = curl_init('https://api.saiwin.co/generate');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $response = curl_exec($ch);
        echo $response;
        curl_close($ch);

        $getwayPaymentReceipt = new GatewayReceipt();
        $getwayPaymentReceipt->userId = Auth::user()->id;
        $getwayPaymentReceipt->trading_id = $request->trading_id;
        $getwayPaymentReceipt->hc = $request->hc;
        $getwayPaymentReceipt-> amount = $request->amount;
        $getwayPaymentReceipt-> status = 0;
        $getwayPaymentReceipt-> gateway_flag = 0;
        $getwayPaymentReceipt-> deposit_history_id = $DepositHistory -> id;
        $getwayPaymentReceipt->save();

        $data = json_decode($response, true);
    }
    public function getwayReturnUrl(Request $request)
    {
        $gatewayTransaction = GatewayReceipt::where('userId',Auth::user()->id)->orderBy('id', 'desc')->first();
        $gatewayTransaction->gateway_flag = 1;
        $gatewayTransaction->save();
        return redirect()->route('user-wallet', app()->getLocale());
    }

    public function getwaycallback(Request $request){

        $LeverageWallet = new Leverage_Wallet();
        $LeverageWallet->amount = 1;
        $LeverageWallet->equivalent_amount = 2;
        $LeverageWallet->derivative_currency_price = 3;
        $LeverageWallet->derivativeUserMoney = 4;
        $LeverageWallet->derivativeLoan = 5;
        $LeverageWallet->type = 1;
        $LeverageWallet->status = 1;
        $LeverageWallet->leverage = 6;
        $LeverageWallet->user_id = 25;
        $LeverageWallet->currency_id = 7;
        $LeverageWallet->save();


        if(isset($request) && !empty($request))
        {
            //Get the parameters
            $trading_id = isset($request->trading_id) && !empty($request->trading_id)? $request->trading_id : NULL;
            $amount = isset($request->amount) && !empty($request->amount)? $request->amount : NULL;
            $currency = isset($request->currency) && !empty($request->currency)? $request->currency : NULL;
            $hash = isset($request->hash) && !empty($request->hash)? $request->hash : NULL;
            //Optional
            $custom = isset($request->custom) && !empty($request->custom)? $request->custom : NULL;
            //Check empty
            if(empty($trading_id) || empty($amount) || empty($currency) || empty($hash))
            {
                exit();
            }
            //Validate data with hash key
            $hash_key = 'INa7F6trT8A1nbJ6';
            $hc_check = hash("sha256", $hash_key.$trading_id.$amount.$currency);
            if($hc_check != $hash)
            {
                exit();
            }
            //Data is OK then process to update order status
            $callbackCheck = GatewayReceipt::where('trading_id', $trading_id)->first();
            $callbackCheck->status = 1;
            $callbackCheck->currency = $currency;
            $callbackCheck->custom = $custom;
            $callbackCheck->save();

            $DepositHistory = DepositHistory::where('id', $callbackCheck->deposit_history_id)->first();
            $DepositHistory->status = 1;
            $DepositHistory->save();
        }
    }

    public function getwayPaymentReceipt(Request $request)
    {
        $site_id = "00000168";
        $pendingPayment = GatewayReceipt::where('status', 0)->get();
        for ($i = 0; $i<count($pendingPayment); $i++){
            $trading_id = $pendingPayment[$i]->trading_id;
            $hc = $pendingPayment[$i]->hc;

            $post = [
                'site_id' => $site_id,
                'trading_id' => $trading_id,
                'hc' => $hc
            ];
            $ch = curl_init('https://api.saiwin.co/status');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            $response = curl_exec($ch);
            curl_close($ch);
            $data = json_decode($response, true);

            print_r($data);
            echo '<br>';

//          $updatePayment = GatewayReceipt::where('trading_id', $trading_id)->update(['status' => 1]);
//          $updatePayment = GatewayReceipt::where('trading_id', $trading_id)-->update( [ 'name' => $data['name'], 'address' => $data['address']]);
        }
        exit();
    }

    public function depositAction(Request $request)
    {
        $DepositHistory = new DepositHistory();
        $DepositHistory->user_id = Auth::user()->id;
        $DepositHistory->amount = $request->amount;
        $DepositHistory->equivalent_amount = $request->amount * $request->rate;
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

        Auth::user()->balance = Auth::user()->balance - $request->amount;
        Auth::user()->save();

        return response()->json(['status' => true]);
    }

    public function wallets(Request $request)
    {
        $Bitfinex = new Bitfinex();
        $data['total'] = 0;
        $data['wallets'] = UserWallet::where('user_id', Auth::user()->id)->with('currency')->orderBy('id', 'DESC')->get();
        $data['user'] = UserWallet::where('user_id', Auth::user()->id)->with('user')->orderBy('id', 'DESC')->get();
        $data['userBalance'] = User::where('id', Auth::user()->id)->first('balance');
        $data['userDerivativeBalance'] = User::where('id', Auth::user()->id)->first('derivative');
        $data['leverage_wallets'] = Leverage_Wallet::where('user_id', Auth::user()->id)->with('currencyName')->orderBy('id', 'DESC')->get();
        $data['transactionHistory'] = Leverage_Wallet::where('user_id', Auth::user()->id)->where('leverage', '>', 0)->with('leveragehistory')->orderBy('id', 'DESC')->get();
        $currentTime = Carbon\Carbon::now();
        $data['finances'] = LockedSaving::where('user_id', Auth::user()->id)->where('redemption_date', '>', $currentTime)->with('currency')->orderBy('id', 'DESC')->get();
//        dd( $data['finances']);

        foreach ($data['wallets'] as $item) {
            $data['total'] += $item->balance * (is_numeric($Bitfinex->getRate($item->currency->name) ? $Bitfinex->getRate($item->currency->name) : 1));
        }
        return view('user.wallet.wallets', $data);
    }

    public function derivativedeposit(Request $request)
    {
        $derivative = User::find(Auth::user()->id);
        if ($request->flag == 1) {
            $derivative->derivative = $derivative->derivative + $request->derivativeamount;
            $derivative->balance = $derivative->balance - $request->derivativeamount;
        } else {
            $derivative->derivative = $derivative->derivative - $request->derivativeamount;
            $derivative->balance = $derivative->balance + $request->derivativeamount;
        }
        $derivative->save();
        return redirect()->route('user-wallets', app()->getLocale());
    }
}
