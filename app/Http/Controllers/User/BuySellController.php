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
        $data['transactions'] = TransactionHistory::where('user_id', Auth::user()->id)->with('currency')->get();
        return view('user.buysell.index', $data);
    }
}
