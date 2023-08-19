<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserDetails;
use App\Models\Courses;
use App\Models\CourseDivisions;
use App\Models\CoursePackages;
use App\Models\PackageModules;
use App\Models\CourseClasses;
use App\Models\TeacherDivisions;
use App\Models\AssignTeachers;
use App\Models\TeacherSlots;
use App\Models\MockTests;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Auth;
use Validator;
use Storage;
use Str;
use File;
use Hash;
use DB;

class MockTestController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','admin']);
    }

    public function getMockTestList(Request $request){
        $date_search = $student_search = '';

        if($request->has('date_search')){
            $date_search 	= $request->date_search;
        }
        if($request->has('student_search')){
            $student_search 	= $request->student_search;
        }

        $query = MockTests::leftJoin('users as u','u.id','=','mock_tests.student_id')
                            ->where('mock_tests.is_deleted',0)->select('mock_tests.*','u.name','u.unique_id');

        if($date_search || $student_search){
            if($date_search){
                $query->where('mock_tests.test_date', $date_search);
            }
            if($student_search){
                $query->where('mock_tests.student_id', $student_search);
            }
            $query->orderBy('u.name','ASC');
        }else{
            $query->orderBy('mock_tests.id','DESC');
        }
    
        $tests = $query->paginate(20);

        $students = User::where('user_type','student')
                        ->where('is_active',1)
                        ->where('is_approved',1)
                        ->where('is_deleted',0)
                        ->orderBy('name','ASC')
                        ->get();
        return view('admin.mock_tests.index', compact('tests','date_search','students','student_search'));
    }

    public function createMockTest()
    {
        $students = User::where('user_type','student')
                        ->where('is_active',1)
                        ->where('is_approved',1)
                        ->where('is_deleted',0)
                        ->orderBy('name','ASC')
                        ->get();

        return view('admin.mock_tests.create', compact('students'));
    }

    public function storeMockTest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'test_date' => 'required',
            'student_id' => 'required'
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $test = MockTests::create([
            'test_date' => $request->test_date,
            'student_id' => $request->student_id,
            'listening_total' => $request->listening_total,
            'reading_total' => $request->reading_total,
            'listening_a' => $request->listening_a,
            'listening_b' => $request->listening_b ,
            'listening_c' => $request->listening_c ,
            'reading_a' => $request->reading_a,
            'reading_b' => $request->reading_b ,
            'reading_c' => $request->reading_c
        ]);

        flash('Mock test result created successfully')->success();
        return redirect()->route('mock-tests');
    }   

    public function deleteMockTest(Request $request){
        MockTests::where('id', $request->id)->update(['is_deleted' => 1]);
    }

    public function editMockTest(Request $request, $id)
    {
        $students = User::where('user_type','student')
                        ->where('is_active',1)
                        ->where('is_approved',1)
                        ->where('is_deleted',0)
                        ->orderBy('name','ASC')
                        ->get();
        $test = MockTests::with(['student'])->find($id);

        return view('admin.mock_tests.edit', compact('students','test'));
    }

    public function updateMockTest(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'test_date' => 'required',
            'student_id' => 'required'
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $test = MockTests::find($id);
        $test->test_date = $request->test_date;
        $test->student_id = $request->student_id;
        $test->listening_total = $request->listening_total;
        $test->reading_total = $request->reading_total;
        $test->listening_a = $request->listening_a;
        $test->listening_b = $request->listening_b ;
        $test->listening_c = $request->listening_c ;
        $test->reading_a = $request->reading_a;
        $test->reading_b = $request->reading_b ;
        $test->reading_c = $request->reading_c;
        $test->save();
        flash('Mock test result updated successfully')->success();
        return redirect()->route('mock-tests');
    }

    public function createBulkMockTest(){
        return view('admin.mock_tests.bulk_create');
    }
    
    public function storeBulkMockTest(Request $request){
        $validator = Validator::make($request->all(), [
            'test_date' => 'required',
            'test_file' => 'required|mimes:xlsx, csv, xls'
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $students = User::where('user_type','student')
                        ->where('is_approved',1)
                        ->where('is_deleted',0)
                        ->orderBy('name','ASC')
                        ->get()->pluck('id','unique_id')->toArray();
        
        $the_file = $request->file('test_file');
        
        $notFound = [];
        $spreadsheet = IOFactory::load($the_file->getRealPath());
        $sheet        = $spreadsheet->getActiveSheet();
        $row_limit    = $sheet->getHighestDataRow();
        $column_limit = $sheet->getHighestDataColumn();
        $row_range    = range( 3, $row_limit );
        $column_range = range( 'F', $column_limit );
        $data = array();
        foreach ( $row_range as $row ) {
            $student_id = trim($sheet->getCell( 'A' . $row )->getValue());
            if($student_id != ''){
                if(isset($students[$student_id])){
                    $data[] = [
                        'test_date' => $request->test_date,
                        'student_id' => $students[$student_id],
                        'listening_a' => $sheet->getCell( 'B' . $row )->getValue(),
                        'listening_b' => $sheet->getCell( 'C' . $row )->getValue(),
                        'listening_c' => $sheet->getCell( 'D' . $row )->getValue(),
                        'listening_total' => $sheet->getCell( 'E' . $row )->getValue(),
                        'reading_a' => $sheet->getCell( 'F' . $row )->getValue(),
                        'reading_b' => $sheet->getCell( 'G' . $row )->getValue(),
                        'reading_c' => $sheet->getCell( 'H' . $row )->getValue(),
                        'reading_total' => $sheet->getCell( 'I' . $row )->getValue(),
                    ];
                }else{
                    $notFound[] = $student_id;
                }
            }
        }
        
        if(!empty($data)){
            MockTests::insert($data);
            if(!empty($notFound)){
                flash("The following students details were not saved in the system. Please check their Student Id and try again ( ".implode(', ',$notFound)." )")->warning()->important();
            }else{
                flash("Successfully uploaded! ")->success()->important();
            }
        }else{
            flash("No data found in the file! ")->error()->important();
        }
        return redirect()->route('mock.bulk-create');
    }
}