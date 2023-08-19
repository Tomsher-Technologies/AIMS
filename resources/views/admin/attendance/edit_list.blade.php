@extends('admin.layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="mb-3 d-flex align-items-center justify-content-between">
                <h1 class="m-0 p-0">Attendance Details</h1>
                <div class="btn_group">
                    
                    <a href="#" onclick="window.location=document.referrer;" class="btn btn_primary">Back</a>
                </div>
            </div>
            
            <div class="separator mb-5"></div>
        </div>
    </div>
    <div class="row list" data-check-all="checkAll">
        <div class="col-lg-12 col-md-12 mb-4">
            <div class="card recent_certificate">
                <div class="card-body">
                    <h4>{{ $date }} - {{$class_name}}</h4>
                    <div class="data_card w-100">
                        <div class="table-responsive">
                            
                                <input type="hidden" name="class_id" id="class_id" value="{{ $class_id }}">
                                <input type="hidden" name="date_value" id="date_value" value="{{ $date }}">
                                <input type="hidden" name="course_id" id="course_id" value="{{ $course }}">
                                <input type="hidden" name="division_id" id="division_id" value="{{ $course_division }}">
                                <table class="table table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="w-10">Sl No</th>
                                            <th scope="col" class="w-50">Student Name</th>
                                            <th scope="col" class="w-20">Student Code</th>
                                            <th scope="col" class="w-20 text-center">Attendance Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($students[0]))
                                            @foreach($students as $key => $stud)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $stud->name }}</td>
                                                    <td>{{ $stud->unique_id }}</td>
                                                
                                                    <td class="text-center">
                                                        <input type="checkbox" class="wh-17" name="attendance[{{$stud->user_id}}]" id="attendance_{{$stud->user_id}}"  value="" @if($stud->is_attended == 1) checked @endif onclick="updateAttendance('{{$id}}','{{$stud->user_id}}')">
                                                    </td>
                                                
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="4" class="text-center">No data found.</td>
                                            </tr>
                                        @endif
                                        
                                    </tbody>
                                </table>
                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('header')
<link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
@endsection
@section('footer')
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script type="text/javascript">
    function updateAttendance(id, user_id){
        var status = $('#attendance_'+user_id).prop('checked') ? 1:0;
        var date = $('#date_value').val();
        var classid = $('#class_id').val();
        $.ajax({
            url: "{{ route('update.attendance') }}",
            type: "POST",
            data: {
                id: id,
                user_id : user_id,
                status : status,
                date :date,
                classid:classid,
                _token:'{{ @csrf_token() }}',
            },
            success: function (response) {
                swal.fire("Done!", "Successfully Updated", "success");
            }
        });
    }
</script>
@endsection