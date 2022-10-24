<?php

namespace App\Http\Controllers;

use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Carbon\Carbon;


class TripController extends Controller
{

    public function getPrice(Request $request)
    {
        $priceByKm = 0;
        $priceByTime = 0;
        $string = "Your price in km is:".$priceByKm."end by time is:".$priceByTime;
        $distance=$request->input('distance');
        $startTime = Carbon::parse(str_replace('T',' ',$request->input('startDate')))->format('Y-m-d h:i A');
        $endTime = Carbon::parse(str_replace('T',' ',$request->input('endDate')))->format('Y-m-d h:i A');
//        $startTime = Carbon::parse( Carbon::createFromFormat('Y-m-d\TH:i',$request->startDate));
//        $endTime =  Carbon::parse(Carbon::createFromFormat('Y-m-d\TH:i',$request->endDate));
        //$endTime = Carbon::parse($request->input('endDate'))->format('Y:m:d');

        if ($request->get('company') == '1') {

        if ($distance > 100) {
            $inKm = 100;
            $outKm = $distance - 100;

        } else {
            $inKm = $distance;
            $outKm = 0;
        }
        $priceByKm = $inKm * 5 + $outKm * 3;

        $hours = 0;
        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);
        $diffInHours = $start->diffInHours($end);
        $hours += $diffInHours;

        $priceByTime = $hours * 4;

    }else if ($request->get('company') == '2') {

        $priceByKm = $distance * 3;
        $pricePerDay = 0;
        $hoursIn = 0;
        $hoursOut = 0;


        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);
        $totalHours = 0;


        for ($d = $start; $d < $end; $d->addHour()) {
            $totalHours++;

            $s = explode(" ", $d);
            $h1 = Carbon::parse(Carbon::createFromFormat('Y-m-d H:i A', $s[0] . ' ' . '00:00 AM'));
            $h2 = Carbon::parse(Carbon::createFromFormat('Y-m-d H:i A', $s[0] . ' ' . '06:00 AM'));

            if ($h1 <= $d && $d < $h2) {
                $hoursIn++;

            } else {
                $hoursOut++;
            }
        }
        //dd($hoursIn,$hoursOut);

        $priceByTime = $hoursIn * 12 + $hoursOut * 7;


