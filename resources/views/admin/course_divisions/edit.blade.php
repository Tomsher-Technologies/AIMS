@extends('admin.layouts.app')
@section('title', 'Update Course Division')
@section('content')
<div class="container-fluid disable-text-selection">
    <div class="row">
        <div class="col-12">
            <div class="mb-0">
                <h1>Update Course Division</h1>
                <div class="text-zero top-right-button-container">
                    <a href="{{ route('all-divisions') }}" class="btn btn-primary btn-lg top-right-button btn_primary">Back</a>
                </div>
            </div>
            <div class="separator mb-5"></div>
        </div>
    </div>
    <div class="row ">
        <div class="col-lg-12 col-md-12 mb-4">
            <div class="card mb-4">
                <div class="card-body">
                    <form class="form-horizontal repeater" action="{{ route('division.update',$division->id) }}" method="POST"
                        enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        <div class="form-row justify-content-center">
                            <div class="form-group col-md-7">
                                <label for="#">Course<span class="error">*</span></label>
                                <select class="form-control"  id="course_id" name="course_id" >
                                    <option value="">Select Course</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" {{ (old('course_id', $division->courses_id) == $course->id) ? 'selected' : '' }}> {{ $course->name }} </option>
                                    @endforeach
                                </select>
                            
                                @error('course_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            
                            <div class="form-group col-md-7">
                                <label for="#">Division Title<span class="error">*</span></label>
                                <input type="text" class="form-control" id="division_name" value="{{ old('division_name',$division->title) }}" name="division_name" placeholder="Enter course division title">
                                @error('division_name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Description <span class="error">*</span></label>
                                <textarea class="form-control" id="description"  rows="8" name="description" >{{ old('description', $division->description) }}</textarea>
                                @error('description')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="inputPassword4">Active Status</label>
                                <select class="form-control" name="is_active" id="is_active">
                                    <option {{ ($division->is_active == 1) ? 'selected' : '' }} value="1">Active</option>
                                    <option {{ ($division->is_active == 0) ? 'selected' : '' }} value="0">In-Active</option>
                                </select>
                            </div>
                            
                            <div class="form-group col-md-7 d-flex">
                                <button type="submit" class="btn btn-primary d-block mt-2 btn_primary">Save</button>
                                <a href="{{ route('all-divisions') }}" class="btn btn-info d-block mt-2 ml-2">Cancel</a>
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