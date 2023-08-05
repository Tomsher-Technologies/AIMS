@extends('admin.layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="mb-3 d-flex align-items-center justify-content-between">
                <h1 class="m-0 p-0">All Student Bookings</h1>
                <div class="btn_group">
                    
                    <!-- <a href="{{ route('student.create') }}" class="btn btn_primary">Add New Student</a> -->
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
                        <form class="" id="classes" action="" method="GET">
                            
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="#">Search By Name/Student Code/Email</label>
                                    <input type="text" class="form-control" value="{{ $title_search }}" id="title" name="title" placeholder="Enter Name/Student Code/Email">
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="#">Teachers</label>
                                    <select class="form-control"  id="course" name="course" onchange="getDivisions(this.value)">
                                        <option value="">Select Course</option>
                                        @foreach($teacher as $teach)
                                            <option value="{{ $teach->id }}" @if($course_search == $teach->id) selected @endif > {{ $teach->name }} </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="#">Course Package</label>
                                    <select class="form-control"  id="package" name="package">
                                        <option value="">Select Course Package</option>
                                        @foreach($package as $div)
                                            <option value="{{ $div->id }}" @if($package_search == $div->id) selected @endif > {{ $div->package_title }} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3 filterDiv">
                                    <button type="submit" class="btn btn_primary">Filter</button>
                                    <a href="{{ route('student.bookings') }}"  class="btn btn-info">Reset</a>
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
                                        <th scope="col" class="text-center">Action</th>
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
                                                <td class="text-center">{{ $stud->student->name }}</td>
                                                <td class="text-center">{{ $stud->teacher->name }}</td>
                                                <td class="text-center">
                                                   {{ $stud->course_division->title }}
                                                </td>
                                        
                                                <td class="text-center ">
                                                    @if($stud->is_cancelled == 1)
                                                        <span class="error">Cancelled By </span>
                                                    @else
                                                        <button class="btn btn-danger pending mt-1 " onclick="cancelBooking({{$stud->id}})"><span class="label label-danger">Cancel Booking</span> </button>
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
                $.ajax({
                    url: "{{ route('booking.cancel') }}",
                    type: "POST",
                    data: {
                        id: id,
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
            }  
        });
    }
   

</script>
@endsection