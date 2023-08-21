<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notifications;
use App\Models\StudentPackages;

class CourseExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'course:expiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Course expiry notification for students 3 days before';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $packs = StudentPackages::where('end_date','>=', date('Y-m-d'))
                                ->where('is_deleted', 0)
                                ->where('is_active', 1)
                                ->get();

        $not = [];
        if($packs){
            foreach($packs as $pack){
                $notification = '';
                $end_date = $pack->end_date;
                $diff =  strtotime("$end_date")-strtotime(date("Y-m-d"));
                $days = round($diff / (60 * 60 * 24));
                if($days == 0){
                    $notification = "Your course package will expire today.";
                }elseif($days == 1){
                    $notification = "Your course package will expire tomorrow.";
                }elseif($days == 2){
                    $notification = "Your course package will expire in 2 days.";
                }
                if($notification != ''){
                    $not[] = array(
                        'user_id' => $pack->user_id,
                        'content' => $notification
                    ); 
                }
            }
            if(!empty($not)){
                Notifications::insert($not);
            }
        }
    }
}