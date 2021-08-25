<?php

namespace App\Libraries;

use App\Libraries\DataGenerator;
use App\Models\DmgCoin;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Redis;

class Bitfinex
{
    private $PublicUrl = 'https://api-pub.bitfinex.com/';
    private $PublicTicker = 'https://api.bitfinex.com/';
    private $AuthenticatedUrl = 'https://api.bitfinex.com/';
    private $Version = 'v2';
    private $version1 = 'v1';

    private function handleCache($type)
    {
        if(!Redis::exists($type)) {
            $get_content = file_get_contents('./dataJson/'.$type.'.json');
            Redis::set($type, $get_content);
        }
        return 0;
    }

    public function getCurrencies()
    {
        $response = Http ::get($this -> PublicUrl . $this -> Version . '/conf/pub:list:currency');
        if (count($response -> json()) > 0) return $response -> json()[0];
        return [];
    }

    public function getRate($coin1, $type = null, $coin2 = 'USD')
    {
        $response = Http ::get($this -> PublicTicker . $this -> version1 . '/pubticker/'.strtolower($coin1).'usd');
        if ($type != null){
            // $response = Http ::post($this -> PublicUrl . $this -> Version . '/calc/fx', [
            //     'ccy1' => $coin1,
            //     'ccy2' => $coin2,
            // ]);
            if ($response -> json()) return $response -> json()["last_price"];
            return false;
        } else {
            if ($type == 'buy'){
                if ($response -> json()) return $response -> json()["ask"];
            } else {
                if ($response -> json()) return $response -> json()["bid"];
            }
            return false;
        }
    }

    public function getRateBuySell($type, $coin1, $coin2 = 'USD'){
        $coin1 = ($coin1 == 'MAB')?'ADA':$coin1;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://api-pub.bitfinex.com/v2/tickers?symbols=t".$coin1."USD");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);
        $final_result = json_decode($result);
        if ($type == 'buy'){
            return $final_result[0][3];
        } else {
            return $final_result[0][1];
        }
    }

