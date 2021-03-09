<?php

namespace App\Libraries;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class Bitfinex
{
    private $PublicUrl = 'https://api-pub.bitfinex.com/';
    private $AuthenticatedUrl = 'https://api.bitfinex.com/';
    private $Version = 'v2';

    public function getCurrencies()
    {
        $response = Http::get($this->PublicUrl.$this->Version.'/conf/pub:list:currency');
        if(count($response->json()) > 0) return $response->json()[0];
        return [];
    }
    public function getRate($coin1, $coin2 = 'USD')
    {
        $response = Http::post($this->PublicUrl.$this->Version.'/calc/fx', [
            'ccy1' => $coin1,
            'ccy2' => $coin2,
        ]);
        if($response->json()) return $response->json()[0];
        return false;
    }

    public function getCandle($currency)
    {
        $response = Http::get('https://api-pub.bitfinex.com/v2/candles/trade:30m:'.$currency.'/hist?limit=1200');
        if($response->json()) return $response->json();
    }

    public function getOrderBook($currency)
    {
        $response = Http::get('https://api-pub.bitfinex.com/v2/book/'.$currency.'/P0');
        if($response->json()) return $response->json();
    }
}
