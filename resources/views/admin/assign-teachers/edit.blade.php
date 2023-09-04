@extends('admin.layouts.app')
@section('title', 'Update Teacher Assign')
@section('content')
<div class="container-fluid disable-text-selection">
    <div class="row">
        <div class="col-12">
            <div class="mb-0">
                <h1>Update Teacher Assign</h1>
                <div class="text-zero top-right-button-container">
                    <a href="{{ route('assign-teachers') }}" class="btn btn-primary btn-lg top-right-button btn_primary">Back</a>
                </div>
            </div>
            <div class="separator mb-5"></div>
        </div>
    </div>
    <div class="row ">
        <div class="col-lg-12 col-md-12 mb-4">
            <div class="card mb-4">
                <div class="card-body">
                    <form class="form-horizontal repeater" action="{{ route('assign-teacher.update', $assign->id) }}" method="POST"
                        enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        <div class="form-row justify-content-center">
                            <div class="form-group col-md-7">
                                <label for="#">Class Date<span class="error">*</span></label>
                                <input type="text" class="form-control" value="{{ old('class_date', $assign->assigned_date) }}" id="class_date" name="class_date" placeholder="YYYY-MM-DD">
                                @error('class_date')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Teacher<span class="error">*</span></label>
                                
                                    <select class="form-control"  id="teacher" name="teacher" onchange="getTeacherDivisions(this.value)">
                                        <option value="">Select Teacher</option>
                                        @foreach($teachers as $teach)
                                            <option value="{{ $teach->id }}"  @if($assign->teacher_id == $teach->id) selected @endif> {{ $teach->name }} </option>
                                        @endforeach
                                    </select>
                                
                                @error('teacher')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Course Divisions<span class="error">*</span></label>
                                
                                    <select class="form-control"  id="course_division" name="course_division">
                                        @foreach($divisions as $div)
                                            <option value="{{ $div->course_division->id }}"  @if($assign->module_id == $div->course_division->id) selected @endif> {{ $div->course_division->title }}</option>
                                        @endforeach
                                    </select>
                                
                                @error('course_division')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-7 d-flex">
                                <div class="form-group col-md-4 padding-left0">
                                    <label for="#">From Time<span class="error">*</span></label>
                                    <input type="text" class="form-control datePicker" value="{{ old('from_time', $assign->start_time) }}" id="from_time" name="from_time" >
                                    @error('from_time')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="#">To Time<span class="error">*</span></label>
                                    <input type="text" class="form-control datePicker" value="{{ old('to_time', $assign->end_time) }}" id="to_time" name="to_time" >
                                    @error('to_time')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="#">Time Interval (In Minutes)<span class="error">*</span></label>
                                    <input type="text" class="form-control" value="{{ old('interval', $assign->time_interval) }}" id="interval" name="interval" >
                                    @error('interval')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            

                            <div class="form-group col-md-7 d-flex">
                                <button type="submit" class="btn btn-primary d-block mt-2 btn_primary">Save</button>
                                <a href="{{ route('assign-teachers') }}" class="btn btn-info d-block mt-2 ml-2">Cancel</a>
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
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<style>
    .select2 {
        width:inherit !important
    }
</style>    
@endsection
@section('footer')
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<!-- <script src="{{ asset('assets/js/moment.min.js') }}"></script> -->

<script type="text/javascript">
    

    $("#class_date").datepicker({
        dateFormat: "yy-mm-dd",
        changeYear: true,
        changeMonth: true,
        minDate: "-0y"
    });
    $('#from_time').timepicker({
        timeFormat: 'h:mm p',
        interval: 60,
        minTime: '6:00am',
        maxTime: '10:00pm',
        defaultTime: '',
        dynamic: false,
        dropdown: true,
        scrollbar: true
    });
    $('#to_time').timepicker({
        timeFormat: 'h:mm p',
        interval: 60,
        minTime: '6:00am',
        maxTime: '10:00pm',
        defaultTime: '',
        dynamic: false,
        dropdown: true,
        scrollbar: true
    });
    
    $.ajaxSetup({
        headers:{'X-CSRF-TOKEN':$('meta[name="_token"]').attr('content')}
    });

    $('#course_division').select2({

    });

    function getTeacherDivisions(course){
        $.ajax({
            url: "{{ route('teacher.divisions') }}",
            type: "GET",
            data: {
                id: course,
                _token:'{{ @csrf_token() }}',
            },
            success: function (response) {
                $('#course_division').empty();
                $('#course_division').append(response).trigger('change');
            }
        });
    }
</script>
@endsection