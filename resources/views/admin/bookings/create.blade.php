@extends('admin.layouts.app')
@section('content')
<div class="container-fluid disable-text-selection">
    <div class="row">
        <div class="col-12">
            <div class="mb-0">
                <h1>Add New Booking</h1>
                <div class="text-zero top-right-button-container">
                    <a href="{{ route('student.bookings') }}" class="btn btn-primary btn-lg top-right-button btn_primary">Back</a>
                </div>
            </div>
            <div class="separator mb-5"></div>
        </div>
    </div>
    <div class="row ">
        <div class="col-lg-12 col-md-12 mb-4">
            <div class="card mb-4">
                <div class="card-body">
                    <form class="form-horizontal repeater" action="{{ route('booking.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-row justify-content-center">
                            <div class="form-group col-md-7">
                                <label for="#">Student<span class="error">*</span></label>
                                
                                    <select class="form-control"  id="student" name="student" onchange="getStudentDivisions(this.value)">
                                        <option value="">Select Student</option>
                                        @foreach($students as $stud)
                                            <option value="{{ $stud['id'] }}">  {{ $stud['name'] }} ({{ $stud['unique_id'] }})</option>
                                        @endforeach
                                    </select>
                                
                                @error('student')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Course Divisions<span class="error">*</span></label>
                                
                                    <select class="form-control"  id="course_division" name="course_division" onchange="setBelowFieldsNull()">
                                        <option value="">Select</option>
                                    </select>
                                
                                @error('course_division')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Booking Date<span class="error">*</span></label>
                                <input type="text" class="form-control" value="{{ old('book_date') }}" placeholder="YYYY-MM-DD" id="book_date" name="book_date"  onchange="getAvailableTeachers(this.value)">
                                @error('book_date')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Available Teachers<span class="error">*</span></label>
                                    <select class="form-control"  id="teacher" name="teacher" onchange="getTimeSlots(this.value)">
                                        <option value="">Select</option>
                                    </select>
                                
                                @error('teacher')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Time Slot<span class="error">*</span></label>
                                    <select class="form-control"  id="slot" name="slot">
                                        <option value="">Select</option> 
                                    </select>
                                
                                @error('slot')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7 d-flex">
                                <button type="submit" class="btn btn-primary d-block mt-2 btn_primary">Save Booking</button>
                                <a href="{{ route('student.bookings') }}" class="btn btn-info d-block mt-2 ml-2">Cancel</a>
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
<script type="text/javascript">
    $.ajaxSetup({
        headers:{'X-CSRF-TOKEN':$('meta[name="_token"]').attr('content')}
    });
    $('#student').select2();
    $('#time_slot').select2();
    $('#course_division').select2();
    $('#teacher').select2();
    $('#slot').select2();

    function setBelowFieldsNull(){
        $("#book_date").val('');
        $('#teacher').empty().append('<option value="">Select</option>').trigger('change');
    }

    $("#book_date").datepicker({
        dateFormat: "yy-mm-dd",
        changeYear: true,
        changeMonth: true,
        yearRange: '-60:+2'
    });

    function getStudentDivisions(student){
        $.ajax({
            url: "{{ route('student.divisions') }}",
            type: "GET",
            data: {
                id: student,
                _token:'{{ @csrf_token() }}',
            },
            success: function (response) {
                $('#course_division').empty();
                $('#course_division').append(response).trigger('change');
            }
        });
    }

    function getAvailableTeachers(){
        var division = $('#course_division').val();
        var date = $("input#book_date").val();

        $.ajax({
            url: "{{ route('get-available-teachers') }}",
            type: "GET",
            data: {
                module_id: division,
                date: date,
                _token:'{{ @csrf_token() }}',
            },
            success: function (response) {
                $('#teacher').empty();
                $('#teacher').append(response).trigger('change');
            }
        });
    }

    function getTimeSlots(teacher){

        var division = $('#course_division').val();
        var date = $("input#book_date").val();

        $.ajax({
            url: "{{ route('get-slots') }}",
            type: "GET",
            data: {
                module_id: division,
                date: date,
                teacher_id :teacher,
                _token:'{{ @csrf_token() }}',
            },
            success: function (response) {
                $('#slot').empty();
                $('#slot').append(response).trigger('change');
            }
        });
    }
</script>
@endsection