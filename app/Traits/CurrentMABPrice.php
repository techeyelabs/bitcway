<?php

namespace App\Traits;

Trait CurrentMABPrice
{
    /********************************************************
     * TO GET THE CURRENT DAY PRICE FOR ADA FROM DATA       *
     * ******************************************************/
    public function getCurrentPrice(){
        $get_json = file_get_contents('./dataJson/1d.json');
        $json_data = json_decode($get_json, 'true');
        $current_date = strtotime(date("Y-m-d") . " 00:00:00 GMT") * 1000;
        $find_date_index = array_search($current_date, array_column($json_data, 'time'));
        return $json_data[$find_date_index]['open'];
    }

}
