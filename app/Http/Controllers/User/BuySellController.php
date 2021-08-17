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
        $data['transactions'] = TransactionHistory::where('user_id', Auth::user()->id)
                                                    ->where(function($query) {
                                                        $query->where('type', 2)->orWhere('is_settlement', 1);
                                                    })
                                                    ->with('currency')
                                                    ->orderBy('id', 'DESC')
                                                    ->get();
        return view('user.buysell.index', $data);
    }
}
