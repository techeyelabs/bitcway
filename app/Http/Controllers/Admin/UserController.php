<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\User;
use App\Models\UserWallet;
use App\Libraries\Bitfinex;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.user.index');
    }

    public function data(Request $request)
    {
        $users = User::all();
        return Datatables::of($users)
                // ->editColumn('status', function ($row) {
                //     if ($row->status==1) {
                //         return '<span class="text-success">Active</span>';
                //     }
                //     else{
                //         return '<span class="text-danger">Inactive</span>';
                //     }
                // })
                
                ->addColumn('name', function ($row) {
                    return $row->first_name.' '.$row->last_name;
                })
                ->addColumn('asset', function ($row) {
                    return '<a href="'.route('admin-user-wallets', [$row->id]).'" class="btn btn-sm btn-outline-info">Asset</a>';
                })
                ->editColumn('memo', function ($row) {
                    return '<a href="'.route('admin-user-memo', [$row->id]).'" class="btn btn-sm btn-outline-info">Memo</a>';
                })
                ->editColumn('created_at', function ($row) {
                    return date('d M Y', strtotime($row->created_at));
                })
                ->addColumn('action', function ($row) {
                    $action = '';
                    // $action .= ' <a href="'.route('admin-user-wallets', [$row->id]).'" class="btn btn-sm btn-outline-info">Asset</a>';

                    if($row->status == 0) $action .= ' <a href="'.route('admin-user-change-status', ['id' => $row->id, 'status' => 1]).'" class="btn btn-sm btn-outline-success">Active</a>';
                    else $action .= ' <a href="'.route('admin-user-change-status', ['id' => $row->id, 'status' => 0]).'" class="btn btn-sm btn-outline-warning">Inactive</a>';
                    // $action .= ' <a href="'.route('admin-user-destroy', [$row->id]).'" class="btn btn-sm btn-outline-danger delete-button-new">Delete</a>';
                    
                    $action .= ' <a href="'.route('admin-message-details', [$row->id]).'" class="btn btn-sm btn-outline-info">Chat</a>';

                    return $action;
                })
                ->rawColumns(['status', 'action', 'asset', 'memo'])
                ->make();
    }

    public function changeStatus($id, $status)
    {
        User::where('id', $id)->update(['status' => $status, 'is_email_verified' => true]);
        return redirect()->back()->with('success_message', 'Status changed');
    }

    public function wallets(Request $request)
    {
        $data['total'] = 0;
        $data['wallets'] = UserWallet::where('user_id', $request->id)->with('currency')->get();
        $Bitfinex = new Bitfinex();
        foreach($data['wallets'] as $item){            
            $data['total'] += $item->balance*$Bitfinex->getRate($item->currency->name);
        }
        return view('admin.user.wallets', $data);
    }

    public function memo(Request $request)
    {
        return view('admin.user.memo', ['user' => User::find($request->id)]);
    }
    public function memoAction(Request $request)
    {
        $User = User::find($request->id);
        $User->memo = $request->memo;
        $User->save();
        return redirect()->route('admin-user-list');
    }



    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->back()->with('success_message', 'Successfully Deleted.');
    }
}
