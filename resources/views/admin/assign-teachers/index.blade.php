@extends('admin.layouts.app')
@section('title', 'Assign Teachers')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="mb-3 d-flex align-items-center justify-content-between">
                <h1 class="m-0 p-0">Assign Teachers</h1>
                <div class="btn_group">
                    
                    <a href="{{ route('assign-teacher.create') }}" class="btn btn_primary">Assign Teacher</a>
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
                                    <label for="#">Assigned Date</label>
                                    <input type="text" class="form-control" value="{{ $date_search }}" id="assigned_date" name="assigned_date" placeholder="YYYY-MM-DD">
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="#">Teachers</label>
                                    <select class="form-control"  id="teacher" name="teacher" onchange="getTeacherDivisions(this.value)">
                                        <option value="">Select Teacher</option>
                                        @foreach($teachers as $teach)
                                            <option value="{{ $teach->id }}" @if($teacher_search == $teach->id) selected @endif > {{ $teach->name }} </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="#">Course Divisions</label>
                                    <select class="form-control"  id="course_division" name="course_division">
                                        <option value="">Select Course Division</option>
                                        @if(!empty($divisions))
                                            @foreach($divisions as $div)
                                                <option value="{{ $div->id }}" @if($division_search == $div->id) selected @endif > {{ $div->title }} </option>
                                            @endforeach
                                        @endif

                                        @if(!empty($teacherdivisions))
                                            @foreach($teacherdivisions as $tdiv)
                                                <option value="{{ $tdiv->course_division->id }}" @if($division_search == $tdiv->course_division->id) selected @endif > {{ $tdiv->course_division->title }} </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group col-md-3 filterDiv">
                                    <button type="submit" class="btn btn_primary">Filter</button>
                                    <a href="{{ route('assign-teachers') }}"  class="btn btn-info">Reset</a>
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
                                        <th scope="col">Assigned Date</th>
                                        <th scope="col" class="text-center">Teacher Name</th>
                                        <th scope="col" class="text-center">Division</th>
                                        <th scope="col" class="text-center">Start Time</th>
                                        <th scope="col" class="text-center">End Time</th>
                                        <th scope="col" class="text-center">Time Interval<br> (In Minutes)</th>
                                        <th scope="col" class="text-center">Slots </th>
                                        <th scope="col" class="text-center">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($assigned[0]))
                                        @foreach($assigned as $key => $assign)
                                            @php $deletable = 1; @endphp
                                            <tr>
                                                <td>{{ $key + 1 + ($assigned->currentPage() - 1) * $assigned->perPage() }}</td>
                                                <td>{{ $assign->assigned_date }}</td>
                                                <td class="text-center">{{ $assign->teacher->name }}</td>
                                                <td class="text-center">{{ $assign->course_division->title }}</td>
                                                <td class="text-center">
                                                {{ $assign->start_time }}
                                                </td>
                                                <td class="text-center">
                                                {{ $assign->end_time }}
                                                </td>
                                                <td class="text-center">
                                                {{ $assign->time_interval }} Min
                                                </td>

                                                <td>
                                                    <ul>
                                                        @foreach($assign->slots as $slot)
                                                            @if($slot->is_booked == 1)
                                                                @php 
                                                                    if($slot->is_deleted == 0){
                                                                        $deletable = 0;
                                                                    }
                                                                @endphp
                                                                <li class="error"> {{ $slot->slot}} (Booked) </li>
                                                            @else
                                                                <li class="green"> {{ $slot->slot}} (Available) </li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                </td>

                                                <td class="text-center">
                                                    @if($assign->is_active == 1)
                                                        <span class="green">Active</span>
                                                    @else
                                                        <span class="error">All Bookings Canceled</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    
                                                        <ul class="action_list">
                                                            @if($assign->assigned_date >= date('Y-m-d') )
                                                                @if($assign->is_active == 1)
                                                                    <li>
                                                                        <a class="" data-id="{{$assign->id}}" title="Edit Teacher Assign" href="{{ route('assign-teacher.edit',['id'=>$assign->id]) }}"><img src="{{ asset('assets/images/pencil.png') }}" width="20" class="img-fluid" alt=""></a>
                                                                    </li>
                                                                @endif
                                                            @endif

                                                            @if($deletable == 1)
                                                            <li> <span> <a class="deleteTeacherAssign" data-id="{{$assign->id}}" title="Delete Teacher Assign" href="#"><img src="{{ asset('assets/images/delete.png') }}" width="20" class="img-fluid" alt=""></a></span></li>
                                                            @endif

                                                            @if($assign->assigned_date >= date('Y-m-d') )
                                                                @if($deletable == 0)
                                                                <li> <button class="btn btn-danger pending mt-1 " onclick="cancelAllBooking({{$assign->id}})"><span class="label label-danger">Cancel All Bookings</span> </button></li>
                                                                @endif
                                                            @endif
                                                        </ul>
                                                   
                                                </td>
                                               
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="10" class="text-center">No data found.</td>
                                        </tr>
                                    @endif

                                </tbody>
                            </table>
                            <div class="aiz-pagination float-right">
                                {{ $assigned->appends(request()->input())->links() }}
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

    $("#assigned_date").datepicker({
        dateFormat: "yy-mm-dd",
        changeYear: true,
        changeMonth: true,
        yearRange: '-20:+5'
    });

    $(document).on('click','.deleteTeacherAssign',function(){
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
                    url: "{{ route('assign-teacher.delete') }}",
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

    function getTeacherDivisions(course){
        $.ajax({
            url: "{{ route('teacher.divisions.filter') }}",
            type: "GET",
            data: {
                id: course,
                _token:'{{ @csrf_token() }}',
            },
            success: function (response) {
                $('#course_division').empty();
                $('#course_division').append('<option value="">Select Course Division</option>');
                $('#course_division').append(response).trigger('change');
            }
        });
    }

    function cancelAllBooking(id){
        
        Swal.fire({
            title: "Are you sure?",
            text: 'Do you want to cancel all the bookings?',
            icon: 'warning',
            confirmButtonText: "Yes, cancel it!",
            showCancelButton: true,
        }).then((result) => {
            if (result.isConfirmed){
                Swal.fire({
                    title: 'Enter notification message to students',
                    input: 'textarea'
                }).then(function(result) {
                    $.ajax({
                        url: "{{ route('assign-teacher.cancel') }}",
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
</script>
@endsection