    public function getCandle($currency, $interval, $start, $end, $range)
    {
        $get_start_date_for_coin = DmgCoin ::select('start_date') -> where('name', 'DMGCoin') -> first();
        if (!$get_start_date_for_coin) {
            $get_start_date_for_coin = new DmgCoin();
            $get_start_date_for_coin -> start_date = '2021-05-17 00:00:00 GMT';
            $get_start_date_for_coin -> end_date = '2021-06-30 23:59:59 GMT';
            $get_start_date_for_coin -> price_update = 40.00;
            $get_start_date_for_coin -> display_status = 0;
            $get_start_date_for_coin -> save();

            $datagenerator = new DataGenerator();
            $datagenerator -> index();
        }

        if ($get_start_date_for_coin) {
            //Entry for current_date
            $get_last_date_for_coin = DmgCoin ::where('name', 'DMGCoin') -> orderBy('id', 'desc') -> first();

            if ($get_last_date_for_coin -> end_date < Carbon::today('UTC')) {
                $adjustmentRowStart = date("Y-m-d H:i:s", strtotime("+1 minutes", strtotime($get_last_date_for_coin -> end_date)));
//                $adjustmentRowEnd = date("Y-m-d 23:59:00");
                $adjustmentRowEnd = Carbon::tomorrow('UTC');;

                //Missing price increase calculation
                $startoflast = strtotime($get_last_date_for_coin -> start_date);
                $endoflast = strtotime($get_last_date_for_coin -> end_date);
                $datediff = $endoflast - $startoflast;
                $diffInDays = round($datediff / (60 * 60 * 24));
                $increasePerDay = (($get_last_date_for_coin -> price_update) / $diffInDays);

                $DmgCoin = new DmgCoin();
                $DmgCoin -> start_date = $adjustmentRowStart;
                $DmgCoin -> end_date = $adjustmentRowEnd;
                $DmgCoin -> price_update = $increasePerDay;
                $DmgCoin -> display_status = 1;
                $DmgCoin -> save();

                $datagenerator = new DataGenerator();
                $datagenerator -> index();
            }
            $dmg_start_date = strtotime($get_start_date_for_coin -> start_date . " GMT") * 1000;
            $date_before_start_date = $dmg_start_date - (3600 * 24 * 1000);
        }

        $current_date = strtotime(date("Y-m-d") . " 00:00:00 GMT") * 1000;
        $currentUTC = (Carbon::now('UTC')->timestamp) * 1000;
        if ($start != "" && $end != "") {
            switch ($range) {
                case '1h':
                    $interval_range = '1m';
                    $this->handleCache('1min');
                    $get_json = Redis::get('1min');
                    $json_data = json_decode($get_json, 'true');
                    break;
                case '6h':
                    $interval_range = '5m';
                    $this->handleCache('5min');
                    $get_json = Redis::get('5min');
                    $json_data = json_decode($get_json, 'true');
                    break;
                case '1D':
                    $interval_range = '15m';
                    $this->handleCache('15min');
                    $get_json = Redis::get('15min');
                    $json_data = json_decode($get_json, 'true');
                    break;
                case '3D':
                    $interval_range = '30m';
                    $this->handleCache('30min');
                    $get_json = Redis::get('30min');
                    $json_data = json_decode($get_json, 'true');
                    break;
                case '7D':
                    $interval_range = '1h';
                    $this->handleCache('1h');
                    $get_json = Redis::get('1h');
                    $json_data = json_decode($get_json, 'true');
                    break;
                case '1M':
                    $interval_range = '6h';
                    $this->handleCache('6h');
                    $get_json = Redis::get('6h');
                    $json_data = json_decode($get_json, 'true');
                    break;
                case '3M':
                    $interval_range = '6h';
                    $this->handleCache('6h');
                    $get_json = Redis::get('6h');
                    $json_data = json_decode($get_json, 'true');
                    break;
                case '1Y':
                    $interval_range = '1D';
                    $this->handleCache('1d');
                    $get_json = Redis::get('1d');
                    $json_data = json_decode($get_json, 'true');
                    break;
                case '3Y':
                    $interval_range = '7D';
                    $this->handleCache('7d');
                    $get_json = Redis::get('7d');
                    $json_data = json_decode($get_json, 'true');
                    break;
                default:
                    $interval_range = '1W';
            }

            if ($currency == 'tADAUSD') {
                if ($get_json === false) {
                    dd("not found");
                }
                if ($json_data === null) {
                    dd("no data here");
                } else {
                    $response = $json_data;
                    $response_original = Http ::get('https://api-pub.bitfinex.com/v2/candles/trade:' . $interval_range . ':' . $currency . '/hist?limit=10000&start=' . $start . '&end=' . $end);
                    $end_key = array_search($date_before_start_date, array_column(json_decode($response_original), 0));
                    $response_end = array_slice(json_decode($response_original), $end_key);
                    $start_key = array_search($start, array_column($response_end, 0));
                    //$find_date_index = array_search($current_date, array_column($response, 'time'));
                    //$dmg_response = array_slice($response, 0, $find_date_index, true);
                    $dmg_response = array_filter($response, function ($var) use ($currentUTC) {
                        return ($var['time'] <= $currentUTC);
                    });


                    if ($range == '3Y') {
                        $data = json_decode($response_original);
                        foreach ($data as $d) {
                            if ($d['0'] <= $date_before_start_date) {
                                $da[] = $d;
                            }
                        }
                        foreach ($response as $r) {
                            if ($r['time'] <= $current_date) {
                                $dmg_ar[] = array_values($r);
                            }
                        }
                    }
                    if ($range == '1M' || $range == '7D' || $range == '3D' || $range == '1D' || $range == '6h' || $range == '1h') {
                        foreach ($response as $key => $value) {
                            if ($value["time"] >= $start && $value["time"] <= $currentUTC) {
                                $arr[] = [$value["time"], $value["open"], $value["close"], $value["high"], $value["low"]];
                            }
                        }
                        return $arr;
                    }

                    if ($start_key) {
                        $response_start = array_slice(json_decode($response_original), $start_key);
                    } else {
                        $response_start = $response_end;
                    }

                    foreach ($dmg_response as $d) {
                        $data_ar[] = array_values($d);
                    }
                    if ($interval_range == '7D') {
                        $response = array_merge($da, $dmg_ar);
                    } else {
                        $response = array_merge($response_start, $data_ar);
                    }
                    return $response;
                }
                $date_diff = round(($end - $start) / (1000 * 3600 * 24 * 365));
            } else {
                $response = Http ::get('https://api-pub.bitfinex.com/v2/candles/trade:' . $interval_range . ':' . $currency . '/hist?limit=10000&start=' . $start . '&end=' . $end);
            }

        } elseif ($interval != "") {
            if ($currency == 'tADAUSD') {
                switch ($interval) {
                    case '1m':
                        $this->handleCache('1min');
                        $get_json = Redis::get('1min');
                        $json_data = json_decode($get_json, 'true');
                        break;
                    case '15m':
                        $this->handleCache('15min');
                        $get_json = Redis::get('15min');
                        $json_data = json_decode($get_json, 'true');
                        break;
                    case '30m':
                        $this->handleCache('30min');
                        $get_json = Redis::get('30min');
                        $json_data = json_decode($get_json, 'true');
                        break;
                    case '1h':
                        $this->handleCache('1h');
                        $get_json = Redis::get('1h');
                        $json_data = json_decode($get_json, 'true');
                        break;
                    case '6h':
                        $this->handleCache('6h');
                        $get_json = Redis::get('6h');
                        $json_data = json_decode($get_json, 'true');
                        break;
                    default:
                        $this->handleCache('7d');
                        $get_json = Redis::get('7d');
                        $json_data = json_decode($get_json, 'true');
                }
                $response_data = Http ::get('https://api-pub.bitfinex.com/v2/candles/trade:' . $interval . ':' . $currency . '/hist?limit=10000');
                $key = array_search($date_before_start_date, array_column(json_decode($response_data), 0));
                $response_original = array_slice(json_decode($response_data), $key);
                //$find_date_index = array_search($current_date, array_column($json_data, 'time'));
                //$dmg_response = array_slice($json_data, 0, $find_date_index, true);
                $dmg_response = array_filter($json_data, function ($var) use ($currentUTC) {
                    return ($var['time'] <= $currentUTC);
                });

                foreach ($dmg_response as $d) {
                    $data_ar[] = array_values($d);
                }
                if ($key) {
                    $response = array_merge($response_original, $data_ar);
                } else {
                    $response = $data_ar;
                }
                if ($interval == '1m'){
                    $response = array_splice($response, 0, count($response) - 4);
                }

                return $response;

            } else {
                $response = Http ::get('https://api-pub.bitfinex.com/v2/candles/trade:' . $interval . ':' . $currency . '/hist?limit=10000');
            }
        } else {
            if ($currency == 'tADAUSD') {
                $response_data = Http ::get('https://api-pub.bitfinex.com/v2/candles/trade:1D:' . $currency . '/hist?limit=10000');
                $key = array_search($date_before_start_date, array_column(json_decode($response_data), 0));
                $response_original = array_slice(json_decode($response_data), $key);
                $get_json = file_get_contents('./dataJson/1d.json');
                $json_data = json_decode($get_json, 'true');
                $find_date_index = array_search($current_date, array_column($json_data, 'time'));

                // $dmg_response = array_slice($json_data, 0, $find_date_index, true);
                $dmg_response = array_filter($json_data, function ($var) use ($currentUTC) {
                    return ($var['time'] <= $currentUTC);
                });

                foreach ($dmg_response as $d) {
                    $data_ar[] = array_values($d);
                }
                $response = array_merge($response_original, $data_ar);

                return $response;
            } else {
                $response = Http ::get('https://api-pub.bitfinex.com/v2/candles/trade:1D:' . $currency . '/hist?limit=10000');
            }
        }
        if ($response -> json()) return $response -> json();
    }

    public function getOrderBook($currency)
    {
        $response = Http ::get('https://api-pub.bitfinex.com/v2/book/' . $currency . '/P0');
        if ($response -> json()) return $response -> json();
    }
}
