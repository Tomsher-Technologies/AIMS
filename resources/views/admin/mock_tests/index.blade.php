@extends('admin.layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="mb-3 d-flex align-items-center justify-content-between">
                <h1 class="m-0 p-0">Mock Test Results</h1>
                <div class="btn_group">
                    <a href="{{ route('mock.bulk-create') }}" class="btn btn-success">Upload Bulk Test Result</a>
                    <a href="{{ route('mock.create') }}" class="btn btn_primary">Add New Test Result</a>
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
                                    <label for="#">Test Date</label>
                                    <input type="text" class="form-control" value="{{ $date_search }}" id="date_search" name="date_search" placeholder="YYYY-MM-DD" >
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="#">Student</label>
                                    <select class="form-control select2"  id="student_search" name="student_search" >
                                        <option value="">Select Student</option>
                                        @foreach($students as $student)
                                            <option value="{{ $student->id }}" {{ ($student_search == $student->id) ? 'selected' : '' }}> {{ $student->name }} ({{ $student->unique_id }})</option>
                                        @endforeach
                                    </select>
                                </div>



                                <div class="form-group col-md-3 filterDiv">
                                    <button type="submit" class="btn btn_primary">Filter</button>
                                    <a href="{{ route('mock-tests') }}"  class="btn btn-info">Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="data_card">
                        <div class="table-responsive">
                            
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col" colspan="4"></th>
                                        <th scope="col" colspan="4" class="text-center">Listening</th>
                                        <th scope="col" colspan="4" class="text-center">Reading</th>
                                        <th scope="col"></th>
                                    </tr>
                                    <tr>
                                        <th scope="col" class="text-center">Sl. No</th>
                                        <th scope="col" class="text-center">Date</th>
                                        <th scope="col">Student Name</th>
                                        <th scope="col" class="text-center">Student Code</th>
                                        <th scope="col" class="text-center">A</th>
                                        <th scope="col" class="text-center">B</th>
                                        <th scope="col" class="text-center">C</th>
                                        <th scope="col" class="text-center">Total</th>
                                        <th scope="col" class="text-center">A</th>
                                        <th scope="col" class="text-center">B</th>
                                        <th scope="col" class="text-center">C</th>
                                        <th scope="col" class="text-center">Total</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($tests[0]))
                                        @foreach($tests as $key => $cls)
                                            <tr>
                                                <td class="text-center">{{ $key + 1 + ($tests->currentPage() - 1) * $tests->perPage() }}</td>
                                                <td class="text-center">{{ $cls->test_date }}</td>
                                                <td>{{ $cls->name }}</td>
                                                <td class="text-center">{{ $cls->unique_id }}</td>
                                                <td class="text-center">{{ $cls->listening_a ?? '-'}}</td>
                                                <td class="text-center">{{ $cls->listening_b ?? '-' }}</td>
                                                <td class="text-center">{{ $cls->listening_c ?? '-' }}</td>
                                                <td class="text-center"><b>{{ $cls->listening_total ?? '-' }}</b></td>
                                                <td class="text-center">{{ $cls->reading_a ?? '-' }}</td>
                                                <td class="text-center">{{ $cls->reading_b ?? '-' }}</td>
                                                <td class="text-center">{{ $cls->reading_c ?? '-' }}</td>
                                                <td class="text-center"><b>{{ $cls->reading_total ?? '-' }}</b></td>
                                                <td>
                                                    <ul class="action_list">
                                                        <li>
                                                            <a class="" data-id="{{$cls->id}}" title="Edit Test Result" href="{{ route('mock.edit',['id'=>$cls->id]) }}"><img src="{{ asset('assets/images/pencil.png') }}" width="20" class="img-fluid" alt=""></a>
                                                        </li>
                                                        <li> <span> <a class="deleteTest" data-id="{{$cls->id}}" title="Delete Test Result" href="#"><img src="{{ asset('assets/images/delete.png') }}" width="20" class="img-fluid" alt=""></a></span></li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="13" class="text-center">No data found.</td>
                                        </tr>
                                    @endif

                                </tbody>
                            </table>
                            <div class="aiz-pagination float-right">
                                {{ $tests->appends(request()->input())->links() }}
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
<style>
.table td, .table th {
    border-color: #cdcdcd!important;
}
.select2 {
        width:inherit !important
    }
</style>
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
    $('#student_search').select2({
       
    });

    $(document).on('click','.deleteTest',function(){
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
                    url: "{{ route('mock.delete') }}",
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
</script>
@endsection