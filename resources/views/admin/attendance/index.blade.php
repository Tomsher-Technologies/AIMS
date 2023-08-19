@extends('admin.layouts.app')
@section('content')
<div class="container-fluid disable-text-selection">
    <div class="row">
        <div class="col-12">
            <div class="mb-0">
                <h1>Manage Attendance</h1>
                <div class="text-zero top-right-button-container">
                    <!-- <a href="{{ route('student.bookings') }}" class="btn btn-primary btn-lg top-right-button btn_primary">Back</a> -->
                </div>
            </div>
            <div class="separator mb-5"></div>
        </div>
    </div>
    <div class="row ">
        <div class="col-lg-12 col-md-12 mb-4">
            <div class="card mb-4">
                <div class="card-body">
                    <form class="form-horizontal repeater" action="#" method="POST"
                        enctype="multipart/form-data" id="attendance" autocomplete="off">
                        @csrf
                        <div class="form-row justify-content-center">

                            <div class="form-group col-md-7">
                                <label for="#">Date<span class="error">*</span></label>
                                <input type="text" class="form-control" value="{{ old('date') }}" placeholder="YYYY-MM-DD" id="date" name="date" >
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Course<span class="error">*</span></label>
                                
                                <select class="form-control"  id="course" name="course" onchange="getDivisions(this.value)">
                                    <option value="">Select Course</option>
                                    @foreach($courses as $co)
                                        <option value="{{ $co->id }}">  {{ $co->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Course Divisions<span class="error">*</span></label>
                                
                                <select class="form-control"  id="course_division" name="course_division" onchange="getClasses(this.value)">
                                    <option value="">Select</option>
                                </select>
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Class<span class="error">*</span></label>
                                    <select class="form-control"  id="class" name="class">
                                        <option value="">Select</option>
                                    </select>
                            </div>

                            <div class="form-group col-md-7 d-flex">
                                <button type="submit" class="btn btn-primary d-block mt-2 btn_primary">Show Students</button>
                               
                            </div>

                        </div>
                    </form>

                    <form method="POST" id="attendanceMark" action="#">
                        <div class="form-row justify-content-center">
                            @csrf
                            <div class="form-group col-md-7 d-flex" id="student_list">

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('header')
<link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
<style>
    .select2 {
        width:inherit !important
    }
</style>    
@endsection
@section('footer')
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>

<script type="text/javascript">
    $.ajaxSetup({
        headers:{'X-CSRF-TOKEN':$('meta[name="_token"]').attr('content')}
    });
    $('#course').select2();
    $('#class').select2();
    $('#course_division').select2();

    $("#date").datepicker({
        dateFormat: "yy-mm-dd",
        changeYear: true,
        changeMonth: true,
        yearRange: '-60:+2'
    });

    $("#attendance").validate({
        rules: {
            date: {
                required: true
            },
            course:{
                required:true
            },
            course_division:{
                required:true
            },
            class:{
                required:true
            }
        },
        errorPlacement: function (error, element) {
            if(element.hasClass('select2')) {
                error.insertAfter(element.next('.select2-container'));
            }else{
                error.appendTo(element.parent("div"));
            }
        },
        submitHandler: function(form,event) {

            var date = $('#date').val();
            var course = $('#course').val();
            var course_division = $('#course_division').val();
            var class_id = $('#class').val();
            $.ajax({
                url: "{{ route('students-list') }}",
                type: "GET",
                data: {
                    date: date,
                    course: course,
                    course_division: course_division,
                    class_id: class_id,
                    _token:'{{ @csrf_token() }}',
                },
                success: function (response) {
                    $('#student_list').html(response);
                    
                }
            });
        }
    });
   
    function getDivisions(course){
        $.ajax({
            url: "{{ route('course.divisions') }}",
            type: "GET",
            data: {
                id: course,
                _token:'{{ @csrf_token() }}',
            },
            success: function (response) {
                $('#course_division').empty();
                $('#course_division').append('<option value="">Select</option>');
                $('#course_division').append(response).trigger('change');
            }
        });
    }

    function getClasses(module_id){
        $.ajax({
            url: "{{ route('course.classes') }}",
            type: "GET",
            data: {
                id: module_id,
                _token:'{{ @csrf_token() }}',
            },
            success: function (response) {
                $('#class').empty();
                $('#class').append('<option value="">Select</option>');
                $('#class').append(response).trigger('change');
            }
        });
    }

    $("#attendanceMark").submit(function(e) {
        e.preventDefault();
        var formData = new FormData($('#attendanceMark')[0]);
        formData.append('_token','{{ @csrf_token() }}');

        var checkbox = $("#attendanceMark").find("input[type=checkbox]");
        $.each(checkbox, function(key, val) {
            formData.append($(val).attr('name'), $(this).prop('checked') ? 1:0)
        });

        $.ajax({
            url: "{{ route('save-attendance') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                swal.fire("Done!", "Successfully Updated", "success");
            }
        });
    });

    
   
</script>
@endsection