@extends('admin.layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="mb-3 d-flex align-items-center justify-content-between">
                <h1 class="m-0 p-0">All Classes</h1>
                <div class="btn_group">
                    
                    <a href="{{ route('class.create') }}" class="btn btn_primary">Add New Class</a>
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
                        <form class="" id="classes" action="" method="GET">
                            
                            <div class="form-row">
                                <div class="form-group col-md-2">
                                    <label for="#">Class Title</label>
                                    <input type="text" class="form-control" value="{{ $title_search }}" id="title" name="title" placeholder="Enter class title">
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

                                <div class="form-group col-md-2">
                                    <label for="#">Course Divisions</label>
                                    <select class="form-control"  id="course_division" name="course_division">
                                        <option value="">Select Course Division</option>
                                        @foreach($divisions as $div)
                                            <option value="{{ $div->id }}" @if($division_search == $div->id) selected @endif > {{ $div->title }} </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="#">Course Package</label>
                                    <select class="form-control"  id="package" name="package">
                                        <option value="">Select Package</option>
                                        @foreach($packages as $pack)
                                            <option value="{{ $pack->id }}" @if($package_search == $pack->id) selected @endif > {{ $pack->package_title }} </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-2 filterDiv">
                                    <button type="submit" class="btn btn_primary">Filter</button>
                                    <a href="{{ route('classes') }}"  class="btn btn-info">Reset</a>
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
                                        <th scope="col">Class Title</th>
                                        <th scope="col" class="text-center">Course Name</th>
                                        <th scope="col" class="text-center">Division Name</th>
                                        <th scope="col" class="text-center">Course Packages</th>
                                        <th scope="col" class="text-center">Order</th>
                                        <th scope="col" class="text-center">Mandatory</th>
                                        <th scope="col" class="text-center">Active Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($classes[0]))
                                        @foreach($classes as $key => $cls)
                                            <tr>
                                                <td>{{ $key + 1 + ($classes->currentPage() - 1) * $classes->perPage() }}</td>
                                                <td>{{ $cls->class_name }}</td>
                                                <td class="text-center">{{ $cls->course->name }}</td>
                                                <td class="text-center">{{ $cls->course_division->title }}</td>
                                                <td>
                                                    @if($cls->packages)
                                                        <ul>
                                                            @foreach($cls->packages as $pack)
                                                                <li> 
                                                                    {{ $pack->package->package_title }}
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ $cls->order }}</td>
                                                <td class="text-center">
                                                    @if($cls->is_mandatory == 1)
                                                        <span class="green">Yes</span>
                                                    @else
                                                        <span class="error">No</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if($cls->is_active == 1)
                                                        <span class="green">Active</span>
                                                    @else
                                                        <span class="error">In-Active</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <ul class="action_list">
                                                        <li>
                                                            <a class="" data-id="{{$cls->id}}" title="Edit Class" href="{{ route('class.edit',['id'=>$cls->id]) }}"><img src="{{ asset('assets/images/pencil.png') }}" width="20" class="img-fluid" alt=""></a>
                                                        </li>
                                                        <li> <span> <a class="deleteClass" data-id="{{$cls->id}}" title="Delete Class" href="#"><img src="{{ asset('assets/images/delete.png') }}" width="20" class="img-fluid" alt=""></a></span></li>
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
                                {{ $classes->appends(request()->input())->links() }}
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
    $('div.alert').not('.alert-important').delay(3000).fadeOut(350);
    $(document).on('click','.deleteClass',function(){
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
                    url: "{{ route('class.delete') }}",
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

    function getDivisions(course){
        $.ajax({
            url: "{{ route('course.pack-divisions') }}",
            type: "GET",
            data: {
                id: course,
                _token:'{{ @csrf_token() }}',
            },
            success: function (response) {
                var jsonData = JSON.parse(response);
                
                $('#course_division').empty();
                $('#course_division').append('<option value="">Select Course Division</option>');
                $('#course_division').append(jsonData.divisions).trigger('change');

                $('#package').empty();
                $('#package').append('<option value="">Select Package</option>');
                $('#package').append(jsonData.packages).trigger('change');
            }
        });
    }
</script>
@endsection