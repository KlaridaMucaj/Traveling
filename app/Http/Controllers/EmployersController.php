<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Off_day;
use App\Models\Working_day;
use App\Services\EmployerService;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

class EmployersController extends Controller
{
     public function isWeekend($date)
     {
          return (date('N', strtotime($date)) >= 6);
     }

     public function calculate()
     {
         $workingDays = Working_day::query()->with('user')->get();
         $offDays = Off_Day::pluck('date')->toArray();


         $results = [];
         $months = [];
         foreach ($workingDays as $working_day) {
             $user = $working_day->user;
             $payPerHour = round($user->total_paga / 22 / 8, 2);
             $payment = $user->total_paga;

             if (!isset($results[$user->id])) {
                 $results[$user->id] = [
                     'full_name' => $user->full_name,
                     'over8hours' => 0,
                     'under8hours' => 0,
                     'total_hours' => 0,
                     'totalPayment' => 0,
                     'payPerHour' => $payPerHour,
                     'months' => []
                 ];
             }
             $monthKey = Carbon::parse($working_day->date, 'UTC')->format('Y-m');
             if (!isset($results[$user->id]['months'][$monthKey])) {
                 $results[$user->id]['months'][$monthKey] = [
                     'over8hours' => 0,
                     'under8hours' => 0,
                     'total_hours' => 0,
                     'totalPayment' => 0,
                     'weeks' => [],
                 ];
             }
             $weekKey = Carbon::parse($working_day->date, 'UTC')->weekOfMonth;
             if (!isset($results[$user->id]['months'][$monthKey]['weeks'][$weekKey])) {
                 $results[$user->id]['months'][$monthKey]['weeks'][$weekKey] = [
                     'over8hours' => 0,
                     'under8hours' => 0,
                     'total_hours' => 0,
                     'totalPayment' => 0,
                 ];
             }

             if ($working_day->hours > 8) {
                 $inHours = 8;
                 $outHours = $working_day->hours - 8;

             } else {
                 $inHours = $working_day->hours;
                 $outHours = 0;
             }

             if (in_array($working_day->date, $offDays)) {
                 $inKoef = 1.5;
                 $outKoef = 2;

             } else if (self::isWeekend($working_day->date)) {
                 $inKoef = 1.25;
                 $outKoef = 1.5;

             } else {
                 $inKoef = 1;
                 $outKoef = 1.25;
             }

             $payment = $payPerHour * $inHours * $inKoef + $payPerHour * $outHours * $outKoef;

             $results[$user->id]['over8hours'] += $outHours;
             $results[$user->id]['under8hours'] += $inHours;
             $results[$user->id]['total_hours'] += $working_day->hours;
             $results[$user->id]['totalPayment'] += round($payment, 2);

             $results[$user->id]['months'][$monthKey]['over8hours'] += $outHours;
             $results[$user->id]['months'][$monthKey]['under8hours'] += $inHours;
             $results[$user->id]['months'][$monthKey]['total_hours'] += $working_day->hours;
             $results[$user->id]['months'][$monthKey]['totalPayment'] += round($payment, 2);


             $results[$user->id]['months'][$monthKey]['weeks'][$weekKey]['over8hours'] += $outHours;
             $results[$user->id]['months'][$monthKey]['weeks'][$weekKey]['under8hours'] += $inHours;
             $results[$user->id]['months'][$monthKey]['weeks'][$weekKey]['total_hours'] += $working_day->hours;
             $results[$user->id]['months'][$monthKey]['weeks'][$weekKey]['totalPayment'] += round($payment, 2);
         }

         dd($results);
     }
}

