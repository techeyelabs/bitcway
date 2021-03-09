<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\WithdrawHistory;
use App\Models\User;

class WithdrawController extends Controller
{
    public function index()
    {
        return view('admin.withdraw.index');
    }

    public function data(Request $request)
    {
        $users = WithdrawHistory::all();
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
                        $action .= '<a href="'.route('admin-withdraw-change-status',['id' => $row->id, 'status' =>1]).'" class="btn btn-sm btn-outline-success">Approve</a>';
                        $action .= ' <a href="'.route('admin-withdraw-change-status',['id' => $row->id, 'status' => 2]).'" class="btn btn-sm btn-outline-warning">Decline</a>';
                    }elseif($row->status == 2){
                        $action .= ' <a href="'.route('admin-withdraw-destroy', [$row->id]).'" class="btn btn-sm btn-outline-danger delete-button-new">Delete</a>';
                    }
                    return $action;
                    
                })
                ->rawColumns(['status', 'action'])
                ->make();
    }

    public function changeStatus($id, $status)
    {
        $WithdrawHistory = WithdrawHistory::find($id);
        $WithdrawHistory->status = $status;
        $WithdrawHistory->save();
        if($status == 2){
            $User = User::find($WithdrawHistory->user_id);
            $User->balance += $WithdrawHistory->amount;
            $User->save();
        }
        return redirect()->back()->with('success_message', 'Status changed');
    }

    public function destroy($id)
    {
        WithdrawHistory::find($id)->delete();
        return redirect()->back()->with('success_message', 'Successfully Deleted.');
    }
}
