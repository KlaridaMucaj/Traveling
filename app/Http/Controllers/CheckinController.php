<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use App\Models\Checkin;
use App\Models\User;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isEmpty;

class CheckinController extends Controller
{

    private function convertToSeconds(string $hour): int
    {
        $hour = explode(':', $hour);

        return ((int)$hour[0]) * 3600 + ((int)$hour[1]) * 60 + ((int)$hour[2]);
    }

    public function check(Request $request)
    {
        $results = [];
        $user_id = $request->input('user_id');
        $date = $request->date;
        //dd($date);

        $query = Checkin::with('user');
        if (filled($user_id)) {
            $query->where('user_id', $user_id);
        }
        if (filled($date)) {
            $query->where('check_in_date', $date);
        }

        $checkins = $query->get();


        foreach ($checkins as $checkin) {
            $user = $checkin->user;
            $key = $user->id . '-' . $checkin->check_in_date;
            if (!isset($results[$key])) {
                $results[$key] = [
                    'full_name' => $user->full_name,
                    'total_in' => '00:00:00',
                    'date' => $checkin->check_in_date,
                ];
            }

            $checkOutHour = $checkin->check_out_hour == '00:00:00' ? '18:00:00' : $checkin->check_out_date;

            $checkInCarbon = Carbon::parse($checkin->check_in_hour);
            $checkOutCarbon = Carbon::parse($checkin->check_out_hour);
            $diffInSeconds = $checkInCarbon->diffInSeconds($checkOutCarbon);
            $checkInTotal = $diffInSeconds + $this->convertToSeconds($results[$key]['total_in']);

            $results[$key]['total_in'] = gmdate('H:i:s', $checkInTotal);
        }

//dd ($results);
        return view('checkin', ['users' => User::all()], compact('results'));
    }

    public function getUsersWithCheckins(Request $request)
    {
        if (request()->ajax()) {
            $from_date = Carbon::parse($request->input('from_date'))->format('Y:m:d');
            $to_date = Carbon::parse($request->input('to_date'))->format('Y:m:d');


            $query = User::query()->with(['checkins' => function ($query) use ($to_date, $from_date, $request) {

//                $query = User->leftJoin('checkins', function ($query) use ($to_date, $from_date) {
//                    $query->on('checkins.user_id', '=','users.id');

                if (filled($from_date) && filled($to_date)) {

                    $query->whereBetween('check_in_date', array($from_date, $to_date));
                    //dd($query);
                }
            }])->latest();
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('total_in', function ($user) use ($request, $query) {
                    foreach ($user->checkins as $checkin) {

                        $total = 0;
                        $checkOutCarbon = $checkin->check_out_hour == '00:00:00' ? '18:00:00' : Carbon::parse($checkin->check_out_hour);

                        $checkInCarbon = Carbon::parse($checkin->check_in_hour);
                        $diffInSeconds = $checkInCarbon->diffInSeconds($checkOutCarbon);
                        $total += $diffInSeconds;

                        return gmdate('H:i:s', $total);
                    }
//                })->addIndexColumn()
//                ->addColumn('check_in_date', function ($user) use ($query) {
//                    foreach ($user->checkins as $checkin) {
//                        return $checkin->check_in_date;
//                   }
                })->addColumn('status', function ($row) {
                    if ($row->status) {
                        return '<span class="badge badge-primary">Active</span>';
                    } else {
                        return '<span class="badge badge-danger">Deactive</span>';
                    }
                })
                ->filter(function ($instance) use ($request) {
                    // $instance = User::query()->with('checkins');

                    if ($request->get('status') == '0' || $request->get('status') == '1') {
                        $instance->where('status', $request->get('status'));
                    }
                    if (filled($request->get('search'))) {
                        $instance->where(function ($instance) use ($request) {
                            $search = $request->get('search');
                            $date = Carbon::parse($request->input('from_date'));


                            if ($request->get('operator') == '=') {
                                $instance->where('full_name', "$search");
                                $instance->orWhere('id', "$search");
                                // $instance->where('check_in_date', "$date");
                            } else if ($request->get('operator') == '!=') {
                                $instance->whereNot('full_name', "$search");
                                $instance->whereNot('id', "$search");
                                //  $instance->whereDate('check_in_date','!=',"$date");
                            } else if ($request->get('operator') == '<') {
                                $instance->where('id', '<', "$search");
                                // $instance->whereDate('check_in_date','<',"$date");
                            } else if ($request->get('operator') == '>') {
                                $instance->where('id', '>', "$search");
                                // $instance->whereDate('check_in_date','>',"$date");
                            } else if ($request->get('operator') == '<=') {
                                $instance->where('id', '<=', "$search");
                                // $instance->whereDate('check_in_date','<=',"$date");
                            } else if ($request->get('operator') == '>=') {
                                $instance->where('id', '>=', "$search");
                                // $instance->whereDate('check_in_date','>=',"$date");
                            }
                        });
                    }
                })
                ->rawColumns(['status'])
                ->make(true);
        }
        return view('checkin');
    }

}
