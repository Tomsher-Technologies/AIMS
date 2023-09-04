@extends('admin.layouts.app')
@section('title', 'All Students')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="mb-3 d-flex align-items-center justify-content-between">
                <h1 class="m-0 p-0">All Students</h1>
                <div class="btn_group">
                    <a href="{{ route('student.bulk-create') }}" class="btn btn-success">Upload Bulk Students</a>
                    <a href="{{ route('student.create') }}" class="btn btn_primary">Add New Student</a>
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

                                <div class="form-group col-md-2">
                                    <label for="#">Course</label>
                                    <select class="form-control"  id="course" name="course" onchange="getDivisions(this.value)">
                                        <option value="">Select Course</option>
                                        @foreach($courses as $cou)
                                            <option value="{{ $cou->id }}" @if($course_search == $cou->id) selected @endif > {{ $cou->name }} </option>
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

                                <div class="form-group col-md-2">
                                    <label for="#">Status</label>
                                    <select class="form-control"  id="status" name="status" >
                                        <option value="">Select Status</option>
                                        <option value="active" @if($status_search == "active") selected @endif>Active</option>
                                        <option value="inactive" @if($status_search == "inactive") selected @endif>Inactive</option>
                                        <option value="approved" @if($status_search == "approved") selected @endif>Approved</option>
                                        <option value="rejected" @if($status_search == "rejected") selected @endif>Rejected</option>
                                        <option value="pending" @if($status_search == "pending") selected @endif>Approval Pending</option>
                                        <option value="booking" @if($status_search == "booking") selected @endif>Booking Approval</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-2 filterDiv">
                                    <button type="submit" class="btn btn_primary">Filter</button>
                                    <a href="{{ route('students') }}"  class="btn btn-info">Reset</a>
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
                                        <th scope="col">Name</th>
                                        <th scope="col" class="text-center">Student Code</th>
                                        <th scope="col" class="text-center">Email</th>
                                        <!-- <th scope="col" class="text-center">Phone</th> -->
                                        <!-- <th scope="col" class="text-center">Image</th> -->
                                        <th scope="col" class="text-center">Course Package</th>
                                        <th scope="col" class="text-center w-10">Start Date</th>
                                        <th scope="col" class="text-center w-10">End Date</th>
                                        <th scope="col" class="text-center">Approval Status</th>
                                        <th scope="col" class="text-center">Active Status</th>
                                        <th scope="col" class="text-center">Booking Approval</th>
                                        <th scope="col" class="w-10">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($students[0]))
                                        @foreach($students as $key => $stud)
                                            <tr>
                                                <td>{{ $key + 1 + ($students->currentPage() - 1) * $students->perPage() }}</td>
                                                <td>{{ $stud->name }}</td>
                                                <td class="text-center">{{ $stud->unique_id }}</td>
                                                <td class="text-center">{{ $stud->email }}</td>
                                                <!-- <td class="text-center">{{ $stud->user_details->phone_code }}{{ $stud->user_details->phone_number }}</td> -->
                                                <!-- <td class="text-center">
                                                    @if($stud->user_details->profile_image != NULL)
                                                    <img class="profileImage" src="{{ asset($stud->user_details->profile_image) }}"/>
                                                    @endif
                                                </td> -->
                                                @if(!empty($stud->student_packages) && $stud->student_packages != '[]')
                                                    @foreach($stud->student_packages as $stPack)
                                                    <td class="text-center"> {{ $stPack->package->package_title }}</td>
                                                    <td class="text-center"> {{ $stPack->start_date }}</td>
                                                    <td class="text-center"> {{ $stPack->end_date }}</td>
                                                    @endforeach
                                                @else
                                                <td class="text-center">- </td>
                                                <td class="text-center">-</td>
                                                <td class="text-center">-</td>
                                                @endif
                                              
                                                <td class="text-center ">
                                                    @if($stud->is_approved == 1)
                                                        <span class="green">Approved</span>
                                                    @elseif($stud->is_approved == 2)
                                                        <span class="error">Rejected</span>
                                                    @else
                                                        <button class="btn btn-success pending" onclick="approveStudent({{$stud->id}})"><span class="label label-success">Approve</span> </button>
                                                        <button class="btn btn-danger pending mt-1" onclick="rejectStudent({{$stud->id}})"><span class="label label-danger">Reject</span> </button>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if($stud->is_active == 1)
                                                        <span class="green">Active</span>
                                                    @else
                                                        <span class="error">Inactive</span>
                                                    @endif
                                                </td>
                                                <td class="text-center"> 
                                                    @if($stud->booking_approval == 0)
                                                        <button class="btn btn-success pending" onclick="approveBooking({{$stud->id}})"><span class="label label-success">Approve</span> </button>
                                                    @endif
                                                </td>
                                                <td>
                                                    <ul class="action_list">
                                                        <li class="mt-1" style="margin-bottom: 0px;">
                                                            <a class="" data-id="{{$stud->id}}" title="View Student" href="{{ route('view-student',['id'=>$stud->id]) }}">
                                                                <!-- <img src="{{ asset('assets/images/eye.png') }}" width="20" class="img-fluid" alt=""> -->
                                                            <i class="simple-icon-eye view-icon"> </i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="" data-id="{{$stud->id}}" title="Edit Student" href="{{ route('student.edit',['id'=>$stud->id]) }}"><img src="{{ asset('assets/images/pencil.png') }}" width="20" class="img-fluid" alt=""></a>
                                                        </li>
                                                        <li> <span> <a class="deleteStudent" data-id="{{$stud->id}}" title="Delete Student" href="#"><img src="{{ asset('assets/images/delete.png') }}" width="20" class="img-fluid" alt=""></a></span></li>
                                                    </ul>
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
                                {{ $students->appends(request()->input())->links() }}
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
    $(document).on('click','.deleteStudent',function(){
        var id = $(this).attr('data-id');
        Swal.fire({
            title: "Are you sure?",
            text: 'Do you want to continue?',
            icon: 'warning',
            confirmButtonText: "Yes, delete it!",
            showCancelButton: true,
        }).then((result) => {
            if (result.isConfirmed){
                $.ajax({
                    url: "{{ route('student.delete') }}",
                    type: "POST",
                    data: {
                        id: id,
                        _token:'{{ @csrf_token() }}',
                    },
                    dataType: "html",
                    success: function () {
                        swal.fire("Done!", "It was succesfully deleted!", "success");
                        setTimeout(function () { 
                            window.location.reload();
                        }, 3000);  
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        swal.fire("Error deleting!", "Please try again", "error");
                    }
                });
            }  
        });
    }) ;

    function approveStudent(id){
        Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to approve this student?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
        }).then(function(result) {
            var status = 1;
            if (result.isConfirmed) {
                changeStatus(id, status);
            } 
            
        })
    }

    function rejectStudent(id){
        Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to reject this student?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
        }).then(function(result) {
            var status = 2;
            if (result.isConfirmed) {
                changeStatus(id, status);
            } 
            
        })
    }

    function changeStatus(id, status){
        
        $.ajax({
            url: "{{ route('student.approve')}}",
            type: "POST",
            data: { "_token": "{{ csrf_token() }}", "id":id, 'status':status},
            success: function( response ) {
                Swal.fire(
                    response+' successfully',
                    '',
                    'success'
                );
                setTimeout(function () { 
                    window.location.reload();
                }, 3000);  
            }
        });
    }

    function approveBooking(id){
        Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to approve?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
        }).then(function(result) {
            var status = 1;
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('booking.approve')}}",
                    type: "POST",
                    data: { "_token": "{{ csrf_token() }}", "id":id},
                    success: function( response ) {
                        Swal.fire(
                            'Approved successfully',
                            '',
                            'success'
                        );
                        setTimeout(function () { 
                            window.location.reload();
                        }, 3000);  
                    }
                });
            } 
            
        })
    }

</script>
@endsection