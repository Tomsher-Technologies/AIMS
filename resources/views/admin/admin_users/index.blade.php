@extends('admin.layouts.app')
@section('title', 'All Admin Users')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="mb-3 d-flex align-items-center justify-content-between">
                <h1 class="m-0 p-0">All Admin Users</h1>
                <div class="btn_group">
                    
                    <a href="{{ route('admin.create') }}" class="btn btn_primary">Add New Admin</a>
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
                                        <th scope="col">Name</th>
                                        <th scope="col" class="text-center">Email</th>
                                        <th scope="col" class="text-center">Phone</th>
                                        <th scope="col" class="text-center">Profile Image</th>
                                        <th scope="col" class="w-20">Permissions</th>
                                        <th scope="col" class="text-center">Active Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($admins[0]))
                                        @foreach($admins as $key => $teach)
                                            <tr>
                                                <td>{{ $key + 1 + ($admins->currentPage() - 1) * $admins->perPage() }}</td>
                                                <td>{{ $teach->name }}</td>
                                                <td class="text-center">{{ $teach->email }}</td>
                                                <td class="text-center">{{ $teach->user_details->phone_number }}</td>
                                                <td class="text-center">
                                                    @if($teach->user_details->profile_image != NULL)
                                                    <img class="profileImage" src="{{ asset($teach->user_details->profile_image) }}"/>
                                                    @endif
                                                </td>
                                                <td>
                                                    <!-- {{$teach->teacher_divisions}} -->
                                                    @if($teach->user_permissions)
                                                        <ul>
                                                            @foreach($teach->user_permissions as $div)
                                                                @if($div->permission != null)
                                                                <li> 
                                                                    {{ $div->permission->title }}
                                                                </li>
                                                                @endif
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if($teach->is_active == 1)
                                                        <span class="green">Active</span>
                                                    @else
                                                        <span class="error">In-Active</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <ul class="action_list">
                                                        <li>
                                                            <a class="" data-id="{{$teach->id}}" title="Edit Admin" href="{{ route('admin.edit',['id'=>$teach->id]) }}"><img src="{{ asset('assets/images/pencil.png') }}" width="20" class="img-fluid" alt=""></a>
                                                        </li>
                                                        <li> <span> <a class="deleteAdmin" data-id="{{$teach->id}}" title="Delete Admin" href="#"><img src="{{ asset('assets/images/delete.png') }}" width="20" class="img-fluid" alt=""></a></span></li>
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
                                {{ $admins->appends(request()->input())->links() }}
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
    $(document).on('click','.deleteAdmin',function(){
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
                    url: "{{ route('admin.delete') }}",
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