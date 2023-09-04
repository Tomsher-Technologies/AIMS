@extends('admin.layouts.app')
@section('title', 'Update Student')
@section('content')
<div class="container-fluid disable-text-selection">
    <div class="row">
        <div class="col-12">
            <div class="mb-0">
                <h1>Update Student</h1>
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
                    <form class="form-horizontal repeater" action="{{ route('student.update', $student->id) }}" method="POST"
                        enctype="multipart/form-data" autocomplete="off">
                    
                        @csrf
                        <div class="form-row justify-content-center">
                            <div class="form-group col-md-7">
                                <label for="#">First Name<span class="error">*</span></label>
                                <input type="text" class="form-control" value="{{ old('first_name', $student->user_details->first_name) }}" id="first_name" name="first_name" placeholder="Enter first name">
                                @error('first_name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Last Name<span class="error">*</span></label>
                                <input type="text" class="form-control" value="{{ old('last_name', $student->user_details->last_name) }}" id="last_name" name="last_name" placeholder="Enter last name">
                                @error('last_name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Email<span class="error">*</span></label>
                                <input type="email" class="form-control" value="{{ old('email', $student->email) }}" id="email" name="email" placeholder="Enter email">
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
                                <input type="integer" class="form-control" value="{{ old('phone_number', $student->user_details->phone_number) }}" id="phone_number" name="phone_number" placeholder="Enter phone number">
                                @error('phone_number')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Gender<span class="error">*</span></label>
                                <select class="form-control"  id="gender" name="gender">
                                    <option value="" >Select Gender</option>
                                    <option value="male" @if($student->user_details->gender == 'male') selected @endif>Male</option>
                                    <option value="female" @if($student->user_details->gender == 'female') selected @endif >Female</option>
                                </select>
                                
                                @error('gender')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Date Of Birth</label>
                                <input type="text" class="form-control dob-datepicker" value="{{ old('dob',$student->user_details->date_of_birth) }}" id="dob" name="dob" placeholder="YYYY-MM-DD">
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Address</label>
                                <textarea class="form-control" id="address"  rows="3" name="address" >{{ old('address',$student->user_details->address) }}</textarea>
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Country</label>
                                <select class="form-control select2"  id="country" name="country" onchange="getStates(this.value)">
                                    <option value=""> Select Country</option>
                                    @foreach($countries as $contry)
                                        <option value="{{ $contry->id }}" {{ ($student->user_details->country == $contry->id) ? 'selected' : ''}} >{{ $contry->name }} </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">State</label>
                                <select class="form-control  select2"  id="state" name="state">
                                    <option value=""> Select State</option>
                                    @foreach($states as $state)
                                        <option value="{{ $state->id }}" {{ ($student->user_details->state == $state->id) ? 'selected' : ''}} >{{ $state->name }} </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">City</label>
                                <input type="text" class="form-control" value="{{ old('city',$student->user_details->city) }}" id="city" name="city" placeholder="Enter city">
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Student Image</label>
                                <input type="file" class="form-control" id="profile_image" name="profile_image" />
                               
                                @if($student->user_details->profile_image != NULL)
                                    <a href="{{ asset($student->user_details->profile_image) }}" target="_blank">
                                        <img class="mt-3" src="{{ asset($student->user_details->profile_image) }}" style="width:350px" />
                                    </a>
                                @endif
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Passport Image </label>
                                <input type="file" class="form-control" id="passport" name="passport" />
                               
                                @if($student->user_details->passport != NULL)
                                    <a href="{{ asset($student->user_details->passport) }}" target="_blank">
                                        <img class="mt-3" src="{{ asset($student->user_details->passport) }}" style="width:350px" />
                                    </a>
                                @endif
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Enrollment Form</label>
                                <input type="file" class="form-control" id="enrollment_form"  name="enrollment_form" />
                               
                                @if($student->user_details->enrollment_form != NULL)
                                    <a href="{{ asset($student->user_details->enrollment_form) }}" target="_blank">
                                        @php    
                                            $filePath = asset($student->user_details->enrollment_form);
                                            $extension = pathinfo($filePath, PATHINFO_EXTENSION);

                                            $fileExtensions = ['pdf','docx','doc'];

                                            if (in_array($extension, $fileExtensions)) {
                                                $file = asset('assets/images/file.png');
                                                echo '<img class="mt-3" src="'.$file.'" style="width:100px" />';
                                            } else {
                                                echo '<img class="mt-3" src="'.$filePath.'" style="width:350px" />';
                                            }
                                        @endphp
                                        
                                    </a>
                                @endif
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Course<span class="error">*</span></label>
                                <select class="form-control"  id="course" name="course"  onchange="getPackages(this.value)">
                                    <option value=""> Select</option>
                                    @foreach($courses as $cou)
                                        <option value="{{ $cou->id }}" {{ (isset($student->student_packages[0]) && $student->student_packages[0]->course_id == $cou->id) ? 'selected' : '' }} >{{ $cou->name }} </option>
                                    @endforeach
                                </select>
                                
                                @error('course')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Course Package<span class="error">*</span></label>
                                <select class="form-control"  id="course_package" name="course_package">
                                    <option value="">Select Package</option>
                                    @php 
                                        $days ='0';
                                    @endphp
                                    @foreach($packages as $pack)
                                        @php
                                            $selected=''; 
                                            if(isset($student->student_packages[0]) && $student->student_packages[0]->package_id == $pack->id){
                                                $days = $pack->duration;
                                                $selected = 'selected';
                                            }
                                        @endphp
                                        <option value="{{ $pack->id }}" {{ $selected }} data-id="{{$pack->duration}}" >{{ $pack->package_title }} </option>
                                    @endforeach
                                </select>
                                @error('course_package')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <input type="hidden" name="valid_days" id="valid_days" value="{{$days}}">
                                <label for="#">Start Date<span class="error">*</span></label>
                                @php   
                                    $startdate = isset($student->student_packages[0]) ? $student->student_packages[0]->start_date : '';
                                @endphp
                                <input type="text" class="form-control datepicker" onchange="getEndDate()" value="{{ old('start_date',$startdate) }}" id="start_date" name="start_date" placeholder="YYYY-MM-DD">
                                @error('start_date')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">End Date<span class="error">*</span></label>
                                @php   
                                    $end_date = isset($student->student_packages[0]) ? $student->student_packages[0]->end_date : '';
                                @endphp
                                <input type="text" class="form-control datepicker" value="{{ old('end_date', $end_date) }}" id="end_date" name="end_date" placeholder="YYYY-MM-DD">
                                @error('end_date')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Fees Pending<span class="error">*</span></label>
                                <div class="d-flex">
                                    @php   
                                        $fee_pending = isset($student->student_packages[0]) ? $student->student_packages[0]->fee_pending : '';
                                    @endphp
                                    <input type="radio" class="form-control radioBtn"  id="fee_pendingYes" name="fee_pending" value='1' {{ ($fee_pending == 1) ? 'checked' : ''}}> Yes
                                    <input type="radio" class="form-control radioBtn" id="fee_pendingNo" name="fee_pending" value='0'  {{ ($fee_pending != 1) ? 'checked' : ''}}> No
                                </div>
                                @error('fee_pending')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-7" id="due_dateDiv" @if($fee_pending != 1) style="display:none"  @endif>
                                <label for="#">Due Date</label>
                                    @php   
                                        $due_date = isset($student->student_packages[0]) ? $student->student_packages[0]->due_date : '';
                                    @endphp
                                <input type="text" class="form-control datepicker" value="{{ old('due_date',$due_date) }}" id="due_date" name="due_date" placeholder="YYYY-MM-DD">
                                @error('due_date')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-7">
                                <label for="inputPassword4">Active Status</label>
                                <select class="form-control" name="is_active" id="is_active">
                                    <option {{ ($student->is_active == 1) ? 'selected' : '' }} value="1">Active</option>
                                    <option {{ ($student->is_active == 0) ? 'selected' : '' }} value="0">Inactive</option>
                                </select>
                            </div>

                            <div class="form-group col-md-7 d-flex">
                                <button type="submit" class="btn btn-primary d-block mt-2 btn_primary">Save</button>
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
</style>    
@endsection
@section('footer')
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script type="text/javascript">
    $.ajaxSetup({
        headers:{'X-CSRF-TOKEN':$('meta[name="_token"]').attr('content')}
    });

    $('.select2').select2({
        'placeholder' : 'select'
    });
    $(".dob-datepicker").datepicker({
        dateFormat: "yy-mm-dd",
        changeYear: true,
        changeMonth: true,
        yearRange: '-50:-15'
    });

    $(".datepicker").datepicker({
        dateFormat: "yy-mm-dd",
        changeYear: true,
        changeMonth: true,
        yearRange: '-5:+2'
    });
    $( "#end_date" ).datepicker( "option", "minDate", new Date('{{$startdate}}') );

    $('input[type=radio][name=fee_pending]').change(function() {
        if (this.value == '1') {
            $('#due_dateDiv').css('display','block');
        }
        else if (this.value == '0') {
            $('#due_dateDiv').css('display','none');
        }
    });

    function getEndDate(){
        var valid_days = parseInt($("#valid_days").val());
        if(!isNaN(valid_days)){
            var start_date= $('#start_date').val();
            var end_date = new Date(start_date); // pass start date here
            end_date.setDate(end_date.getDate() + valid_days);
            var month = end_date.getMonth() + 1;
            var day = end_date.getDate();

            var output = end_date.getFullYear() + '-' +
                (('' + month).length < 2 ? '0' : '') + month + '-' +
                (('' + day).length < 2 ? '0' : '') + day;
            $( "#end_date" ).datepicker( "option", "minDate", new Date(start_date) );
            $('#end_date').val(output);
        }
    }

    $(document).on('change','#course_package', function(){
        var days = $('option:selected', this).attr('data-id');
        $('#valid_days').val(days);
        $('#start_date, #end_date').val('');
    });


    function getStates(country){
        $.ajax({
            url: "{{ route('country.states') }}",
            type: "GET",
            data: {
                id: country,
                _token:'{{ @csrf_token() }}',
            },
            success: function (response) {
                $('#state').empty();
                $('#state').append(response).trigger('change');
            }
        });
    }

    function getPackages(country){
        $('#start_date, #end_date').val('');
        $.ajax({
            url: "{{ route('course.packages') }}",
            type: "GET",
            data: {
                id: country,
                _token:'{{ @csrf_token() }}',
            },
            success: function (response) {
                $('#course_package').empty();
                $('#course_package').append('<option value="">Select package</option>');
                $('#course_package').append(response).trigger('change');
            }
        });
    }
</script>
@endsection