@extends('admin.layouts.app')
@section('title', 'All Course Divisions')
@section('content')
<div class="container-fluid disable-text-selection">
    <div class="row">
        <div class="col-12">
            <div class="mb-3 d-flex align-items-center justify-content-between">
                <h1 class="m-0 p-0">All Course Divisions</h1>
                <div class="btn_group">
                    
                    <a href="{{ route('division.create') }}" class="btn btn_primary">Add Course Division</a>
                </div>
            </div>
            
            <div class="separator mb-5"></div>
        </div>
    </div>
    <div class="row list disable-text-selection" data-check-all="checkAll">
        <div class="col-lg-12 col-md-12 mb-4">
            <div class="card recent_certificate">
                <div class="card-body">
                @include('flash::message')
                    <div class="">
                        <!-- <h3> Filters </h3> -->
                        <form class="" id="classes" action="" method="GET">
                            
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="#">Division Title</label>
                                    <input type="text" class="form-control" value="{{ $title_search }}" id="title" name="title" placeholder="Enter division title">
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="#">Course</label>
                                    <select class="form-control"  id="course" name="course" >
                                        <option value="">Select Course</option>
                                        @foreach($courses as $cou)
                                            <option value="{{ $cou->id }}" @if($course_search == $cou->id) selected @endif > {{ $cou->name }} </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="inputPassword4">Active Status</label>
                                    <select class="form-control" name="is_active" id="is_active">
                                        <option value="">Select</option>
                                        <option {{ ($status_search == '1') ? 'selected' : '' }} value="1">Active</option>
                                        <option {{ ($status_search == '0') ? 'selected' : '' }} value="0">In-Active</option>
                                    </select>
                                </div>
                               
                                <div class="form-group col-md-3 filterDiv">
                                    <button type="submit" class="btn btn_primary">Filter</button>
                                    <a href="{{ route('all-divisions') }}"  class="btn btn-info">Reset</a>
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
                                        <th scope="col" class="w-20">Division Name</th>
                                        <th scope="col" class="w-15">Course Name</th>
                                        <th scope="col" class="w-30">Description</th>
                                        <th scope="col" class="text-center">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($divisions[0]))
                                        @foreach($divisions as $key => $crs)
                                            <tr>
                                                <td>{{ $key + 1 + ($divisions->currentPage() - 1) * $divisions->perPage() }}</td>
                                                <td>{{ $crs->title }}</td>
                                                <td>{{ $crs->course_name->name }}</td>
                                                <td>{{ $crs->description }}</td>
                                                
                                                <td class="text-center">
                                                    @if($crs->is_active == 1)
                                                        <span class="green">Active</span>
                                                    @else
                                                        <span class="error">In-Active</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <ul class="action_list">
                                                        <li>
                                                            <a class="" data-id="{{$crs->id}}" title="Edit Course Division" href="{{ route('division.edit',['id'=>$crs->id]) }}"><img src="{{ asset('assets/images/pencil.png') }}" width="20" class="img-fluid" alt=""></a>
                                                        </li>
                                                        <li> <span> <a class="deleteDivision" data-id="{{$crs->id}}" title="Delete Course Division" href="#"><img src="{{ asset('assets/images/delete.png') }}" width="20" class="img-fluid" alt=""></a></span></li>
                                                    </ul>
                                                </td>
                                               
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7" class="text-center">No data found.</td>
                                        </tr>
                                    @endif

                                </tbody>
                            </table>
                            <div class="aiz-pagination float-right">
                                {{ $divisions->appends(request()->input())->links() }}
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
    $(document).on('click','.deleteDivision',function(){
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
                    url: "{{ route('division.delete') }}",
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