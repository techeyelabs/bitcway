<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Yajra\Datatables\Datatables;

use App\Models\DepositHistory;
use App\Models\Currency;
use App\Models\UserWallet;
use App\Models\User;

class DepositController extends Controller
{
    public function index()
    {
        return view('admin.deposit.index');
    }

    public function data(Request $request)
    {
        $users = DepositHistory::all();
        return Datatables::of($users)
                ->editColumn('status', function ($row) {
                    if ($row->status==1) {
                        return '<span class="text-success">Approved</span>';
                    }
                    elseif ($row->status==2) {
                        return '<span class="text-warning">Declined</span>';
                    }
                    else{
                        return '<span class="text-danger">Pending</span>';
                    }
                })
                
                ->addColumn('name', function ($row) {
                    return $row->user->first_name.' '.$row->user->last_name;
                })
                ->addColumn('email', function ($row) {
                    return $row->user->email;
                })
                ->editColumn('created_at', function ($row) {
                    return date('d M Y', strtotime($row->created_at));
                })
                ->addColumn('action', function ($row) {
                    $action = '';
                    if($row->status == 0) {
                        $action .= '<a href="'.route('admin-deposit-change-status',['id' => $row->id, 'status' => 1]).'" class="btn btn-sm btn-outline-success">Approve</a>';
                        $action .= ' <a href="'.route('admin-deposit-change-status',['id' => $row->id, 'status' => 2]).'" class="btn btn-sm btn-outline-success">Cancel</a>';
                    }elseif($row->status == 2){
                        $action .= ' <a href="'.route('admin-deposit-destroy', [$row->id]).'" class="btn btn-sm btn-outline-danger delete-button-new">delete</a>';
                    }
                    
                    return $action;
                })
                ->rawColumns(['status', 'action'])
                ->make();
    }

    public function changeStatus($id, $status)
    {
        $DepositHistory = DepositHistory::find($id);
        $DepositHistory->status = $status;
        $DepositHistory->save();
        if($status == 1){
            $User = User::find($DepositHistory->user_id);
            $User->balance += $DepositHistory->equivalent_amount;
            $User->save();
        }
        // $currency_id = Currency::where('name', 'BTC')->first();
        // $check = UserWallet::where('user_id', $DepositHistory->user_id)->where('currency_id', $currency_id->id)->first();
        // if($check){
        //     $check->balance += $DepositHistory->amount;
        // }else{
        //     $check = new UserWallet();
        //     $check->balance = $DepositHistory->amount;
        //     $check->user_id = $DepositHistory->user_id;
        //     $check->currency_id = $currency_id->id;
        // }
        // $check->save();
        return redirect()->back()->with('success_message', 'Status changed');
    }

    public function destroy($id)
    {
        DepositHistory::find($id)->delete();
        return redirect()->back()->with('success_message', 'Successfully Deleted.');
    }
}
