<?php

namespace App\Services;

use App\Models\User;
use App\Models\Off_day;
use App\Models\Working_day;
use Illuminate\Support\Facades\DB;


class EmployerService
{
    // private UserRepository $userRepository;
    // public function __construct(UserRepository $userRepository) {
    //     $this->userRepository = $userRepository;
    // }

    public function calculate()
    {


        // $name = $user->full_name;
        // $total_paga = $user->total_paga;
        // $user_id = $working_day->user_id;
        // $date = $working_day->date;
        // $hours = $working_day->hours;
        // $date = $off_day->date;
        $over8hours = 0;
        $under8hours = 0;

        $users = DB::select('select * from users');
        $off_days = DB::select('select * from off_days');
        $working_days = DB::select('select * from working_days');
        $paga_per_ore = 1; 
        $weekend = array(6, 7, 13, 14, 20, 21, 27,28);


        foreach ($users as $user) {
            foreach ($working_days as $working_day){

                    // punon ne nje dite pushimi pagesa perllogaritet:
                    // 8 oret e para 150% dhe koha mbi 8 ore me 200%

                if ($user->id == $working_day->user_id && in_array($working_day->date,$off_days)) {

                    if($working_day->hours > 8){
                        $paga_per_ore *= 2; 
                        $over8hours += $working_day->hours ;
                    }
                    $paga_per_ore *= 1.5; 
                    $under8hours += $working_day->hours ;

                }
                // punon ne fundjave pagesa perllogaritet:
                // 8 oret e para 125% dhe koha mbi 8 ore me 150%

                if(in_array($working_day->date,$weekend)){

                    if($working_day->hours > 8){
                        $paga_per_ore *= 1.5;
                        $over8hours += $working_day->hours ;
                    }
                    $paga_per_ore *= 1.25; 
                    $under8hours += $working_day->hours ;

                }

                // punon ne ditet normale te punes pagesa perllogaritet:
                // 8 oret e para 100% dhe koha mbi 8 ore me 125%

                if(!in_array($working_day->date,$weekend)){

                    if($working_day->hours > 8){
                        $paga_per_ore *= 1.25; 
                        $over8hours += $working_day->hours ;
                    }
                    $paga_per_ore *= 1; 
                    $under8hours += $working_day->hours ;

                }
                $user->paga_totale = count($working_days)  * $paga_per_ore;

            }

        }
       // return $user->paga_totale;
    }
}
