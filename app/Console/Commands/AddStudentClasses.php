<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CronAddClasses;
use App\Models\StudentPackages;
use App\Models\StudentClasses;

class AddStudentClasses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'student:classes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add package classes to students';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        CronAddClasses::where('is_added',1)->delete();
        $crons = CronAddClasses::where('is_added',0)->get();
        if($crons){
            foreach ($crons as  $cron)
            {
                $package_id = $cron->package_id;
                $class_id = $cron->class_id;
                $students = StudentPackages::where('is_deleted',0)->where('is_active',1)
                                            ->where('package_id', $package_id)
                                            ->where('end_date','>=', date('Y-m-d'))
                                            ->get();
                $details = [];
                if($students){
                    foreach($students as $stud){
                        $details[] = [
                            'user_id' => $stud->user_id,
                            'student_package_id' => $stud->id,	
                            'course_package_id' => $package_id,	
                            'class_id' => $class_id,	
                            'start_date' => $stud->start_date,	
                            'end_date' => $stud->end_date,	
                        ];
                    }
                    if(!empty($details)){
                        StudentClasses::insert($details);
                    }
                }
                CronAddClasses::where('id', $cron->id)->update(['is_added' => 1]);
            }
        }
    }
}
