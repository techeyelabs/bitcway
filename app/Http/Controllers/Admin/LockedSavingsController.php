<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        foreach($request->pstart_date as $key => $value){
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
        $data = new LockedSavingsSetting();
        $data->rate = $request->interest_rate;
        $data->duration = $request->duration;
        $data->interest_per_lot = (($request->interest_rate)*($request->duration))/365;
        $data->save();

        return redirect()->back()->with('success_message', 'successfully updated');
    }

    public function lockedSavingsDeleteAction($id)
    {
        $id = \Crypt::decrypt($id);
        $deletedata = LockedSavingsSetting::where('id', $id);
        $data['settings'] = LockedSavingsSetting::orderBy('id', 'desc')->get();
        // echo '<pre>';
        // print_r($data);
        // exit;
        $deletedata->delete();
        return view('admin.locked_savings.index', $data);
        // return route('admin-locked-savings')->with('success_message', 'successfully deleted');
    }
    public function lockedSavingsEdit($id)
    {
        $id = \Crypt::decrypt($id);
        $data['settings'] = LockedSavingsSetting::orderBy('id', 'desc')->get();
        $user = LockedSavingsSetting::where('id', $id)->orderBy('id', 'desc')->get();
        // echo '<pre>';
        // print_r($user);
        // exit;
        return view('admin.locked_savings.index', $data)->with('user', $user);
        
    }
    public function lockedSavingsEditAction(Request $request, $id)
    {
        $id = \Crypt::decrypt($id);

        LockedSavingsSetting::where('id', $id)
                ->update(['rate' => $request->interest_rate,
                         'duration'=> $request->duration,
                         'interest_per_lot'=> (($request->interest_rate)*($request->duration))/365]
                        );
        
        return redirect()->back()->with('success_message', 'successfully edited');
        
    }
}
