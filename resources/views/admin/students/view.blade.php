@extends('admin.layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="mb-3 d-flex align-items-center justify-content-between">
                <h1 class="m-0 p-0">Student Details</h1>
                <div class="btn_group">
                    <a href="{{ route('students') }}" class="btn btn-primary btn-lg top-right-button btn_primary">Back</a>
                </div>
            </div>
            
            <div class="separator mb-5"></div>
        </div>
    </div>
    <div class="row list " data-check-all="checkAll">
        <div class="col-lg-12 col-md-12 mb-4">
            <div class="card recent_certificate">
                <div class="card-body">
                    <div class="data_card">
                        
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
    
</script>
@endsection