@extends('admin.layouts.app')
@section('title', 'Update Teacher')
@section('content')
<div class="container-fluid disable-text-selection">
    <div class="row">
        <div class="col-12">
            <div class="mb-0">
                <h1>Update Teacher</h1>
                <div class="text-zero top-right-button-container">
                    <a href="{{ route('teachers') }}" class="btn btn-primary btn-lg top-right-button btn_primary">Back</a>
                </div>
            </div>
            <div class="separator mb-5"></div>
        </div>
    </div>
    <div class="row ">
        <div class="col-lg-12 col-md-12 mb-4">
            <div class="card mb-4">
                <div class="card-body">
                    <form class="form-horizontal repeater" action="{{ route('teacher.update', $teacher->id) }}" method="POST"
                        enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        <div class="form-row justify-content-center">
                            <div class="form-group col-md-7">
                                <label for="#">First Name<span class="error">*</span></label>
                                <input type="text" class="form-control" value="{{ old('first_name', $teacher->user_details->first_name) }}" id="first_name" name="first_name" placeholder="Enter first name">
                                @error('first_name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Last Name<span class="error">*</span></label>
                                <input type="text" class="form-control" value="{{ old('last_name', $teacher->user_details->last_name) }}" id="last_name" name="last_name" placeholder="Enter last name">
                                @error('last_name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Email<span class="error">*</span></label>
                                <input type="email" class="form-control" value="{{ old('email', $teacher->email) }}" id="email" name="email" placeholder="Enter email">
                                @error('email')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Password</label>
                                <input type="password" autocomplete="new-password" class="form-control" value="{{ old('password') }}" id="password" name="password" placeholder="Enter password">
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Phone Number<span class="error">*</span></label>
                                <input type="integer" class="form-control" value="{{ old('phone_number', $teacher->user_details->phone_number) }}" id="phone_number" name="phone_number" placeholder="Enter phone number">
                                @error('phone_number')
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
                                            <option value="{{ $div->id }}"  {{$selected}}> {{ $div->title }} ({{ $div->course_name->name }}) </option>
                                        @endforeach
                                    </select>
                                
                                @error('course_division')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Profile Image </label>
                                <input type="file" class="form-control" id="profile_image"  name="profile_image" />
                                @if($teacher->user_details->profile_image != NULL)
                                    <img class="mt-3" src="{{ asset($teacher->user_details->profile_image) }}" style="width:250px" />
                                @endif
                            </div>

                            <div class="form-group col-md-7">
                                <label for="inputPassword4">Active Status</label>
                                <select class="form-control" name="is_active" id="is_active">
                                    <option {{ ($teacher->is_active == 1) ? 'selected' : '' }} value="1">Active</option>
                                    <option {{ ($teacher->is_active == 0) ? 'selected' : '' }} value="0">Inactive</option>
                                </select>
                            </div>

                            <div class="form-group col-md-7 d-flex">
                                <button type="submit" class="btn btn-primary d-block mt-2 btn_primary">Save</button>
                                <a href="{{ route('teachers') }}" class="btn btn-info d-block mt-2 ml-2">Cancel</a>
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

    $('#course_division').select2({

    });

  
</script>
@endsection