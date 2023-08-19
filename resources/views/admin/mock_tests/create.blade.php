@extends('admin.layouts.app')
@section('content')
<div class="container-fluid disable-text-selection">
    <div class="row">
        <div class="col-12">
            <div class="mb-0">
                <h1>Add Student Mock Test Result</h1>
                <div class="text-zero top-right-button-container">
                    <a href="{{ route('mock-tests') }}" class="btn btn-primary btn-lg top-right-button btn_primary">Back</a>
                </div>
            </div>
            <div class="separator mb-5"></div>
        </div>
    </div>
    <div class="row ">
        <div class="col-lg-12 col-md-12 mb-4">
            <div class="card mb-4">
                <div class="card-body">
                    <form class="form-horizontal repeater" action="{{ route('mock.store') }}" method="POST" autocomplete="off"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-row justify-content-center">

                            <div class="form-group col-md-7">
                                <label for="#">Test Date<span class="error">*</span></label>
                                <input type="text" class="form-control" value="{{ old('test_date') }}" id="test_date" name="test_date" placeholder="YYYY-MM-DD">
                                @error('test_date')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Student<span class="error">*</span></label>
                                
                                    <select class="form-control"  id="student_id" name="student_id" >
                                        <option value="">Select Student</option>
                                        @foreach($students as $student)
                                            <option value="{{ $student->id }}" {{ (old('student_id') == $student->id) ? 'selected' : '' }}> {{ $student->name }} ({{ $student->unique_id }})</option>
                                        @endforeach
                                    </select>
                                
                                @error('student_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-7">
                                <h4>LISTENING</h4>
                            </div>
                        
                            <div class="form-group col-md-7">
                                <label for="#">Part A</label>
                                <input type="number" class="form-control listening" value="{{ old('listening_a') }}" id="listening_a" name="listening_a" >
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Part B</label>
                                <input type="number" class="form-control listening" value="{{ old('listening_b') }}" id="listening_b" name="listening_b" >
                            </div>
                            <div class="form-group col-md-7">
                                <label for="#">Part C</label>
                                <input type="number" class="form-control listening" value="{{ old('listening_c') }}" id="listening_c" name="listening_c" >
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Listening Total</label>
                                <input type="number" class="form-control" value="{{ old('listening_total') }}" id="listening_total" name="listening_total" >
                            </div>

                            <div class="col-md-7">
                                <h4>READING</h4>
                            </div>
                        
                            <div class="form-group col-md-7">
                                <label for="#">Part A</label>
                                <input type="number" class="form-control reading" value="{{ old('reading_a') }}" id="reading_a" name="reading_a" >
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Part B</label>
                                <input type="number" class="form-control reading" value="{{ old('reading_b') }}" id="reading_b" name="reading_b" >
                            </div>
                            <div class="form-group col-md-7">
                                <label for="#">Part C</label>
                                <input type="number" class="form-control reading" value="{{ old('reading_c') }}" id="reading_c" name="reading_c" >
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Reading Total</label>
                                <input type="number" class="form-control" value="{{ old('reading_total') }}" id="reading_total" name="reading_total" >
                            </div>

                            <div class="form-group col-md-7 d-flex">
                                <button type="submit" class="btn btn-primary d-block mt-2 btn_primary">Save Test Result</button>
                                <a href="{{ route('mock-tests') }}" class="btn btn-info d-block mt-2 ml-2">Cancel</a>
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
    .radioBtn{
        width:5% !important;
        height: 20px !important;
    }
</style>    
@endsection
@section('footer')
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script type="text/javascript">
    $.ajaxSetup({
        headers:{'X-CSRF-TOKEN':$('meta[name="_token"]').attr('content')}
    });

    $('#student_id').select2();
    $("#test_date").datepicker({
        dateFormat: "yy-mm-dd",
        changeYear: true,
        changeMonth: true,
        yearRange: '-60:+2'
    });
   
    $(document).on('keyup','.listening', function() {
        updateListeningTotalFields()
    });

    $(document).on('keyup','.reading', function() {
        updateReadingTotalFields()
    });

    function  updateListeningTotalFields(){
        var la = parseInt($('#listening_a').val());
        var lb = parseInt($('#listening_b').val());
        var lc = parseInt($('#listening_c').val());

        la = la ? la : 0;
        lb = lb ? lb : 0;
        lc = lc ? lc : 0;

        var ltotal = la + lb + lc;

        $('#listening_total').val(ltotal);
    }

    function  updateReadingTotalFields(){
        var ra = parseInt($('#reading_a').val());
        var rb = parseInt($('#reading_b').val());
        var rc = parseInt($('#reading_c').val());

        ra = ra ? ra : 0;
        rb = rb ? rb : 0;
        rc = rc ? rc : 0;

        var rtotal = ra + rb + rc;

        $('#reading_total').val(rtotal);
    }


</script>
@endsection