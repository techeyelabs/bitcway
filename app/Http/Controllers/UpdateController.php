<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use App\Libraries\Bitfinex;
use App\Models\Currency;

class UpdateController extends Controller
{
    public function getCurrencies(Request $request)
    {
        $Bitfinex = new Bitfinex();
        $currencies = $Bitfinex->getCurrencies();
        $data = [];

        foreach($currencies as $value){
            $check = Currency::where('name', $value)->first();
            if($check) continue;
            $data[] = ['name' => $value];
        }
        Currency::insert($data);
        echo count($data).' record inserted';
    }

    public function getfirstbook(Request $request)
    {
        $bids = [];
        $symbol = $request->currency;
        $PublicUrl = 'https://api.bitfinex.com/v2/book/'.$symbol.'/P0';
        $response = Http::get($PublicUrl);
        if($response->json()) return $response->json();
    }
}