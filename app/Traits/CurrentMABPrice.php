<?php

namespace App\Traits;
Use Carbon\Carbon;

Trait CurrentMABPrice
{
    /********************************************************
     * TO GET THE CURRENT DAY PRICE FOR ADA FROM DATA       *
     * ******************************************************/
    public function getCurrentPrice(){
        $get_json = file_get_contents('./dataJson/1min.json');
        $json_data = json_decode($get_json, 'true');
        $current_date = strtotime(date("Y-m-d") . " 00:00:00 GMT") * 1000;
        $currentUTC = (Carbon::now('UTC')->timestamp) * 1000;
        $lastUTC = Carbon::now('UTC')->subDays(30)->timestamp * 1000;
        $find_date_index = array_search($currentUTC, array_column($json_data, 'time'));
        $dmg_response_24 = array_filter($json_data, function ($var) use ($lastUTC) {
            return ($var['time'] <= $lastUTC);
        });
        $dmg_response = array_filter($json_data, function ($var) use ($currentUTC) {
            return ($var['time'] <= $currentUTC);
        });
        $lastval = $dmg_response_24[count($dmg_response_24) - 1]['high'];
        $nowval = $dmg_response[count($dmg_response) - 1]['high'];
        if ($lastval > $nowval){
            $change = ($lastval / $nowval) * -1;
        } else {
            $change =  $nowval / $lastval;
        }
        $data = [
            'change' => $change,
            'lastval' => $dmg_response[count($dmg_response) - 1]['high']
        ];
        return $data;
        // return $json_data[$find_date_index]['open'];
    }

}
