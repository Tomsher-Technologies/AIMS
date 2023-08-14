@extends('admin.layouts.app')
@section('content')
<div class="container-fluid disable-text-selection">
    <div class="row">
        <div class="col-12">
            <div class="mb-0">
                <h1>Add New Student</h1>
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
                    <form class="form-horizontal repeater" action="{{ route('student.store') }}" method="POST"
                        enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        <div class="form-row justify-content-center">
                            <div class="form-group col-md-7">
                                <label for="#">First Name<span class="error">*</span></label>
                                <input type="text" class="form-control" value="{{ old('first_name') }}" id="first_name" name="first_name" placeholder="Enter first name">
                                @error('first_name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Last Name<span class="error">*</span></label>
                                <input type="text" class="form-control" value="{{ old('last_name') }}" id="last_name" name="last_name" placeholder="Enter last name">
                                @error('last_name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Email<span class="error">*</span></label>
                                <input type="email" class="form-control" value="{{ old('email') }}" id="email" name="email" placeholder="Enter email">
                                @error('email')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Password<span class="error">*</span></label>
                                <input type="password" autocomplete="new-password" class="form-control" value="{{ old('password') }}" id="password" name="password" placeholder="Enter password">
                                @error('password')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Phone Number<span class="error">*</span></label>
                                <input type="integer" class="form-control" value="{{ old('phone_number') }}" id="phone_number" name="phone_number" placeholder="Enter phone number">
                                @error('phone_number')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Gender<span class="error">*</span></label>
                                <select class="form-control"  id="gender" name="gender">
                                    <option value="" >Select Gender</option>
                                    <option value="male" >Male</option>
                                    <option value="female" >Female</option>
                                </select>
                                
                                @error('gender')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Date Of Birth</label>
                                <input type="text" class="form-control datepicker" value="{{ old('dob') }}" id="dob" name="dob" placeholder="YYYY-MM-DD">
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Address</label>
                                <textarea class="form-control" id="address"  rows="3" name="address" >{{ old('address') }}</textarea>
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Country</label>
                                <select class="form-control select2"  id="country" name="country" onchange="getStates(this.value)">
                                    <option value=""> Select Country</option>
                                    @foreach($countries as $contry)
                                        <option value="{{ $contry->id }}" >{{ $contry->name }} </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">State</label>
                                <select class="form-control  select2"  id="state" name="state">
                                    <option value=""> Select State</option>
                                </select>
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">City</label>
                                <input type="text" class="form-control" value="{{ old('city') }}" id="city" name="city" placeholder="Enter city">
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Student Image<span class="error">*</span></label>
                                <input type="file" class="form-control" id="profile_image" name="profile_image" />
                                @error('profile_image')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Passport Image <span class="error">*</span></label>
                                <input type="file" class="form-control" id="passport" name="passport" />
                                @error('passport')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Enrollment Form<span class="error">*</span></label>
                                <input type="file" class="form-control" id="enrollment_form"  name="enrollment_form" />
                                @error('enrollment_form')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Course<span class="error">*</span></label>
                                <select class="form-control"  id="course" name="course"  onchange="getPackages(this.value)">
                                    <option value=""> Select</option>
                                    @foreach($courses as $cou)
                                        <option value="{{ $cou->id }}" >{{ $cou->name }} </option>
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
                                </select>
                                @error('course_package')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <input type="hidden" name="valid_days" id="valid_days">
                                <label for="#">Start Date<span class="error">*</span></label>
                                <input type="text" class="form-control datepicker" onchange="getEndDate()" value="{{ old('start_date') }}" id="start_date" name="start_date" placeholder="YYYY-MM-DD">
                                @error('start_date')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">End Date<span class="error">*</span></label>
                                <input type="text" class="form-control datepicker" value="{{ old('end_date') }}" id="end_date" name="end_date" placeholder="YYYY-MM-DD">
                                @error('end_date')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-7">
                                <label for="#">Fees Pending<span class="error">*</span></label>
                                <div class="d-flex">
                                    <input type="radio" class="form-control radioBtn"  id="fee_pendingYes" name="fee_pending" value='1'> Yes
                                    <input type="radio" class="form-control radioBtn" id="fee_pendingNo" name="fee_pending" value='0' checked> No
                                </div>
                                @error('fee_pending')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-7" id="due_dateDiv" style="display:none">
                                <label for="#">Due Date</label>
                                <input type="text" class="form-control datepicker" value="{{ old('due_date') }}" id="due_date" name="due_date" placeholder="YYYY-MM-DD">
                                @error('due_date')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            

                            <div class="form-group col-md-7 d-flex">
                                <button type="submit" class="btn btn-primary d-block mt-2 btn_primary">Save</button>
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

    $('.select2').select2({
        'placeholder' : 'select'
    });

    $(".datepicker").datepicker({
        dateFormat: "yy-mm-dd",
        changeYear: true,
        changeMonth: true,
        yearRange: '-60:+2'
    });

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