@extends('admin.layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="mb-3 d-flex align-items-center justify-content-between">
                <h1 class="m-0 p-0">All Attendance Details</h1>
                <div class="btn_group">
                    
                    <a href="{{ route('attendance') }}" class="btn btn_primary">Add Attendance</a>
                </div>
            </div>
            
            <div class="separator mb-5"></div>
        </div>
    </div>
    <div class="row list" data-check-all="checkAll">
        <div class="col-lg-12 col-md-12 mb-4">
            <div class="card recent_certificate">
                <div class="card-body">
                @include('flash::message')
                    <div class="">
                        <!-- <h3> Filters </h3> -->
                        <form class="" id="classes" action="" method="GET" autocomplete="off">
                            
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="#">Attendance Date</label>
                                    <input type="text" class="form-control" value="{{ $date_search }}" id="date_search" name="date_search" placeholder="YYYY-MM-DD" >
                                </div>

                                <div class="form-group col-md-3 filterDiv">
                                    <button type="submit" class="btn btn_primary">Filter</button>
                                    <a href="{{ route('attendance-list') }}"  class="btn btn-info">Reset</a>
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
                                        <th scope="col">Date</th>
                                        <th scope="col">Class Name</th>
                                        <th scope="col">Course Name</th>
                                        <th scope="col">Division Name</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($attendances[0]))
                                        @foreach($attendances as $key => $cls)
                                            <tr>
                                                <td>{{ $key + 1 + ($attendances->currentPage() - 1) * $attendances->perPage() }}</td>
                                                <td>{{ $cls->attend_date }}</td>
                                                <td>{{ $cls->class->class_name }}</td>
                                                <td>{{ $cls->course->name }}</td>
                                                <td>{{ $cls->course_division->title }}</td>
                                                <td>
                                                    <ul class="action_list">
                                                        <li class="mt-1" style="margin-bottom: 0px;">
                                                            <a class="" data-id="{{$cls->id}}" title="View Attendance" href="{{ route('view-attendance',['id'=>$cls->id]) }}">
                                                                <!-- <img src="{{ asset('assets/images/eye.png') }}" width="20" class="img-fluid" alt=""> -->
                                                            <i class="simple-icon-eye view-icon"> </i>
                                                        </a>
                                                        </li>
                                                        <li  class="mt-1" style="margin-bottom: 0px;">
                                                            <a class="" data-id="{{$cls->id}}" title="Edit Attendance" href="{{ route('edit-attendance',['id'=>$cls->id]) }}">
                                                                <!-- <img src="{{ asset('assets/images/pencil.png') }}" width="20" class="img-fluid" alt=""></a> -->
                                                                <i class="simple-icon-pencil edit-icon"> </i>
                                                        </li>
                                                        
                                                    </ul>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6" class="text-center">No data found.</td>
                                        </tr>
                                    @endif

                                </tbody>
                            </table>
                            <div class="aiz-pagination float-right">
                                {{ $attendances->appends(request()->input())->links() }}
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
<link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
@endsection
@section('footer')
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script type="text/javascript">
   $("#date_search").datepicker({
        dateFormat: "yy-mm-dd",
        changeYear: true,
        changeMonth: true,
        yearRange: '-60:+2'
    });
</script>
@endsection