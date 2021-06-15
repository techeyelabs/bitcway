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

    public function getCandle($currency,$interval,$start,$end,$range)
    {
        //dd($currency);


            if($start != "" && $end != "" ){
                switch ($range) {
                    case '1h':
                        $interval_range = '1m';
                        break;
                    case '6h':
                        $interval_range = '5m';
                        break;
                    case '1D':
                        $interval_range = '15m';
                        break;
                    case '3D':
                        $interval_range = '30m';
                        break;
                    case '7D':
                        $interval_range = '1h';
                        break;
                    case '1M':
                        $interval_range = '6h';
                        break;
                    case '3M':
                        $interval_range = '12h';
                        break;
                    case '1Y':
                        $interval_range = '1D';
                        break;
                    default:
                        $interval_range = '1W';
                }
                if($currency =='tADAUSD'){
                    $get_json = file_get_contents('./dataJson/coindata.json');
                    //dd($get_json);
                    $json_data = json_decode($get_json,'true');

                    if ($get_json === false) {
                    }
                    if ($json_data === null) {
                        dd("no data here");
                    }
                    else{

                        $response = $json_data['yeardata'];

                        //array_push($array,$response);

                        foreach ($response as $key => $value) {

                            if($value["time"]>=$start &&  $value["time"]<=$end){
                                //dd("HOllagotcha");
                                $arr[]=[$value["time"], $value["open"],$value["close"],$value["high"],$value["low"]];
                            }

                        }

                        //dd("here is your data",$response);
                    }
                     $date_diff=round(($end-$start)/(1000*3600*24*365));
                    dd($date_diff);
                    //if($response->json()) return $response->json();
//            $extra_data[]=[
//                [1622177283000, 6.8741, 6.736, 10.01, 6.4117, 1233213],
//                [1622263683000, 6.236, 6.8536, 11.9707, 6.1302, 12323],
//                [1622350083000, 6.236, 6.8536, 13.9707, 6.1302, 12323]
//            ]
//            ;
//            $new_arr= array_merge($arr,$extra_data[0]);
                    //            dd($new_arr);
                    // return $new_arr;
                    return $arr;
                }
                else{
                    $response = Http::get('https://api-pub.bitfinex.com/v2/candles/trade:'.$interval_range.':'.$currency.'/hist?limit=10000&start='.$start.'&end='.$end);
                }

            }
            elseif($interval != ""){
                $response = Http::get('https://api-pub.bitfinex.com/v2/candles/trade:'.$interval.':'.$currency.'/hist?limit=10000');
            }
            else{
                $response = Http::get('https://api-pub.bitfinex.com/v2/candles/trade:1D:'.$currency.'/hist?limit=10000');
            }

            if($response->json()) return $response->json();




    }

    public function getOrderBook($currency)
    {
        $response = Http::get('https://api-pub.bitfinex.com/v2/book/'.$currency.'/P0');
        if($response->json()) return $response->json();
    }
}
