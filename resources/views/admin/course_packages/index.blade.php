@extends('admin.layouts.app')
@section('content')
<div class="container-fluid disable-text-selection">
    <div class="row">
        <div class="col-12">
            <div class="mb-3 d-flex align-items-center justify-content-between">
                <h1 class="m-0 p-0">All Course Packages</h1>
                <div class="btn_group">
                    
                    <a href="{{ route('packages.create') }}" class="btn btn_primary">Add Course Package</a>
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
                                        <th scope="col">Package Name</th>
                                        <th scope="col" class="w-20">Description</th>
                                        <th scope="col" class="text-center">Course Name</th>
                                        <th scope="col" class="text-center">Duration</th>
                                        <th scope="col" class="text-center">Course Fee</th>
                                        <th scope="col" class="w-20">Divisions</th>
                                        <th scope="col" class="text-center">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($packages[0]))
                                        @foreach($packages as $key => $pack)
                                            <tr>
                                                <td>{{ $key + 1 + ($packages->currentPage() - 1) * $packages->perPage() }}</td>
                                                <td>{{ $pack->package_title }}</td>
                                                <td>{{ $pack->description }}</td>
                                                <td class="text-center">{{ $pack->course_name->name }}</td>
                                                <td class="text-center">{{ $pack->duration }}</td>
                                                <td class="text-center">{{config('constants.default_currency')}} {{ $pack->fees }}</td>
                                                <td>
                                                    
                                                    @if($pack->active_package_modules)
                                                        <ul>
                                                            @foreach($pack->active_package_modules as $div)
                                                                @if($div->course_division != null)
                                                                <li> 
                                                                    {{ $div->course_division->title }}
                                                                </li>
                                                                @endif
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if($pack->is_active == 1)
                                                        <span class="green">Active</span>
                                                    @else
                                                        <span class="error">In-Active</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <ul class="action_list">
                                                        <li>
                                                            <a class="" data-id="{{$pack->id}}" title="Edit Course Package" href="{{ route('packages.edit',['id'=>$pack->id]) }}"><img src="{{ asset('assets/images/pencil.png') }}" width="20" class="img-fluid" alt=""></a>
                                                        </li>
                                                        <li> <span> <a class="deletePackage" data-id="{{$pack->id}}" title="Delete Course Package" href="#"><img src="{{ asset('assets/images/delete.png') }}" width="20" class="img-fluid" alt=""></a></span></li>
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
                                {{ $packages->appends(request()->input())->links() }}
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
    $(document).on('click','.deletePackage',function(){
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
                    url: "{{ route('packages.delete') }}",
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