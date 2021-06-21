<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;


use App\Models\LockedSavingsSetting;

class LockedSavingsController extends Controller
{
    public function index()
    {
        $data['settings'] = LockedSavingsSetting::orderBy('id', 'desc')->get();
        return view('admin.locked_savings.index', $data);
    }

    public function action(Request $request)
    {
        $validated = $request->validate([
            'start_price' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'price_update' => 'required'
        ]);
        $DmgCoin = DmgCoin::first();
        $DmgCoin->start_price = $request->start_price;
        $DmgCoin->start_date = $request->start_date;
        $DmgCoin->end_date = $request->end_date;
        $DmgCoin->price_update = $request->price_update;
        $DmgCoin->save();

        DmgCoinPriceUpdate::truncate();
        foreach ($request->pstart_date as $key => $value) {
            $DmgCoinPriceUpdate = new DmgCoinPriceUpdate();
            $DmgCoinPriceUpdate->start_date = $value;
            $DmgCoinPriceUpdate->end_date = $request->pend_date[$key];
            $DmgCoinPriceUpdate->price_update = $request->pprice_update[$key];
            $DmgCoinPriceUpdate->save();
        }

        return redirect()->back()->with('success_message', 'successfully updated');
    }

    public function lockedSavingsSettings(Request $request)
    {
        if($request->selectCoinName == "MAB") {
            $request->selectCoinName = "ADA";
        }
        $currency = Currency::where('name', $request->selectCoinName)->first();
        $data = new LockedSavingsSetting();
        $data->currency_id = $currency->id;
        $data->lot_size = $request->lotSize;
        $data->duration_1 = $request->duration1;
        $data->duration_2 = $request->duration2;
        $data->duration_3 = $request->duration3;
        $data->duration_4 = $request->duration4;
        $data->rate_1 = $request->rate1;
        $data->rate_2 = $request->rate2;
        $data->rate_3 = $request->rate3;
        $data->rate_4 = $request->rate4;
        // $data->interest_per_lot = (($request->interest_rate)*($request->duration))/365;
        $data->save();

        return redirect()->back()->with('success_message', 'successfully updated');
    }

    public function lockedSavingsDeleteAction($id)
    {
        $id = \Crypt::decrypt($id);
        $deletedata = LockedSavingsSetting::where('id', $id);
        $data['settings'] = LockedSavingsSetting::orderBy('id', 'desc')->get();
        $deletedata->delete();
//        return view('admin.locked_savings.index', $data);
//         return route('admin-locked-savings')->with('success_message', 'successfully deleted');
        return redirect()->back();
    }

    public function lockedSavingsEdit($id)
    {
        $id = \Crypt::decrypt($id);
        $data['settings'] = LockedSavingsSetting::orderBy('id', 'desc')->get();
        $user = LockedSavingsSetting::where('id', $id)->with('currency')->orderBy('id', 'desc')->get();
        return view('admin.locked_savings.index', $data)->with('user', $user);
//        return redirect()->back()->with('user', $user);

    }

    public function lockedSavingsEditAction(Request $request, $id)
    {
        $id = \Crypt::decrypt($id);
        $currency = Currency::where('name', $request->selectCoinName)->first();

        LockedSavingsSetting::where('id', $id)
            ->update(['currency_id' => $currency->id,
                    'lot_size' => $request->lotSize,
                    'duration_1' => $request->duration1,
                    'duration_2' => $request->duration2,
                    'duration_3' => $request->duration3,
                    'duration_4' => $request->duration4,
                    'rate_1' => $request->rate1,
                    'rate_2' => $request->rate2,
                    'rate_3' => $request->rate3,
                    'rate_4' => $request->rate4,
                    'interest_per_lot' => (($request->interest_rate) * ($request->duration)) / 365]
            );

        return redirect()->back()->with('success_message', 'successfully edited');

    }
}
