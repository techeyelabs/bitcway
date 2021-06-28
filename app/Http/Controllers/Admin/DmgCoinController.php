<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Facades\Http;
use App\Models\DmgCoin;
use App\Models\DmgCoinPriceUpdate;
use Symfony\Component\VarDumper\Dumper\DataDumperInterface;
use App\Libraries\DataGenerator;

class DmgCoinController extends Controller
{
    public function __construct()
    {
        ini_set('max_execution_time', 900);
    }

    public function index()
    {
        $data['coin'] = DmgCoin ::where ('display_status', 2)->orderBy('id', 'desc')->get();
        return view('admin.dmg_coin.index', $data);
    }

    public function action(Request $request)
    {
        $validated = $request -> validate([
            'start_date' => 'required',
            'end_date' => 'required',
            'price_update' => 'required'
        ]);
        DmgCoin::where('display_status', 0)->delete();
        if (!empty($request -> edit_id)) {
            $DmgCoin = DmgCoin ::find($request -> edit_id, 'id');
            $DmgCoin -> start_date = $request -> start_date;
            $DmgCoin -> end_date = $request -> end_date;
            $DmgCoin -> price_update = $request -> price_update;
            $DmgCoin -> save();
        } else {
            $get_prev_row = DmgCoin ::orderBy('id', 'desc') -> first();
            if ($get_prev_row) {
                /*$date_db = new DateTime($get_prev_row -> end_date);
                $date_input = new DateTime($request -> start_date);
                $get_difference = $date_db -> diff($date_input);
                $diff = $get_difference -> days;*/
                $start = strtotime($get_prev_row -> end_date) * 1000;
                $end = strtotime($request -> start_date) * 1000;

                $diff_min = ($end - $start) / (60 * 1000);
                //dd($end.'start:'.$start." ".$diff_min."request:".$request->end_date );
                //if ($diff > 2) {
                if ($diff_min >1) {
                  /*  $start_day = strtotime($get_prev_row -> end_date . " 00:00:00 GMT") * 1000 + (24 * 3600 * 1000);
                    $end_day = strtotime($request -> start_date . " 00:00:00 GMT") * 1000 - (24 * 3600 * 1000);*/
                    $start_day = strtotime($get_prev_row -> end_date ) * 1000 + 60000;
                    $end_day = strtotime($request -> start_date ) * 1000 - 60000;

                    $normal_start_date = date('Y-m-d H:i:s', $start_day/1000);
                    $normal_end_date = date('Y-m-d H:i:s', $end_day/1000);
                    //dd($request -> start_date."and".$normal_start_date."and".$normal_end_date);
                    //$start_date = gmdate("Y-m-d", $start_day / 1000);
                    //$end_date = gmdate("Y-m-d", $end_day / 1000);

                    $missing_input = ['start_date' => $normal_start_date, 'end_date' => $normal_end_date, 'price_update' => ($request -> price_update + $get_prev_row -> price_update) / 2, 'display_status' => 1];
                    DmgCoin ::create($missing_input);
                    //dd("end is here");
                }
            } else {
                $initial_day = new DateTime('2021-05-17 00:00:00');
               /* $date_input = new DateTime($request -> start_date);
                $get_difference = $initial_day -> diff($date_input);
                $diff = $get_difference -> days;*/
                $start = strtotime('2021-05-17 00:00:00') * 1000;
                $end = strtotime($request -> start_date) * 1000;

                $diff_min = ($end - $start) / (60*1000);
                //if ($diff > 1) {
                if ($diff_min > 60000) {
                    //$start_day = strtotime('2021-05-17' . " 00:00 GMT") * 1000 + (24 * 3600 * 1000);
                    $start_day = strtotime('2021-05-17' . " 00:00:00") * 1000;
                    //$end_day = strtotime($request -> start_date . " 00:00:00 GMT") * 1000 - (24 * 3600 * 1000);
                    $end_day = strtotime($request -> start_date) * 1000 - (60 * 1000);
                    //$start_date = gmdate("Y-m-d", $start_day / 1000);
                    $start_date = date("Y-m-d H:i:s", $start_day / 1000);
                    //dd($start_date);
                   // $end_date = gmdate("Y-m-d", $end_day / 1000);
                    $end_date = date("Y-m-d H:i:s", $end_day / 1000);


                   /* $request_start_date = new DateTime($request -> start_date);
                    $request_end_date = new DateTime($request -> end_date);
                    $request_date_difference = $request_start_date -> diff($request_end_date);
                    $req_diff = $request_date_difference -> days;*/

                    $req_diff= (strtotime($request -> end_date) - strtotime($request -> start_date)) / ( 60 * 1000);


                    //$missing_input = ['start_date' => $start_date, 'end_date' => $end_date, 'price_update' => (($request -> price_update / $req_diff) * $diff), 'display_status' => 1];
                    $missing_input = ['start_date' => $start_date, 'end_date' => $end_date, 'price_update' => (($request -> price_update / $req_diff/24) * $diff_min/24), 'display_status' => 1];
                    DmgCoin ::create($missing_input);
                }
            }
            $inputs = ['start_date' => $request -> start_date, 'end_date' => $request -> end_date, 'price_update' => $request -> price_update, 'display_status' => 2];
            DmgCoin ::create($inputs);
        }
        $datagenerator = new DataGenerator();
        $datagenerator -> index();
        return redirect()
            -> route('admin-dmg-coin', app() -> getLocale())
            -> with('success_message', 'successfully updated');
    }
}
