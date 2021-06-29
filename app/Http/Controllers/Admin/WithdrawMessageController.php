<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Yajra\Datatables\Datatables;
use App\Models\User;

class WithdrawMessageController extends Controller
{
    public function index()
    {
        return view('admin.deposit.index');
    }

    public function destroy($id)
    {
        $Update_message_column = User ::find($id);
        $Update_message_column -> withdraw_message = null;
        $Update_message_column -> save();
        return redirect() -> back() -> with('success_message', 'Successfully Deleted.');
    }

    public function adminWithdrawMessage()
    {
        $users = User ::whereNull('withdraw_message') -> get();
        return view('admin.withdraw-message.adminwithdrawmessage', ['users' => $users]);
    }

    public function adminWithdrawMessageAction(Request $request)
    {
        $withdrawMessage = User ::find($request -> user_id, 'id');
        $withdrawMessage -> withdraw_message = $request -> withdraw_message;
        $withdrawMessage -> save();

        return redirect() -> back() -> with('success_message', 'Successfully Updated.');
    }

    public function adminWithdrawMessageHistory()
    {
        $withdrawMessage = User ::whereNotNull('withdraw_message') -> get();
        return Datatables ::of($withdrawMessage)
            -> addColumn('name', function ($row) {
                return $row -> username;
            })
            -> addColumn('email', function ($row) {
                return $row -> email;
            })
            -> addColumn('message', function ($row) {
                return $row -> withdraw_message;
            })
            -> addColumn('action', function ($row) {
                $action = ' <a href="' . route('admin-withdraw-message-destroy', [$row -> id, app() -> getLocale()]) . '" class="btn btn-sm btn-outline-danger delete-button-new">delete</a>';
                return $action;
            })
            -> make();
    }
}
