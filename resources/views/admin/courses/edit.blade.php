@extends('admin.layouts.app')
@section('title', 'Update Course')
@section('content')
<div class="container-fluid disable-text-selection">
    <div class="row">
        <div class="col-12">
            <div class="mb-0">
                <h1>Update Course</h1>
                <div class="text-zero top-right-button-container">
                    <a href="{{ route('all-courses') }}" class="btn btn-primary btn-lg top-right-button btn_primary">Back</a>
                </div>
            </div>
            <div class="separator mb-5"></div>
        </div>
    </div>
    <div class="row ">
        <div class="col-lg-12 col-md-12 mb-4">
            <div class="card mb-4">
                <div class="card-body">
                    <form class="form-horizontal repeater" action="{{ route('course.update',$course->id) }}" method="POST"
                        enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        <div class="form-row justify-content-center">
                            <div class="form-group col-md-7">
                                <label for="#">Course Name <span class="error">*</span></label>
                                <input type="text" class="form-control" value="{{ old('name', $course->name) }}" id="name" name="name" placeholder="Enter course name">
                                @error('name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            
                            <div class="form-group col-md-7">
                                <label for="#">Description <span class="error">*</span></label>
                                <textarea class="form-control" id="description"  rows="6" name="description" >{{ old('description',$course->description) }}</textarea>
                                @error('description')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Banner Image</label>
                                <input type="file" class="form-control" id="banner_image" value="{{ old('banner_image') }}" name="banner_image" />
                                @error('banner_image')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                @if($course->banner_image != NULL)
                                    <img class="mt-3" src="{{ asset($course->banner_image) }}" style="width:350px" />
                                @endif
                            </div>

                            <div class="form-group col-md-7">
                                <label for="inputPassword4">Active Status</label>
                                <select class="form-control" name="is_active" id="is_active">
                                    <option {{ ($course->is_active == 1) ? 'selected' : '' }} value="1">Active</option>
                                    <option {{ ($course->is_active == 0) ? 'selected' : '' }} value="0">Inactive</option>
                                </select>
                            </div>
                            
                            <div class="form-group col-md-7 d-flex">
                                <button type="submit" class="btn btn-primary d-block mt-2 btn_primary">Save Course</button>
                                <a href="{{ route('all-courses') }}" class="btn btn-info d-block mt-2 ml-2">Cancel</a>
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
@endsection
@section('footer')
<script type="text/javascript">
   
    
</script>
@endsection