        if ($priceByTime > 70 && $totalHours >= 24) {
            $priceByTime = $priceByTime - (6 * 12 + 18 * 7) + 70;

        } else if ($priceByTime > 70 && $totalHours < 24) {
            $priceByTime = 70;

        } else {
            $priceByTime = $hoursIn * 12 + $hoursOut * 7;
        }
        //dd($priceByTime);

        } else if ($request->get('company') == '3') {

        if ($distance > 100) {
            $inKm = 50;
            $outKm = 50;
            $outKm100 = $distance - 100;

        } else if ($distance > 50) {
            $inKm = 50;
            $outKm = $distance - 50;
            $outKm100 = 0;

        } else {
            $inKm = $distance;
            $outKm = 0;
            $outKm100 = 0;
        }
        $priceByKm = $inKm * 7 + $outKm * 5 + $outKm100 * 3;

        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);
        $hours0_7 = 0;
        $hours7_9 = 0;
        $hours16_18 = 0;
        $hoursOut = 0;


        for ($d = $start; $d < $end; $d->addHour()) {

            $s = explode(" ", $d);
            $h1 = Carbon::parse(Carbon::createFromFormat('Y-m-d H:i A', $s[0] . ' ' . '00:00 AM'));
            $h2 = Carbon::parse(Carbon::createFromFormat('Y-m-d H:i A', $s[0] . ' ' . '07:00 AM'));
            $h3 = Carbon::parse(Carbon::createFromFormat('Y-m-d H:i A', $s[0] . ' ' . '09:00 AM'));
            $h4 = Carbon::parse(Carbon::createFromFormat('Y-m-d H:i A', $s[0] . ' ' . '04:00 PM'));
            $h5 = Carbon::parse(Carbon::createFromFormat('Y-m-d H:i A', $s[0] . ' ' . '06:30 PM'));

            if ($h1 <= $d && $d < $h2) {
                $hours0_7++;

            } else if ($h2 <= $d && $d < $h3) {
                $hours7_9++;

            } else if ($h4 <= $d && $d < $h5) {
                $hours16_18++;

            } else {
                $hoursOut++;
            }
        }
        // out -- 6, 5.5, 7, 5.5, 7,  5.5, 6 = 42,5
        // 07 -- 7, 7, 7 = 21
        // 79 -- 2, 2, 2 = 6
        // 1618 -- 2.5, 2.5, 2.5 = 7.5
        $priceByTime = $hours0_7 * 10 + 8 * ($hours16_18 + $hours7_9) + $hoursOut * 5;
         //dd($priceByTime);
               }

        return view('trip',compact('priceByKm','priceByTime'));
    }

    public function price(Request $request)
    {
//        $startTime = $request->startTime;
//        $endTime = $request->endTime;
//        $distance = $request->distance;


        //                                        Skenari A
//Cmimi per kilometer eshte 5 Euro. Nese udhetimi kalon mbi 100 kilometra cmimi per kilometrat e tjera do te
// jete 3 Euro per kilometer.Cmimi per ore eshte 4 Euro per nje ore udhetimi.
//        if ($distance > 100) {
//            $inKm = 100;
//            $outKm = $distance - 100;
//
//        } else {
//            $inKm = $distance;
//            $outKm = 0;
//        }
//        $priceByKm = $inKm * 5 + $outKm * 3;
//
//
//
//        $hours = 0;
//        $start = Carbon::parse($startTime);
//        $end = Carbon::parse($endTime);
//        $diffInHours = $start->diffInHours($end);
//        $hours += $diffInHours;
//
//        $priceByTime = $hours * 4;


        //                                        Skenari B
//Cmimi per kilometer eshte 3 Euro.Nga ora 00:00 deri ne oren 06:00 cmimi eshte 12 Euro, jashte kesaj fashe
// orare cmimi per ore eshte 7 Euro.Nese cmimi kalon mbi 70 Euro atehere per qe nga momenti i nisjes se
// udhetimit per 24 oret e ardheshme udhetimi do te jete i mbuluar nga ky cmim.


//        $startTime = Carbon::createFromFormat('Y-m-d h:i A', '2022-10-11 10:00:00 AM');
//        $endTime = Carbon::createFromFormat('Y-m-d h:i A', '2022-10-14 03:00:00 PM');
//        //out -- 59
//        //in -- 18
//        $distance = 122;
//        $priceByKm = $distance * 3;
//        $priceByTime = 0;
//        $pricePerDay = 0;
//        $hoursIn = 0;
//        $hoursOut = 0;


//        $trip = ["priceByKm" => $priceByKm,
//            "priceByTime" => $priceByTime,
//            "$distance" => $distance,
//            "days" =>["start" => $startTime,
//                      "end" => $endTime,
//                      "pricePerDay" => $pricePerDay,
//                      "hoursIn" => $hoursIn,
//                      "hoursOut" => $hoursOut
//            ]
//        ];
        //13---16 out
        //14---18 out, 6 in
        //15---2 in
//
//        $start = Carbon::parse($startTime);
//        $end = Carbon::parse($endTime);
//        $dateRange = CarbonPeriod::create('2019-09-13', '2019-09-15');
//        $dates = $dateRange->toArray();
//        $days = $endTime->diffInDays($startTime);

//        if ($days) {
//            $hoursIn = $days * 6;
//            $hoursOut = $days * 18;
//        }
//        $twoDates = array("firstDate" => $dates[0],
//            "lastDate" => $dates[count($dates) - 1]);

//        foreach ($dates as $date) {
        //  $h1 = Carbon::parse(Carbon::createFromFormat('Y-m-d H:i', $request->date.' '.$request->start_time));
//
//
//            $h1 = Carbon::parse(Carbon::createFromFormat('Y-m-d H:i A', $date . ' ' . '00:00 AM'));
//            $h2 = Carbon::parse(Carbon::createFromFormat('Y-m-d H:i A', $date . ' ' . '06:00 AM'));


//                if( $priceByTime > 70){
//                    for ($d = $start; $d <= $start->addDay(); $d->addDay()) {
//                        $pricePerDay = 70;
//                    }
//            }

//                if ($h1 <= $start && $start <= $h2) {
//                    $hoursIn += $start->diffInHours($h2);
//                    $hoursOut += $h2->diffInHours($end);
//
//                } else if ($h1 <= $end && $end <= $h2) {
//                    $hoursIn += $h1->diffInHours($end);
//                    $hoursOut += $start->diffInHours($h1);
//
//                } else {
//                    $hoursOut += $start->diffInHours($end);
//                    dd($hoursOut);
//
//                }
        //   dd($hoursIn);
//                $priceByTime = $hoursIn * 12 + $hoursOut * 7;
//        if($priceByTime > 70){
//            $hoursOut = $start->diffInHours($start + $bonus);
//            }
//        dd($hoursIn);
        //dd($priceByTime);

        //                                        Skenari C
//Cmimi per kilometer eshte 7 Euro. Nese udhetimi kalon mbi 50 kilometra cmimi per kilometrat e tjera eshte
// 5 Euro. Nese udhetimi kalon 100 kilometra,cmimi per kilometrat e tjera eshte 3 Euro.Nga ora 00:00 deri ne
// oren 07:00 cmimi per kilometer eshte 10 Euro.Nga ora 07:00 deri ne oren 09:00 dhe nga ora 16:00 deri ne
// oren 18:30 cmimi eshte 8 Euro. Kurse per fashat e tjera te orarit cmimi eshte 5 Euro.

//
//        if ($distance > 50) {
//            $inKm = 50;
//            $outKm = $distance - 50;
//
//        } else {
//            $inKm = $distance;
//            $outKm = 0;
//        }
//        $priceByKm = $inKm * 7 + $outKm * 5;
//
//
//        $hours0_7 = 0;
//        $hours7_9 = 0;
//        $hours16_18 = 0;
//        $hoursOut = 0;
//        $start = Carbon::parse($startTime);
//        $end = Carbon::parse($endTime);
//        $h1 = Carbon::parse('00:00:00');
//        $h2 = Carbon::parse('07:00:00');
//        $h3 = Carbon::parse('09:00:00');
//        $h4 = Carbon::parse('16:00:00');
//        $h5 = Carbon::parse('18:30:00');
//
//        if ($h1 <= $start && $start <= $h2) {
//            $hours0_7 += $start->diffInHours($h2);
//            $hours7_9 += $h2->diffInHours($end);
//            $hours16_18 += $h3->diffInHours($end);
//            $hoursOut += $h2->diffInHours($end);
//            //dd($hours7_9);
//
//        } else   if ($h2 <= $start && $start <= $h3) {
//            $hours7_9 += $start->diffInHours($h3);
//            $hours0_7 += $h1->diffInHours($end);
//            $hours16_18 += $h3->diffInHours($end);
//            $hoursOut += $h3->diffInHours($end);
//
//        } else   if ( $h4 <= $start && $start <= $h5) {
//            $hours16_18 += $start->diffInHours($h5);
//            $hours7_9 += $h2->diffInHours($end);
//            $hours0_7 += $h1->diffInHours($end);
//            $hoursOut += $h5->diffInHours($end);
//
//        }else if ($h1 <= $end && $end <= $h2) {
//            $hours0_7 += $h1->diffInHours($end);
//            $hours7_9 += $start->diffInHours($h3);
//            $hours16_18 += $start->diffInHours($h5);
//            $hoursOut += $start->diffInHours($h1);
//
//        }else if ($h2 <= $end && $end <= $h3) {
//            $hours7_9 += $h2->diffInHours($end);
//            $hours0_7 += $start->diffInHours($h2);
//            $hours16_18 += $start->diffInHours($h5);
//            $hoursOut += $start->diffInHours($h2);
//
//        }else if ($h4 <= $end && $end <= $h5){
//            $hours16_18 += $h4->diffInHours($end);
//            $hours7_9 += $start->diffInHours($h3);
//            $hours0_7 += $start->diffInHours($h2);
//            $hoursOut += $start->diffInHours($h4);
//        } else {
//            $hoursOut += $start->diffInHours($end);
//        }
//        $priceByTime = ($hours0_7 * 10) +  8 *($hours7_9+ $hours16_18) + ($hoursOut * 5);


//        dd($priceByTime);

    }
}
