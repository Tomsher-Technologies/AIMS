@extends('admin.layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="mb-3 d-flex align-items-center justify-content-between">
                <h1 class="m-0 p-0">Student Remarks </h1>
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
                                <div class="form-group col-md-4">
                                    <label for="#">Search By Name/Student Code/Email</label>
                                    <input type="text" class="form-control" value="{{ $title_search }}" id="title" name="title" placeholder="Enter Name/Student Code/Email">
                                </div>

                                <div class="form-group col-md-2 filterDiv">
                                    <button type="submit" class="btn btn_primary">Filter</button>
                                    <a href="{{ route('remarks') }}"  class="btn btn-info">Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="data_card">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col" class="w-5 text-center">Sl. No</th>
                                        <th scope="col" class="w-20">Student Name</th>
                                        <th scope="col" class="text-center w-10">Student Code</th>
                                        <th scope="col" class="w-50">Remark</th>
                                        <th scope="col" class="w-15">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($remarks[0]))
                                        @foreach($remarks as $key => $rem)
                                            <tr>
                                                <td class="text-center">{{ $key + 1 + ($remarks->currentPage() - 1) * $remarks->perPage() }}</td>
                                                <td>{{ $rem->name }}</td>
                                                <td class="text-center">{{ $rem->unique_id }}</td>
                                                <td class="text-center">{{ $rem->remarks }}</td>          
                                               
                                                <td>
                                                    {{ ($rem->created_at != null) ? date('Y-m-d H:i a', strtotime($rem->created_at)) :'' }}
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
                                {{ $remarks->appends(request()->input())->links() }}
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
    

</script>
@endsection