@extends('admin.layouts.app')
@section('title', 'Mock Test')
@section('content')
<div class="container-fluid disable-text-selection">
    <div class="row">
        <div class="col-12">
            <div class="mb-0">
                <h1>Upload Student Mock Test Result</h1>
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
                @include('flash::message')
                    <form class="form-horizontal repeater" action="{{ route('mock.bulk-store') }}" method="POST" autocomplete="off"
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
                                <label for="#">Upload File <span class="error">*</span></label>
                                <input type="file" class="form-control " value="" id="test_file" name="test_file" >
                                @error('test_file')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                
                            </div>
                            <div class="form-group col-md-7">
                                <a href="{{ asset('assets/files/Mock_Test_Demo_File.xlsx') }}" class="text-info"><b><i class="iconsminds-information" style="font-weight:700;"></i>Click here to download sample file format.</a></b>
                            </div>

                            <div class="form-group col-md-7 d-flex">
                                <button type="submit" class="btn btn-primary d-block mt-2 btn_primary">Upload Test Result</button>
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
    [class^="iconsminds-information"]:before, [class*=" iconsminds-information"]:before {
        font-weight: 700 !important;
    }
</style>    
@endsection
@section('footer')
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script type="text/javascript">
    $.ajaxSetup({
        headers:{'X-CSRF-TOKEN':$('meta[name="_token"]').attr('content')}
    });

    $("#test_date").datepicker({
        dateFormat: "yy-mm-dd",
        changeYear: true,
        changeMonth: true,
        yearRange: '-10:+2'
    });
   
    

</script>
@endsection