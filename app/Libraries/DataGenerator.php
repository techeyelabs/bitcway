<?php

namespace App\Libraries;

use App\Models\DmgCoin;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DataGenerator
{
    public function index()
    {
        $get_all_row = DmgCoin ::all();
        $l = 0;
        $max_val = [];

        foreach ($get_all_row as $row) {
            $date1 = new DateTime($row['start_date']);
            $date2 = new DateTime($row['end_date']);
            $start_for_price = strtotime($row['start_date']." GMT") * 1000;
            $end = strtotime($row['end_date']) * 1000;
            $start = strtotime($row['start_date']) * 1000;
            $end = strtotime($row['end_date']) * 1000;


            $diff_min = ($end - $start) / (60 * 1000);

            $date_range[$l]['1m'] = $this -> date_generator($start, $end, $diff_min, '1m');

            if ($l == 0) {
                $response_data = Http ::get('https://api-pub.bitfinex.com/v2/candles/trade:1D:tADAUSD/hist?limit=10000');
                $key = array_search(($start_for_price - (3600 * 24 * 1000)), array_column(json_decode($response_data), 0));
                $response_original = array_slice(json_decode($response_data), $key);
                $min_val[$l] = $response_original[0][1];
                $max_val[$l] = $min_val[$l] + ($min_val[$l] * $row['price_update']) / 100;
            } else {
                $min_val[$l] = $max_val[$l - 1];
                $max_val[$l] = $min_val[$l] + ($min_val[$l] * $row['price_update']) / 100;
            }

            $min_1_generator[$l] = $this -> data_generator($min_val[$l], $max_val[$l], $date_range[$l]['1m']);
            $min_1_data[$l] = $this -> get_final_data($min_1_generator[$l]);
            $l++;
        }
        $min_1 = $this -> get_full_data($min_1_data);
        //dd(count($min_1));
        $week_json = json_encode($this -> segmented_data($min_1, 'week'), JSON_PRETTY_PRINT);
        $day_json = json_encode($this -> segmented_data($min_1, 'day'), JSON_PRETTY_PRINT);
       $hour_6_json = json_encode($this -> segmented_data($min_1, '6h'), JSON_PRETTY_PRINT);
        $hour_1_json = json_encode($this -> segmented_data($min_1, '1h'), JSON_PRETTY_PRINT);
        $min_30_json = json_encode($this -> segmented_data($min_1, '30m'), JSON_PRETTY_PRINT);
        $min_15_json = json_encode($this -> segmented_data($min_1, '15m'), JSON_PRETTY_PRINT);
        $min_5_json = json_encode($this -> segmented_data($min_1, '5m'), JSON_PRETTY_PRINT);
        $min_1_json = json_encode($min_1, JSON_PRETTY_PRINT);
        //dd(count($this -> segmented_data($min_1, 'day')));
        file_put_contents(public_path() . DIRECTORY_SEPARATOR . 'dataJson' . DIRECTORY_SEPARATOR . '7d.json', "{$week_json}");
        file_put_contents(public_path() . DIRECTORY_SEPARATOR . 'dataJson' . DIRECTORY_SEPARATOR . '1d.json', "{$day_json}");
        file_put_contents(public_path() . DIRECTORY_SEPARATOR . 'dataJson' . DIRECTORY_SEPARATOR . '6h.json', "{$hour_6_json}");
        file_put_contents(public_path() . DIRECTORY_SEPARATOR . 'dataJson' . DIRECTORY_SEPARATOR . '1h.json', "{$hour_1_json}");
        file_put_contents(public_path() . DIRECTORY_SEPARATOR . 'dataJson' . DIRECTORY_SEPARATOR . '30min.json', "{$min_30_json}");
        file_put_contents(public_path() . DIRECTORY_SEPARATOR . 'dataJson' . DIRECTORY_SEPARATOR . '15min.json', "{$min_15_json}");
        file_put_contents(public_path() . DIRECTORY_SEPARATOR . 'dataJson' . DIRECTORY_SEPARATOR . '5min.json', "{$min_5_json}");
        file_put_contents(public_path() . DIRECTORY_SEPARATOR . 'dataJson' . DIRECTORY_SEPARATOR . '1min.json', "{$min_1_json}");
    }

    /**
     * @param array $result
     * @return false|string
     */
    function get_final_data(array $result)
    {
        $final_data = [];
        $j = 0;
        foreach ($result as $key => $val) {
            $final_data[$j]['time'] = $key;
            $final_data[$j]['open'] = $val['open'];
            $final_data[$j]['close'] = $val['close'];
            $final_data[$j]['high'] = $val['high'];
            $final_data[$j]['low'] = $val['low'];
            $final_data[$j]['volume'] = $val['volume'];
            $j++;
        }
        return $final_data;
    }

    /**
     * @param $min
     * @param $max
     * @param string $countZero
     * @return float|int
     */
    public function mt_rand_float($min, $max, $countZero = '0')
    {
        $countZero = +('1' . $countZero);
        $min = floor($min * $countZero);
        $max = floor($max * $countZero);
        $rand = mt_rand($min, $max) / $countZero;
        return $rand;
    }

    /**
     * @param $min_val
     * @param $max_val
     * @param array $date
     * @param int $interval
     * @return array
     */
    public function data_generator($min_val, $max_val, array $date, $interval = 1)
    {
        $data = [];
        $prevOpen = 0;
        $incrementer = ($max_val - $min_val) / (count($date));
        foreach ($date as $key => $val) {
            if (($min_val <= $max_val && $incrementer > 0) || ($min_val >= $max_val && $incrementer < 0)) {
                $rand_flactuate = $this -> mt_rand_float(-0.001, 0.002);
                $rand = $this -> mt_rand_float(abs($min_val + $rand_flactuate), abs($min_val), '0000');
                if (count($data) == 0) {
                    $data[$val]['open'] = $rand;
                } else {
                    $data[$val]['open'] = $prevOpen;
                }
                $data[$val]['close'] = $this -> mt_rand_float(($rand - ($min_val * (0.003 / $interval))), $rand + ($min_val * (0.004 / $interval)), '0000');
                $maxLimit = max(array($data[$val]['open'], $data[$val]['close']));
                $minLimit = min(array($data[$val]['open'], $data[$val]['close']));
                $data[$val]['high'] = $this -> mt_rand_float($maxLimit, ($maxLimit * 1.001), '0000');
                $data[$val]['low'] = $this -> mt_rand_float(($minLimit * 0.999), $minLimit, '0000');
                $data[$val]['volume'] = $this -> mt_rand_float(100, 10000, '0000');
            }
            $prevOpen = $data[$val]['close'];
            $min_val = $min_val + $incrementer + (mt_rand($incrementer * (-0.015), $incrementer * (0.015)));
        }

        return $data;
    }

    /**
     * @param $start
     * @param $end
     * @param $days_diff
     * @param $interval
     * @return array
     */
    public function date_generator($start, $end, $days_diff, $interval)
    {
        $date = [];
        for ($i = 0; $i < $days_diff; $i++) {
            if ($start <= $end) {
                $date[$start] = $start + (1 * 60 * 1000);
                $start = $start + (1 * 60 * 1000);
            }

        }
        return $date;
    }

    /**
     * @param array $array_input
     * @return false|string
     */
    public function get_full_data(array $array_input)
    {
        $final_array = [];
        foreach ($array_input as $array) {
            $final_array = array_merge($final_array, $array);
        }
        //print_r ($final_array);
        return $final_array;
    }

    /**
     * @param array $data_array
     * @param $interval
     * @return array
     */
    public function segmented_data(array $data_array, $interval)
    {

        if ($interval == 'week') {
            $divider = (7 * 24 * 3600 * 1000);
        }
        if ($interval == 'day') {
            $divider = (24 * 3600 * 1000);
        }
        if ($interval == '6h') {
            $divider = (6 * 3600 * 1000);
        }
        if ($interval == '1h') {
            $divider = (3600 * 1000);
        }
        if ($interval == '30m') {
            $divider = (30 * 60 * 1000);
        }
        if ($interval == '15m') {
            $divider = (15 * 60 * 1000);
        }
        if ($interval == '5m') {
            $divider = (5 * 60 * 1000);
        }
        $flag = false;
        $array = [];
        $high = 0;
        $low = 1000;
        $volume = 0;
        $firstVal = $data_array[0]['time'];
        $startIndex = 0;
        $iteration = 0;
        //dd($divider);
        foreach ($data_array as $insider) {
            if ($insider["high"] > $high) {
                $high = $insider["high"];
            }
            if ($insider["low"] < $low) {
                $low = $insider["low"];
            }
           /* if($insider['time'] > 1624924800000 && $insider['time'] < 1625184000000){
                echo $insider['time'];
                echo "<br>";
            }*/
            if ($insider['time'] % $divider == 0) {
                $lastVal = $insider['time'];
                if ($insider["time"] >= $firstVal && $insider["time"] <= $lastVal) {
                    if ($insider["high"] > $high) {
                        $high = $insider["high"];
                    }
                    if ($insider["low"] < $low) {
                        $low = $insider["low"];
                    }
                    $volume = $insider["volume"] + $volume;
                }
                $LastIndex = $iteration;
                $maxLimit = max(array($data_array[$startIndex]["open"], $data_array[$LastIndex]["close"]));
                $minLimit = min(array($data_array[$startIndex]["open"], $data_array[$LastIndex]["close"]));
                $high = $this -> mt_rand_float($maxLimit, ($maxLimit * 1.019), '0000');
                $low = $this -> mt_rand_float(($minLimit * 0.945), $minLimit, '0000');
                $insiderPacket = [
                    "time" => $lastVal,
                    "open" => $data_array[$startIndex]["open"],
                    "close" => $data_array[$LastIndex]["close"],
                    "high" => $high,
                    "low" => $low,
                    "volume" => $volume,
                ];
                $array[$iteration] = $insiderPacket;
                $high = 0;
                $low = 1000;
                $volume = 0;
                $startIndex = $iteration;
                $firstVal = $insider['time'];
            }
            $iteration++;
        }
        return array_values($array);
    }
}