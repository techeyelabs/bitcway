<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}