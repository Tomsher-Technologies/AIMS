@extends('admin.layouts.app')
@section('content')
<div class="container-fluid disable-text-selection">
    <div class="row">
        <div class="col-12">
            <div class="mb-0">
                <h1>Update Class</h1>
                <div class="text-zero top-right-button-container">
                    <a href="{{ route('classes') }}" class="btn btn-primary btn-lg top-right-button btn_primary">Back</a>
                </div>
            </div>
            <div class="separator mb-5"></div>
        </div>
    </div>
    <div class="row ">
        <div class="col-lg-12 col-md-12 mb-4">
            <div class="card mb-4">
                <div class="card-body">
                    <form class="form-horizontal repeater" action="{{ route('class.update', $classes->id) }}" method="POST"
                        enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        <div class="form-row justify-content-center">
                            <div class="form-group col-md-7">
                                <label for="#">Class Title<span class="error">*</span></label>
                                <input type="text" class="form-control" value="{{ old('title', $classes->class_name) }}" id="title" name="title" placeholder="Enter class title">
                                @error('title')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Course<span class="error">*</span></label>
                                
                                    <select class="form-control"  id="course" name="course" onchange="getPackDivisions(this.value)">
                                        <option value="">Select Course</option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course->id }}"  @if($classes->course_id == $course->id) selected @endif > {{ $course->name }} </option>
                                        @endforeach
                                    </select>
                                
                                @error('course')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Course Divisions<span class="error">*</span></label>
                                
                                    <select class="form-control"  id="course_division" name="course_division">
                                        @foreach($divisions as $div)
                                            <option value="{{ $div->id }}" @if($classes->module_id == $div->id) selected @endif > {{ $div->title }}</option>
                                        @endforeach
                                    </select>
                                
                                @error('course_division')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Course Packages<span class="error">*</span></label>
                                <select class="form-control"  id="packages" name="packages[]" multiple="multiple">
                                    @foreach($packages as $pack)
                                        @php
                                            $selected = '';
                                            if(in_array($pack->id, $packs)){
                                                $selected = 'selected';
                                            }           
                                        @endphp
                                        <option value="{{ $pack->id }}" {{$selected}} > {{ $pack->package_title }}</option>
                                    @endforeach
                                </select>
                                
                                @error('packages')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Sort Order Number<span class="error">*</span></label>
                                <input type="number" class="form-control" value="{{ old('order', $classes->order) }}" id="order" name="order" >
                                @error('order')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Mandatory<span class="error">*</span></label>
                                <div class="d-flex">
                                    <input type="radio" class="form-control radioBtn"  id="mandatoryYes" name="mandatory" value='1' @if($classes->is_mandatory == '1') checked @endif> Yes
                                    <input type="radio" class="form-control radioBtn" id="mandatoryNo" name="mandatory" value='0' @if($classes->is_mandatory == '0') checked @endif> No
                                </div>
                                @error('mandatory')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="inputPassword4">Active Status</label>
                                <select class="form-control" name="is_active" id="is_active">
                                    <option {{ ($classes->is_active == 1) ? 'selected' : '' }} value="1">Active</option>
                                    <option {{ ($classes->is_active == 0) ? 'selected' : '' }} value="0">In-Active</option>
                                </select>
                            </div>
                           
                            <div class="form-group col-md-7 d-flex">
                                <button type="submit" class="btn btn-primary d-block mt-2 btn_primary">Save Class</button>
                                <a href="{{ route('classes') }}" class="btn btn-info d-block mt-2 ml-2">Cancel</a>
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
    $('#packages').select2();

    function getPackDivisions(course){
        $.ajax({
            url: "{{ route('course.pack-divisions') }}",
            type: "GET",
            data: {
                id: course,
                _token:'{{ @csrf_token() }}',
            },
            success: function (response) {
                var jsonData = JSON.parse(response);
                
                $('#course_division').empty();
                $('#course_division').append(jsonData.divisions).trigger('change');
                $('#packages').empty();
                $('#packages').append(jsonData.packages).trigger('change');
            }
        });
    }
</script>
@endsection