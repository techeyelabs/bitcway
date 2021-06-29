<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminDeposit;
use Illuminate\Database\Eloquent\Model;
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
        $users = DepositHistory::where('status', '!=', 4)->where('status', '!=', 5)->get();
        return Datatables::of($users)
                ->editColumn('status', function ($row) {
                    if ($row->status == 1) {
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
                        $action .= '<a href="'.route('admin-deposit-change-status',['id' => $row->id, 'status' => 1, app()->getLocale()]).'" class="btn btn-sm btn-outline-success">Approve</a>';
                        $action .= ' <a href="'.route('admin-deposit-change-status',['id' => $row->id, 'status' => 2, app()->getLocale()]).'" class="btn btn-sm btn-outline-success">Cancel</a>';
                    }elseif($row->status == 2){
                        $action .= ' <a href="'.route('admin-deposit-destroy', [ $row->id,app()->getLocale()]).'" class="btn btn-sm btn-outline-danger delete-button-new">Delete</a>';
                    }
                    
                    return $action;
                })
                ->rawColumns(['status', 'action'])
                ->make();
    }

    public function changeStatus($lang, $id, $status)
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
    public function adminDeposit()
    {
        $users = User::all();
        return view('admin.deposit.admindeposit',['users'=>$users]);
    }
    public function adminDepositAction(Request $request)
    {
        $userBalance = User::where('id', $request->user_id)->first();

        $username = User::where('id', $request->user_id)->first('username');
        $useremail = User::where('id', $request->user_id)->first('email');

        $admindeposit = new AdminDeposit();
        $admindeposit->user_email = $useremail->email;
        $admindeposit->user_name = $username->username;
        $admindeposit->directdepositamount = $request->directdepositamount;
        $admindeposit->user_id = $request->user_id;
        $admindeposit->save();

        $depositAmount = doubleval($request->directdepositamount);
        $userBalance->balance = $userBalance->balance + $depositAmount;
        $userBalance->update();

        $DepositHistory = new DepositHistory();
        $DepositHistory->equivalent_amount = $depositAmount;
        $DepositHistory->status = 4;
        $DepositHistory->user_id  = $request->user_id;
        $DepositHistory->save();

        return redirect()->back();
    }

    public function adminDepositHistory()
    {
        $directDeposit = AdminDeposit::all();
        return Datatables::of($directDeposit)
            ->addColumn('name', function ($row) {
                return $row->user_name;
            })
            ->addColumn('email', function ($row) {
                return $row->user_email;
            })
            ->addColumn('directdepositamount', function ($row) {
                return $row->directdepositamount;
            })
            ->editColumn('created_at', function ($row) {
                return date('Y-m-d H:i:s', strtotime($row->created_at));
            })
            ->make();

//        return redirect()->back();
    }
}
