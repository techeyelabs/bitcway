<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\TransactionHistory;
use Illuminate\Support\Facades\Auth;

class BuySellController extends Controller
{
    public function index()
    {
        $data['trades'] = TransactionHistory::where('user_id', Auth::user()->id)
                                                    ->where('type', 2)
                                                    ->where('is_settlement', 0)
                                                    ->with('currency')
                                                    ->orderBy('id', 'DESC')
                                                    ->get();
        $data['derivatives'] = TransactionHistory::where('user_id', Auth::user()->id)
                                                    ->where('is_settlement', 1)
                                                    ->with('currency')
                                                    ->orderBy('id', 'DESC')
                                                    ->get();
        return view('user.buysell.index', $data);
    }
}
