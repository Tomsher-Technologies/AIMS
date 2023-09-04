@extends('admin.layouts.app')
@section('title', 'Upload Bulk Students Data')
@section('content')
<div class="container-fluid disable-text-selection">
    <div class="row">
        <div class="col-12">
            <div class="mb-0">
                <h1>Upload Bulk Students Data</h1>
                <div class="text-zero top-right-button-container">
                    <a href="{{ route('students') }}" class="btn btn-primary btn-lg top-right-button btn_primary">Back</a>
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
                    <form class="form-horizontal repeater" action="{{ route('student.bulk-store') }}" method="POST" autocomplete="off"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-row justify-content-center">

                            <div class="form-group col-md-7">
                                <label for="#">Upload File <span class="error">*</span></label>
                                <input type="file" class="form-control " value="" id="student_file" name="student_file" >
                                @error('student_file')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                
                            </div>
                            <div class="form-group col-md-7">
                                <a href="{{ asset('assets/files/Student_Demo_File.xlsx') }}" class="text-info"><b><i class="iconsminds-information" style="font-weight:700;"></i>Click here to download sample file format.</a></b>
                            </div>

                            <div class="form-group col-md-7 d-flex">
                                <button type="submit" class="btn btn-primary d-block mt-2 btn_primary">Upload File</button>
                                <a href="{{ route('students') }}" class="btn btn-info d-block mt-2 ml-2">Cancel</a>
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

    
</script>
@endsection