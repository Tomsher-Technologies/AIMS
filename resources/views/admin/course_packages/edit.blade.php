@extends('admin.layouts.app')
@section('content')
<div class="container-fluid disable-text-selection">
    <div class="row">
        <div class="col-12">
            <div class="mb-0">
                <h1>Update Course Package</h1>
                <div class="text-zero top-right-button-container">
                    <a href="{{ route('course-packages') }}" class="btn btn-primary btn-lg top-right-button btn_primary">Back</a>
                </div>
            </div>
            <div class="separator mb-5"></div>
        </div>
    </div>
    <div class="row ">
        <div class="col-lg-12 col-md-12 mb-4">
            <div class="card mb-4">
                <div class="card-body">
                    <form class="form-horizontal repeater" action="{{ route('packages.update',$package->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-row justify-content-center">
                            <div class="form-group col-md-7">
                                <label for="#">Package Title<span class="error">*</span></label>
                                <input type="text" class="form-control" value="{{ old('title', $package->package_title) }}" id="title" name="title" placeholder="Enter package title">
                                @error('title')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Description <span class="error">*</span></label>
                                <textarea class="form-control" id="description"  rows="6" name="description" >{{ old('description',$package->description) }}</textarea>
                                @error('description')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Course<span class="error">*</span></label>
                                
                                    <select class="form-control"  id="course" name="course" onchange="getDivisions(this.value)">
                                        <option value="">Select Course</option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course->id }}" @if($package->courses_id == $course->id) selected @endif > {{ $course->name }} </option>
                                        @endforeach
                                    </select>
                                
                                @error('course')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Course Divisions<span class="error">*</span></label>
                                
                                    <select class="form-control"  id="course_division" name="course_division[]" multiple="multiple">
                                        @foreach($divisions as $div)
                                            @php
                                                $selected = '';
                                                if(in_array($div->id, $modules)){
                                                    $selected = 'selected';
                                                }           
                                            @endphp
                                            <option value="{{ $div->id }}" {{$selected}}> {{ $div->title }}</option>
                                        @endforeach
                                    </select>
                                
                                @error('course_division')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Duration (days)<span class="error">*</span></label>
                                <input type="number" class="form-control" value="{{ old('duration', $package->duration) }}" id="duration" name="duration" >
                                @error('duration')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Course Fee ({{config('constants.default_currency')}})<span class="error">*</span></label>
                                <input type="text" class="form-control" value="{{ old('name', $package->fees) }}" id="fee" name="fee" placeholder="Enter course fee">
                                @error('fee')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="inputPassword4">Active Status</label>
                                <select class="form-control" name="is_active" id="is_active">
                                    <option {{ ($package->is_active == 1) ? 'selected' : '' }} value="1">Active</option>
                                    <option {{ ($package->is_active == 0) ? 'selected' : '' }} value="0">In-Active</option>
                                </select>
                            </div>
                           
                           
                            <div class="form-group col-md-7 d-flex">
                                <button type="submit" class="btn btn-primary d-block mt-2 btn_primary">Save Course Package</button>
                                <a href="{{ route('course-packages') }}" class="btn btn-info d-block mt-2 ml-2">Cancel</a>
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

    $('#course_division').select2({

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
                $('#course_division').append(response).trigger('change');
            }
        });
    }
</script>
@endsection