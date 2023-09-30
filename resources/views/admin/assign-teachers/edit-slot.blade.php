@extends('admin.layouts.app')
@section('title', 'Delete Time Slots')
@section('content')
<div class="container-fluid disable-text-selection">
    <div class="row">
        <div class="col-12">
            <div class="mb-0">
                <h1>Delete Time Slots</h1>
                <div class="text-zero top-right-button-container">
                    <a href="{{ route('assign-teachers') }}" class="btn btn-primary btn-lg top-right-button btn_primary">Back</a>
                </div>
            </div>
            <div class="separator mb-5"></div>
        </div>
    </div>
    <div class="row ">
        <div class="col-lg-12 col-md-12 mb-4">
            <div class="card mb-4">
            @include('flash::message')
                <div class="card-body">
                    <form class="form-horizontal " action="{{ route('assign-teacher.update-slot', $assign->id) }}" method="POST"
                        enctype="multipart/form-data" autocomplete="off" id="">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-10 offset-sm-2">
                                <h4>Time Slots</h4>
                            </div>
                            <div class="form-group col-md-10 offset-sm-2">
                                <h5>Teacher : {{ $assign->teacher->name }}</h5>
                                <h5>Date : {{ $assign->assigned_date }}</h5>
                            </div>
                            <table class="col-md-3 offset-sm-2">
                                @foreach($slots as $sl)
                                <tr>
                                    <td> 
                                        @php 
                                            if($sl->is_booked == 1){
                                                $color = 'error';
                                            }else{
                                                $color = 'green';
                                            }
                                        @endphp
                                        <span for="" class="{{$color}}">{{ $sl->slot }} {{ ($sl->is_booked == 1) ? "(Booked)" : "" }}</span>  
                                    </td>
                                    <td>
                                        <div class="">
                                            @if($sl->is_booked == 0 )
                                            <input type="checkbox" class="form-control font-size-5" value="{{$sl->id}}" id="slot" name="slot[]" >
                                            @endif
                                        </div>
                                     </td>
                                </tr>
                                @endforeach
                            </table>
                            


                            <div class="form-group col-md-10 offset-sm-2 d-flex mt-4">
                                <button type="submit" class="btn btn-success d-block mt-2">Save</button>
                                <a href="{{ route('assign-teachers') }}" class="btn btn-info d-block mt-2 ml-2">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('header')

<style>
    .select2 {
        width:inherit !important
    }
    .ui-timepicker-wrapper{
        width: 15% !important;
    }
    .font-size-5{
        font-size : 5px !important;
    }
    
</style>    
@endsection
@section('footer')


<script type="text/javascript">
    $(document).ready(function() {
     
    });

</script>
@endsection