<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\DmgCoin;
use App\Models\DmgCoinPriceUpdate;

class DmgCoinController extends Controller
{
    public function index()
    {
        $data['periods'] = DmgCoinPriceUpdate::all();
        $data['coin'] = DmgCoin::first();
        return view('admin.dmg_coin.index', $data);
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
        foreach($request->pstart_date as $key => $value){
            $DmgCoinPriceUpdate = new DmgCoinPriceUpdate();
            $DmgCoinPriceUpdate->start_date = $value;
            $DmgCoinPriceUpdate->end_date = $request->pend_date[$key];
            $DmgCoinPriceUpdate->price_update = $request->pprice_update[$key];
            $DmgCoinPriceUpdate->save();
        }

        return redirect()->back()->with('success_message', 'successfully updated');
    }
}
