<?php
  
use Carbon\Carbon;
use App\Models\UserDetails;
use App\Models\User;
use App\Models\StudentPackages;
use App\Models\PackageModules;
use Carbon\CarbonPeriod;

/**
 * Write code on Method
 *
 * @return response()
 */
if (! function_exists('convertYmdToMdy')) {
    function convertYmdToMdy($date)
    {
        return Carbon::createFromFormat('Y-m-d', $date)->format('m-d-Y');
    }
}
  
/**
 * Write code on Method
 *
 * @return response()
 */
if (! function_exists('convertMdyToYmd')) {
    function convertMdyToYmd($date)
    {
        return Carbon::createFromFormat('m-d-Y', $date)->format('Y-m-d');
    }
}

//highlights the selected navigation on admin panel
if (!function_exists('areActiveRoutes')) {
    function areActiveRoutes(array $routes, $output = "active")
    {
        foreach ($routes as $route) {
            if (Route::currentRouteName() == $route) return $output;
        }
    }
}
if (!function_exists('getDatesBetween2Dates')) {
    function getDatesBetween2Dates($startDate, $endDate){
        $dates = [];
    
        $period = CarbonPeriod::create($startDate,  $endDate);
        foreach ($period as $date) {
            $dates[] =  $date->format('Y-m-d');
        }
        return $dates;
    }
}

if (!function_exists('getTimeSlotHrMIn')) {
    function getTimeSlotHrMIn($interval, $start_time, $end_time)
    {
        $start = new DateTime($start_time);
        $end = new DateTime($end_time);
        $startTime = $start->format('H:i');
        $endTime = $end->format('H:i');
        $i=0;
        $time = [];
        while(strtotime($startTime) <= strtotime($endTime)){
            $start = $startTime;
            $end = date('H:i',strtotime('+'.$interval.' minutes',strtotime($startTime)));
            $startTime = date('H:i',strtotime('+'.$interval.' minutes',strtotime($startTime)));
            $i++;
            if(strtotime($startTime) <= strtotime($endTime)){
                $time[$i] = date('g:i A', strtotime($start)).' - '.date('g:i A', strtotime($end));
            }
        }
        return $time;
    }
}


if(!function_exists('getStudentActiveCourseDivisions')){
    function getStudentActiveCourseDivisions($studentId){
        $studentPackage = StudentPackages::where('user_id', $studentId)
                                        ->where('is_deleted',0)
                                        ->where('is_active', 1)
                                        ->where('end_date', '>', date('Y-m-d'))
                                        ->pluck('package_id')->toArray();
        $options = '<option value=""> Select </option>';  
        if(isset($studentPackage[0])){
            $package_id = $studentPackage[0];
            $divisions = PackageModules::leftJoin('course_divisions as cd','cd.id','=','package_modules.module_id')
                                        ->where('package_modules.package_id', $package_id)
                                        ->where('package_modules.is_deleted',0)
                                        ->where('cd.is_active',1)
                                        ->select('cd.id', 'cd.title')
                                        ->orderBy('id', 'ASC')->get();

            
            foreach($divisions as $div){
                $options .= '<option value="'.$div->id.'">'.$div->title.'</option>';
            }
        }
        
        return $options;
    }
}

function formatTimeSlot($slotTime){
    $slotTimeArray = explode(' - ',$slotTime);
    $slotFrom = $slotTimeArray[0] ?? '';
    $slotTo = $slotTimeArray[1] ?? '';

    $data['fromTime'] = ($slotFrom != '') ? date("H:i", strtotime($slotFrom)) : NULL;
    $data['toTime'] = ($slotTo != '') ? date("H:i", strtotime($slotTo)) : NULL;

    return $data;
}


