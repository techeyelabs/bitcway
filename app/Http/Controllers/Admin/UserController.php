<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminWithdrawMessage;
use App\Models\Leverage_Wallet;
use App\Models\LockedSaving;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;
use App\Models\User;
use App\Models\UserWallet;
use App\Libraries\Bitfinex;
use Carbon;
use App\Traits\CurrentMABPrice;

class UserController extends Controller
{
    use CurrentMABPrice;
    public function index()
    {
        $withdrawnotification["notification"] = AdminWithdrawMessage::first();

        return view('admin.user.index',$withdrawnotification);
    }
    public function withdrawNotification(Request $request)
        {

            $withdrawmessage = AdminWithdrawMessage::first();
            $newMessage = $request->withdrawMessage;
            $displayNewMessage = $request->checkbox ? 1 : 0 ?? 0;
            if(!isset($withdrawmessage)) {
                $withdrawmessageN = new AdminWithdrawMessage();
                $withdrawmessageN->message = $newMessage;
                $withdrawmessageN->display_message = $displayNewMessage;
                $withdrawmessageN->save();
            }else{
                $withdrawmessage->message = $newMessage;
                $withdrawmessage->display_message = $displayNewMessage;
                $withdrawmessage->save();
            }
            return redirect()->back();
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
                    return '<a href="'.route('admin-user-wallets', [app()->getLocale(), $row->id]).'" class="btn btn-sm btn-outline-info">Asset</a>';
                })
                ->editColumn('memo', function ($row) {
                    return '<a href="'.route('admin-user-memo', [app()->getLocale(), $row->id]).'" class="btn btn-sm btn-outline-info">Memo</a>';
                })
                ->editColumn('chat', function ($row) {
                    return '<a href="'.route('admin-message-details', [app()->getLocale(), $row->id]).'" class="btn btn-sm btn-outline-info">Chat</a>';
                })
                ->editColumn('created_at', function ($row) {
                    return date('d M Y', strtotime($row->created_at));
                })
                ->addColumn('action', function ($row) {
                    $action = '';
                    // $action .= ' <a href="'.route('admin-user-wallets', [$row->id, app()->getLocale()]).'" class="btn btn-sm btn-outline-info">Asset</a>';

                    if($row->status == 0) $action .= ' <a href="'.route('admin-user-change-status', [app()->getLocale(), 'id' => $row->id, 'status' => 1]).'" class="btn btn-sm btn-outline-success">Active</a>';
                    else $action .= ' <a href="'.route('admin-user-change-status', [app()->getLocale(), 'id' => $row->id, 'status' => 0]).'" class="btn btn-sm btn-outline-warning">Inactive</a>';
                    // $action .= ' <a href="'.route('admin-user-destroy', [$row->id, app()->getLocale()]).'" class="btn btn-sm btn-outline-danger delete-button-new">Delete</a>';
                    
//                    $action .= ' <a href="'.route('admin-message-details', [app()->getLocale(), $row->id]).'" class="btn btn-sm btn-outline-info">Chat</a>';

                    return $action;
                })
                ->rawColumns(['status', 'action', 'asset', 'memo', 'chat'])
                ->make();
    }

    public function changeStatus($lang, $id, $status)
    {
        User::where('id', $id)->update(['status' => $status, 'is_email_verified' => true]);
        return redirect()->back()->with('success_message', 'Status changed');
    }

    public function wallets(Request $request)
    {
        $Bitfinex = new Bitfinex();
        $user_id = $request->id;
        $data['total'] = 0;
        $data['userBalance'] = User::where('id', $user_id)->first();
        $data['wallets'] = UserWallet::where('user_id', $user_id)->with('currency')->orderBy('id', 'DESC')->get();
        foreach ($data['wallets'] as $item) {
            $data['total'] += $item->balance * (is_numeric($Bitfinex->getRate($item->currency->name) ? $Bitfinex->getRate($item->currency->name) : 1));
        }
        $data['userDerivativeBalance'] = User::where('id',$user_id)->first('derivative');
        $data['transactionHistory'] = Leverage_Wallet::where('user_id',$user_id)->where('leverage', '>', 0)->with('leveragehistory')->with('currencyName')->orderBy('id', 'DESC')->get();
        $currentTime = Carbon\Carbon::now();
        $data['current_price'] = $this->getCurrentPrice()['lastval'];
        $data['finances'] = LockedSaving::where('user_id', $user_id)->where('redemption_date', '>', $currentTime)->with('currency')->orderBy('id', 'DESC')->get();

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
        return redirect()->route('admin-user-list', app()->getLocale());
    }
    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->back()->with('success_message', 'Successfully Deleted.');
    }
}
