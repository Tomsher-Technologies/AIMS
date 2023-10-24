@extends('admin.layouts.app')
@section('title', 'All Courses')
@section('content')
<div class="container-fluid disable-text-selection">
    <div class="row">
        <div class="col-12">
            <div class="mb-3 d-flex align-items-center justify-content-between">
                <h1 class="m-0 p-0">All Courses</h1>
                <div class="btn_group">
                    
                    <a href="{{ route('course.create') }}" class="btn btn_primary">Add Course</a>
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
                    <div class="data_card">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">Sl. No</th>
                                        <th scope="col" class="w-15">Course Name</th>
                                        <th scope="col" class="w-40">Course Description</th>
                                        <th scope="col" class="w-10">Banner Image</th>
                                        <!-- <th scope="col" class="w-20">Divisions</th> -->
                                        <th scope="col" class="text-center">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($courses[0]))
                                        @foreach($courses as $key => $crs)
                                            <tr>
                                                <td>{{ $key + 1 + ($courses->currentPage() - 1) * $courses->perPage() }}</td>
                                                <td>{{ $crs->name }}</td>
                                                <td>{{ $crs->description }}</td>
                                                <td>
                                                    @if($crs->banner_image != '') <img src="{{ asset($crs->banner_image) }}" style="width: 100px;"/>@endif
                                                </td>
                                                <!-- <td>
                                                    @if($crs->course_divisions)
                                                        <ul>
                                                        @foreach($crs->course_divisions as $div)
                                                            <li> 
                                                                @if($div->is_active == 1) 
                                                                    <span class="green">{{ $div->title }}</span>
                                                                @else
                                                                    <span class="error">{{ $div->title }}</span>
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                        </ul>
                                                    @endif
                                                </td> -->
                                                <td class="text-center">
                                                    @if($crs->is_active == 1)
                                                        <span class="green">Active</span>
                                                    @else
                                                        <span class="error">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <ul class="action_list">
                                                        <li class="mt-2 ml-3">
                                                            <a class="" data-id="{{$crs->id}}" title="Edit Course" href="{{ route('course.edit',['id'=>$crs->id]) }}">
                                                                <!-- <img src="{{ asset('assets/images/pencil.png') }}" width="20" class="img-fluid" alt=""> -->
                                                                <i class="simple-icon-pencil view-icon"> </i>
                                                            </a>
                                                        </li>
                                                        <li class="mt-1 ml-3"> <span> <a class="deleteCourse" data-id="{{$crs->id}}" title="Delete Course" href="#">
                                                            <!-- <img src="{{ asset('assets/images/delete.png') }}" width="20" class="img-fluid" alt=""> -->
                                                            <i class="simple-icon-trash view-icon"> </i>
                                                        </a></span></li>
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
                                {{ $courses->appends(request()->input())->links() }}
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
    $(document).on('click','.deleteCourse',function(){
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
                    url: "{{ route('course.delete') }}",
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