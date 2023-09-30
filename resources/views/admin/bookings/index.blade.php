@extends('admin.layouts.app')
@section('title', 'All Student Bookings')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="mb-3 d-flex align-items-center justify-content-between">
                <h1 class="m-0 p-0">All Student Bookings</h1>
                <div class="btn_group">
                    
                    <a href="{{ route('booking.create') }}" class="btn btn_primary">Add New Booking</a>
                </div>
            </div>
            
            <div class="separator mb-5"></div>
        </div>
    </div>
    <div class="row list " data-check-all="checkAll">
        <div class="col-lg-12 col-md-12 mb-4">
            <div class="card recent_certificate">
                <div class="card-body">
                @include('flash::message')

                <div class="">
                        <!-- <h3> Filters </h3> -->
                        <form class="" id="classes" action="" method="GET" autocomplete="off">
                            
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="#">Search By Student Name/Code/Email/Phone</label>
                                    <input type="text" class="form-control" value="{{ $title_search }}" id="title" name="title" placeholder="Enter Student Name/Code/Email/Phone">
                                </div>
                                @if(Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'super_admin')
                                    <div class="form-group col-md-3">
                                        <label for="#">Teacher</label>
                                        <select class="form-control"  id="teacher" name="teacher" >
                                            <option value="">Select Course</option>
                                            @foreach($teacher as $teach)
                                                <option value="{{ $teach->id }}" @if($teacher_search == $teach->id) selected @endif > {{ $teach->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                <div class="form-group col-md-3">
                                    <label for="#">Booking Date</label>
                                    <input type="text" class="form-control" value="{{ $date_search }}" id="date_search" name="date_search" placeholder="YYYY-MM-DD">
                                </div>
                                <div class="form-group col-md-3 filterDiv">
                                    <button type="submit" class="btn btn_primary">Filter</button>
                                    <a href="{{ route('student.bookings') }}"  class="btn btn-info">Reset</a>
                                    <a href="{{ route('export.bookings') }}"  class="btn btn-success">Excel Export</a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="data_card">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">Sl. No</th>
                                        <th scope="col">Booking Date</th>
                                        <th scope="col">Booking Slot</th>
                                        <th scope="col" class="text-center">Student Name</th>
                                        <th scope="col" class="text-center">Student Code</th>
                                        <th scope="col" class="text-center">Teacher Name</th>
                                        <th scope="col" class="text-center">Division</th>
                                        <th scope="col" class="text-center">Created By</th>
                                        <th scope="col" class="text-center">Attended Status</th>
                                        <th scope="col" class="text-center">Cancel Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($bookings[0]))
                                        @foreach($bookings as $key => $stud)
                                            <tr>
                                                <td>{{ $key + 1 + ($bookings->currentPage() - 1) * $bookings->perPage() }}</td>
                                                <td>{{ $stud->booking_date }}</td>
                                                <td>{{ $stud->slot->slot }}</td>
                                                <td class="text-center">{{ $stud->student->name }}</td>
                                                <td class="text-center">{{ $stud->student->unique_id }}</td>
                                                <td class="text-center">{{ $stud->teacher->name }}</td>
                                                <td class="text-center">
                                                   {{ $stud->course_division->title }}
                                                </td>
                                                <td class="text-center ">
                                                    <span class="">{{ $stud->createdBy->name ?? '' }}</span>
                                                </td>

                                                <td class="text-center ">
                                                    @if($stud->is_cancelled == 0)
                                                        @if($stud->is_attended == 1)
                                                            <span class="green success">Attended</span>
                                                        @elseif($stud->is_attended == 2)
                                                            <span class="error">Not Attended</span>
                                                        @else
                                                            <button class="btn btn-success pending mt-1 " onclick="markAttendance({{$stud->id}},1)"><span class="label label-success"><i class="iconsminds-yes"></i>Yes</span> </button>
                                                            <button class="btn btn-danger pending mt-1 " onclick="markAttendance({{$stud->id}},2)"><span class="label label-danger"><i class="iconsminds-close"></i>No</span> </button>
                                                        @endif
                                                    @endif
                                                </td>

                                                <td class="text-center ">
                                                    @if($stud->is_cancelled == 0 && ($stud->booking_date >= date('Y-m-d')))
                                                        <button class="btn btn-danger pending mt-1 " onclick="cancelBooking({{$stud->id}})"><span class="label label-danger">Cancel Booking</span> </button>
                                                    @elseif($stud->is_cancelled == 1)
                                                        <span class="error">Cancelled By {{ $stud->cancelledBy->name ?? ''}}</span>
                                                    @else
                                                    <span class="success">Not Cancelled</span>
                                                    @endif
                                                </td>
                                               
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="12" class="text-center">No data found.</td>
                                        </tr>
                                    @endif

                                </tbody>
                            </table>
                            <div class="aiz-pagination float-right">
                                {{ $bookings->appends(request()->input())->links() }}
                            </div>
                        </div>
                    </div>
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
     $("#date_search").datepicker({
        dateFormat: "yy-mm-dd",
        changeYear: true,
        changeMonth: true,
        yearRange: '-10:+2'
    });
    $('div.alert').not('.alert-important').delay(3000).fadeOut(350);

    function cancelBooking(id){
        
        Swal.fire({
            title: "Are you sure?",
            text: 'Do you want to cancel this booking?',
            icon: 'warning',
            confirmButtonText: "Yes, cancel it!",
            showCancelButton: true,
        }).then((result) => {
            if (result.isConfirmed){
                Swal.fire({
                    title: 'Enter notification message',
                    input: 'textarea'
                }).then(function(result) {
                    $.ajax({
                        url: "{{ route('booking.cancel') }}",
                        type: "POST",
                        data: {
                            id: id,
                            msg:result.value,
                            _token:'{{ @csrf_token() }}',
                        },
                        dataType: "html",
                        success: function () {
                            swal.fire("Done!", "Succesfully cancelled!", "success");
                            setTimeout(function () { 
                                window.location.reload();
                            }, 3000);  
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            swal.fire("Error cancelling!", "Please try again", "error");
                        }
                    });
                })
                
            }  
        });
    }

    function markAttendance(id, status){
        
        Swal.fire({
            title: "Are you sure?",
            text: 'Do you want to change the status?',
            icon: 'warning',
            confirmButtonText: "Yes",
            showCancelButton: true,
        }).then((result) => {
            if (result.isConfirmed){
                $.ajax({
                    url: "{{ route('booking.attend') }}",
                    type: "POST",
                    data: {
                        id: id,
                        status : status,
                        _token:'{{ @csrf_token() }}',
                    },
                    dataType: "html",
                    success: function () {
                        swal.fire("Done!", "Succesfully changed!", "success");
                        setTimeout(function () { 
                            window.location.reload();
                        }, 3000);  
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        swal.fire("Error cancelling!", "Please try again", "error");
                    }
                });
            }  
        });
    }
   

</script>
@endsection