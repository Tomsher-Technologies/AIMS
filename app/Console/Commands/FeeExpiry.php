<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notifications;
use App\Models\StudentPackages;

class FeeExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fee:expiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Extension fee expiry date notification';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $packs = StudentPackages::where('end_date','>', date('Y-m-d'))
                                ->where('due_date','>=', date('Y-m-d'))
                                ->where('is_deleted', 0)
                                ->where('fee_pending', 1)
                                ->where('is_active', 1)
                                ->get();

        $not = [];
        if($packs){
            foreach($packs as $pack){
                $notification = '';
                $due_date = $pack->due_date;
                $diff =  strtotime("$due_date")-strtotime(date("Y-m-d"));
                $days = round($diff / (60 * 60 * 24));
                if($days <= 2){
                    $notification = 'Your fee payment is due on '.date('d M, Y', strtotime($due_date)).'. Please ignore if already Paid.';
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
