@extends('admin.layouts.app')
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
                                        <th scope="col" class="text-center">Duration</th>
                                        <th scope="col" class="text-center">Active Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($assigned[0]))
                                        @foreach($assigned as $key => $assign)
                                            <tr>
                                                <td>{{ $key + 1 + ($assigned->currentPage() - 1) * $assigned->perPage() }}</td>
                                                <td>{{ $assign->assigned_date }}</td>
                                                <td class="text-center">{{ $assign->teacher->name }}</td>
                                                <td class="text-center">{{ $assign->course_division->title }}</td>
                                                <td class="text-center">
                                                {{ $assign->start_time }}
                                                </td>
                                                <td>
                                                {{ $assign->end_time }}
                                                </td>
                                                <td class="text-center">
                                                {{ $assign->time_interval }}
                                                </td>
                                                <td class="text-center">
                                                    @if($assign->is_active == 1)
                                                        <span class="green">Active</span>
                                                    @else
                                                        <span class="error">In-Active</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <ul class="action_list">
                                                        <li>
                                                            <a class="" data-id="{{$assign->id}}" title="Edit Teacher" href="{{ route('teacher.edit',['id'=>$assign->id]) }}"><img src="{{ asset('assets/images/pencil.png') }}" width="20" class="img-fluid" alt=""></a>
                                                        </li>
                                                        <li> <span> <a class="deleteTeacher" data-id="{{$assign->id}}" title="Delete Teacher" href="#"><img src="{{ asset('assets/images/delete.png') }}" width="20" class="img-fluid" alt=""></a></span></li>
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
    $(document).on('click','.deleteTeacher',function(){
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
                    url: "{{ route('teacher.delete') }}",
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